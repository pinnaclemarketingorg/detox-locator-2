<?php
/**
 * CallTrackingMetrics FormReactor delivery.
 *
 * The same stack the other microsites use. WordPress was the only thing
 * providing Gravity Forms, so this is what replaces it: the form posts here,
 * this hands it to CTM, and the visitor sees the same confirmation Gravity
 * used to render.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

defined( 'DL_APP' ) || exit;

/** Digits only, with a leading 1 for a 10-digit US number. '' if implausible. */
function dl_normalize_phone( string $raw ): string {
	$digits = preg_replace( '/\D/', '', $raw ) ?? '';

	if ( 10 === strlen( $digits ) ) {
		$digits = '1' . $digits;
	}
	// 11 digits starting with 1 is the only shape worth sending.
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
function dl_deliver_lead( array $lead, array $attribution = array() ): array {
	$formreactor = dl_cfg( 'ctm_formreactor' );
	$key         = dl_cfg( 'ctm_key' );

	if ( '' === $formreactor || '' === $key ) {
		return array(
			'delivered' => false,
			'reason'    => 'not_configured',
		);
	}

	$phone = dl_normalize_phone( $lead['phone'] ?? '' );
	if ( '' === $phone ) {
		return array(
			'delivered' => false,
			'reason'    => 'no_phone',
		);
	}

	/*
	 * Field shape follows the FormReactor's own documented POST exactly:
	 * phone_number at the top level, ad-click data under paid_attribution[],
	 * everything else under custom_fields[]. The custom field *names* are the
	 * ones configured on this FormReactor — sending a name it does not know
	 * gets that value dropped silently, which is why these are not renamed to
	 * something tidier.
	 *
	 * phone_number goes in E.164: the endpoint's example is +1XXXXXXXXXX.
	 */
	$payload = array(
		'phone_number' => '+' . $phone,
	);

	$name = trim( ( $lead['first'] ?? '' ) . ' ' . ( $lead['last'] ?? '' ) );

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

	// Google / Microsoft click data. CTM matches these against the ad
	// platforms, so they belong in paid_attribution rather than as custom
	// fields — the same value in the wrong bucket does not get attributed.
	$paid = array(
		'gclid'       => $attribution['gclid'] ?? '',
		'msclkid'     => $attribution['msclkid'] ?? '',
		'campaign_id' => $attribution['campaign_id'] ?? '',
		'adgroup_id'  => $attribution['adgroup_id'] ?? '',
		'creative_id' => $attribution['creative_id'] ?? '',
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

	$response = dl_post_form( $url, $payload );

	if ( null === $response ) {
		return array(
			'delivered' => false,
			'reason'    => 'transport',
		);
	}
	if ( $response['status'] < 200 || $response['status'] >= 300 ) {
		return array(
			'delivered' => false,
			'reason'    => 'http_' . $response['status'],
		);
	}

	return array(
		'delivered' => true,
		'reason'    => 'ok',
	);
}

/**
 * POST form-encoded data. cURL when it is available, streams otherwise, so
 * this keeps working on a host without the cURL extension.
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
				CURLOPT_HTTPHEADER     => array( 'Content-Type: application/x-www-form-urlencoded' ),
			)
		);
		$out    = curl_exec( $ch );
		$status = (int) curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );
		$errno  = curl_errno( $ch );
		curl_close( $ch );

		if ( 0 !== $errno ) {
			return null;
		}
		return array(
			'status' => $status,
			'body'   => (string) $out,
		);
	}

	$context = stream_context_create(
		array(
			'http' => array(
				'method'        => 'POST',
				'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
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

	return array(
		'status' => $status,
		'body'   => (string) $out,
	);
}

/**
 * Append the lead to a JSON-lines file, so an enquiry still exists somewhere
 * if CTM is misconfigured or down.
 *
 * The path must be inside private_html; a log under the webroot would be
 * downloadable, and it holds names, numbers and policy numbers.
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
