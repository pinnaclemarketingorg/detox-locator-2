<?php
/**
 * The benefits form's only destination.
 *
 * Two callers. The page script posts JSON and reads back the real outcome;
 * without JavaScript the form posts normally and gets a 303 back to the page
 * it came from with ?submitted=1, which renders the confirmation. Either way
 * this hands the lead to CallTrackingMetrics and logs it.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

require_once dirname( __DIR__ ) . '/app/bootstrap.php';
require_once dirname( __DIR__ ) . '/app/routes.php';
require_once dirname( __DIR__ ) . '/app/ctm.php';

$is_json = str_contains( strtolower( (string) ( $_SERVER['CONTENT_TYPE'] ?? '' ) ), 'application/json' );

if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
	if ( $is_json ) {
		dl_json( array( 'delivered' => false, 'reason' => 'method' ), 405 );
	}
	header( 'Location: /', true, 303 );
	exit;
}

$input = $is_json ? json_decode( (string) file_get_contents( 'php://input' ), true ) : $_POST;
if ( ! is_array( $input ) ) {
	dl_json( array( 'delivered' => false, 'reason' => 'bad_request' ), 400 );
}

/** Trim, flatten and cap a field — nothing here should be long. */
function dl_field( array $src, string $key, int $max = 120 ): string {
	$value = isset( $src[ $key ] ) && is_scalar( $src[ $key ] ) ? (string) $src[ $key ] : '';
	$value = trim( (string) preg_replace( '/[\r\n\t]+/', ' ', $value ) );
	return dl_truncate( $value, $max );
}

// Only a known route can be a return address, so this is not an open redirect.
$page = dl_field( $input, 'page', 40 );
$page = isset( DL_ROUTES[ $page ] ) ? $page : '';

/** Where a no-JS submission lands afterwards. */
function dl_back( string $page, bool $ok ): void {
	header( 'Location: /' . ( '' === $page ? '' : $page . '/' ) . '?submitted=' . ( $ok ? '1' : '0' ) . '#verify', true, 303 );
	exit;
}

/*
 * Honeypot: a field hidden from people and from assistive tech. Answer as
 * though it worked, so a bot has nothing to learn from the response.
 */
if ( '' !== dl_field( $input, 'company' ) ) {
	$is_json ? dl_json( array( 'delivered' => true, 'reason' => 'ok' ) ) : dl_back( $page, true );
}

$lead = array(
	'first'       => dl_field( $input, 'first', 60 ),
	'last'        => dl_field( $input, 'last', 60 ),
	'phone'       => dl_field( $input, 'phone', 30 ),
	'dob'         => dl_field( $input, 'dob', 20 ),
	'insurance'   => dl_field( $input, 'insurance', 80 ),
	'policy'      => dl_field( $input, 'policy', 60 ),
	'consent'     => '' !== dl_field( $input, 'consent', 10 ) ? '1' : '',
	'looking_for' => dl_field( $input, 'looking_for', 60 ),
	'page'        => '' === $page ? 'home' : $page,
);

// Phone is the one field admissions genuinely cannot work without.
if ( '' === dl_normalize_phone( $lead['phone'] ) ) {
	$is_json ? dl_json( array( 'delivered' => false, 'reason' => 'no_phone' ), 422 ) : dl_back( $page, false );
}

/*
 * Attribution. The page script snapshots the ad parameters on landing and
 * sends them as an object; a no-JS post carries them as hidden fields copied
 * from the query string at render time. ValueTrack's native spellings are
 * mapped to the names CTM expects.
 */
$aliases = array(
	'gclid' => 'gclid', 'gbraid' => 'gbraid', 'wbraid' => 'wbraid', 'msclkid' => 'msclkid',
	'utm_source' => 'utm_source', 'utm_medium' => 'utm_medium', 'utm_campaign' => 'utm_campaign',
	'utm_content' => 'utm_content', 'utm_term' => 'utm_term',
	'campaign_id' => 'campaign_id', 'campaignid' => 'campaign_id',
	'adgroup_id' => 'adgroup_id', 'adgroupid' => 'adgroup_id',
	'creative_id' => 'creative_id', 'creative' => 'creative_id',
	'landing_page' => 'landing_page', 'referrer' => 'referrer',
);

$source      = isset( $input['attribution'] ) && is_array( $input['attribution'] ) ? $input['attribution'] : $input;
$attribution = array();
foreach ( $aliases as $from => $to ) {
	if ( isset( $source[ $from ] ) && is_scalar( $source[ $from ] ) && '' !== (string) $source[ $from ] && ! isset( $attribution[ $to ] ) ) {
		$attribution[ $to ] = dl_truncate( trim( (string) $source[ $from ] ), 300 );
	}
}

$result = dl_deliver_lead( $lead, $attribution, dl_field( $input, 'visitor_sid', 120 ) );
dl_log_lead( $lead + array( 'attribution' => $attribution ), $result );

/*
 * A no-JS visitor is told it worked even when CTM rejected it: the lead is in
 * the log and someone will call either way, and making them re-type it loses
 * more enquiries than it saves. The JSON caller gets the real outcome, which
 * is what the page reports to GTM.
 */
$is_json ? dl_json( $result ) : dl_back( $page, true );
