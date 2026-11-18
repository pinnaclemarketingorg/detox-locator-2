<?php
/**
 * CallTrackingMetrics FormReactor delivery.
 *
 * One delivery path, server-side. The form posts to /api/lead.php and this is
 * the only thing that talks to CTM — CORS-free, invisible to ad blockers, and
 * the only place the outcome is actually observable. t.js still loads on every
 * page, for dynamic number insertion and the visitor session leads attach to.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

defined( 'DL_ROOT' ) || exit;

/** Digits only, with a leading 1 for a 10-digit US number. '' if implausible. */
function dl_normalize_phone( string $raw ): string {
	$digits = preg_replace( '/\D/', '', $raw ) ?? '';

	if ( 10 === strlen( $digits ) ) {
		$digits = '1' . $digits;
	}
	if ( 11 !== strlen( $digits ) || '1' !== $digits[0] ) {
		return '';
	}
	// A US area code never starts with 0 or 1.
	if ( in_array( $digits[1], array( '0', '1' ), true ) ) {
		return '';
	}

	return $digits;
}

/**
 * Hand a lead to CTM.
 *
 * Returns the real outcome rather than always claiming success, so a
 * misconfiguration shows up in the log instead of silently eating enquiries.
 *
 * @param array<string,string> $lead        Normalised field values.
 * @param array<string,string> $attribution gclid / utm_* and friends.
 * @return array{delivered:bool,reason:string}
 */
function dl_deliver_lead( array $lead, array $attribution, string $sid = '' ): array {
	$formreactor = dl_cfg( 'ctm_formreactor' );
	$key         = dl_cfg( 'ctm_key' );

	if ( '' === $formreactor || '' === $key ) {
		error_log( '[dl] lead NOT delivered: CTM FormReactor not configured' );
		return array( 'delivered' => false, 'reason' => 'not_configured' );
	}

	$phone = dl_normalize_phone( $lead['phone'] ?? '' );
	if ( '' === $phone ) {
		return array( 'delivered' => false, 'reason' => 'no_phone' );
	}

	/*
	 * Field shape follows the FormReactor's own documented POST: phone_number
	 * at the top level, ad-click data under paid_attribution[], everything
	 * else under custom_fields[]. The custom field *names* are the ones
	 * configured on this FormReactor — CTM silently drops a value whose name
	 * it does not know, which is why these are not renamed to something tidier.
	 */
	$payload = array( 'phone_number' => '+' . $phone );

	$name = trim( ( $lead['first'] ?? '' ) . ' ' . ( $lead['last'] ?? '' ) );
	if ( '' !== $name ) {
		$payload['caller_name'] = $name;
	}

	// Ties the lead to the tracked session's source when the tracker ran.
	if ( '' !== $sid ) {
		$payload['visitor_sid'] = $sid;
	}

	$custom = array(
		'patient_name'  => $name,
		'date_of_birth' => $lead['dob'] ?? '',
		'insurance'     => $lead['insurance'] ?? '',
		'policy_id'     => $lead['policy'] ?? '',
		'landing_page'  => $lead['page'] ?? '',
		'looking_for'   => $lead['looking_for'] ?? '',
		'utm_source'    => $attribution['utm_source'] ?? '',
		'utm_medium'    => $attribution['utm_medium'] ?? '',
		'utm_campaign'  => $attribution['utm_campaign'] ?? '',
		'utm_content'   => $attribution['utm_content'] ?? '',
		'utm_term'      => $attribution['utm_term'] ?? '',
		'consent'       => ( $lead['consent'] ?? '' ) ? 'yes' : 'no',
	);

	// Click data belongs in paid_attribution: CTM matches it against the ad
	// platforms, and the same value in the wrong bucket is not attributed.
	$paid = array(
		'gclid'        => $attribution['gclid'] ?? '',
		'gbraid'       => $attribution['gbraid'] ?? '',
		'wbraid'       => $attribution['wbraid'] ?? '',
		'msclkid'      => $attribution['msclkid'] ?? '',
		'campaign_id'  => $attribution['campaign_id'] ?? '',
		'adgroup_id'   => $attribution['adgroup_id'] ?? '',
		'creative_id'  => $attribution['creative_id'] ?? '',
		'landing_page' => $attribution['landing_page'] ?? '',
		'referrer'     => $attribution['referrer'] ?? '',
	);

	foreach ( $custom as $field => $value ) {
		if ( '' !== (string) $value ) {
			$payload[ 'custom_fields[' . $field . ']' ] = (string) $value;
		}
	}
	foreach ( $paid as $field => $value ) {
		if ( '' !== (string) $value ) {
			$payload[ 'paid_attribution[' . $field . ']' ] = (string) $value;
		}
	}

	// The key is a query parameter on the FormReactor URL, which is how CTM
	// documents it. It never appears in the page or the POST body.
	$url = sprintf(
		'https://api.calltrackingmetrics.com/api/v1/formreactor/%s?key=%s',
		rawurlencode( $formreactor ),
		rawurlencode( $key )
	);

	for ( $attempt = 1; $attempt <= 2; $attempt++ ) {
		$response = dl_post_form( $url, $payload );

		if ( null !== $response && $response['status'] >= 200 && $response['status'] < 300 && dl_ctm_accepted( $response['body'] ) ) {
			return array( 'delivered' => true, 'reason' => 'ok' );
		}

		// Only a network failure, a 429 or a 5xx is worth one retry. A
		// rejection fails identically the second time.
		$status    = null === $response ? 0 : $response['status'];
		$retryable = 0 === $status || 429 === $status || $status >= 500;

		if ( ! $retryable || 2 === $attempt ) {
			error_log( sprintf( '[dl] CTM rejected lead (attempt %d): http %d %s', $attempt, $status, substr( $response['body'] ?? '', 0, 300 ) ) );
			return array( 'delivered' => false, 'reason' => 0 === $status ? 'transport' : 'http_' . $status );
		}

		usleep( 400000 );
	}

	return array( 'delivered' => false, 'reason' => 'unknown' );
}

/**
 * Did CTM accept it? HTTP 200 is not delivery: the FormReactor answers 200
 * with an error body for a rejected payload. A body that is not JSON at all
 * is taken as accepted, so an unexpected-but-successful response is not
 * logged as a loss.
 */
function dl_ctm_accepted( string $body ): bool {
	$data = json_decode( $body, true );
	if ( ! is_array( $data ) ) {
		return true;
	}
	if ( ! empty( $data['error'] ) || ( isset( $data['status'] ) && in_array( $data['status'], array( false, 'error', 'failed' ), true ) ) ) {
		return false;
	}
	return true;
}

/**
 * POST form-encoded data. cURL when available, streams otherwise.
 *
 * @return array{status:int,body:string}|null
 */
function dl_post_form( string $url, array $fields ): ?array {
	$body = http_build_query( $fields );

	if ( function_exists( 'curl_init' ) ) {
		$ch = curl_init( $url );
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => $body,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT        => 10,
				CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_USERAGENT      => 'detox-locator.com',
				CURLOPT_HTTPHEADER     => array( 'Content-Type: application/x-www-form-urlencoded', 'Accept: application/json' ),
			)
		);
		$out    = curl_exec( $ch );
		$status = (int) curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );
		$errno  = curl_errno( $ch );

		if ( 0 !== $errno ) {
			return null;
		}
		return array( 'status' => $status, 'body' => (string) $out );
	}

	$context = stream_context_create(
		array(
			'http' => array(
				'method'        => 'POST',
				'header'        => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
				'content'       => $body,
				'timeout'       => 10,
				'ignore_errors' => true,
			),
		)
	);

	$out = @file_get_contents( $url, false, $context );
	if ( false === $out ) {
		return null;
	}

	$status = 0;
	foreach ( $http_response_header ?? array() as $header ) {
		if ( preg_match( '#^HTTP/\S+\s+(\d{3})#', $header, $m ) ) {
			$status = (int) $m[1];
		}
	}

	return array( 'status' => $status, 'body' => (string) $out );
}

/**
 * Append the lead to a JSON-lines file, so an enquiry still exists somewhere
 * if CTM is misconfigured or down. The path must be inside private_html — the
 * log holds names, numbers and policy numbers.
 */
function dl_log_lead( array $lead, array $result ): void {
	$path = dl_cfg( 'lead_log' );
	if ( '' === $path ) {
		return;
	}

	$line = json_encode(
		array(
			'at'     => gmdate( 'c' ),
			'page'   => $lead['page'] ?? '',
			'result' => $result,
			'lead'   => $lead,
		)
	);

	@file_put_contents( $path, $line . "\n", FILE_APPEND | LOCK_EX );
}
