<?php
/**
 * Server-side region lookup for the dynamic-location variants (-dli).
 *
 * The live /lpa-2-dli/ calls ipinfo.io from the browser with the token in the
 * page source. Same lookup, same token, moved here:
 *
 *   1. The token lives in private_html and never ships to the browser.
 *   2. Ad blockers block ipinfo.io outright, so a slice of paid traffic got
 *      the fallback line. A same-origin endpoint is on no blocklist.
 *   3. Results are cached per /24, so a burst of clicks from one campaign
 *      costs one upstream request instead of hundreds.
 *
 * The swap stays client-side on purpose: baking the state into the HTML would
 * mean any page cache in front of the app serves the first visitor's state to
 * everybody after them.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

require_once dirname( __DIR__ ) . '/app/bootstrap.php';

const DL_REGION_TTL = 43200; // 12 hours
const DL_MISS_TTL   = 900;   // re-try a failing upstream after 15 minutes

$token = dl_cfg( 'ipinfo_token' );
$ip    = dl_visitor_ip();

if ( '' === $token || null === $ip ) {
	dl_json( array( 'region' => null ) );
}

// Same neighbourhood, same state — and it collapses a campaign burst into one
// upstream call.
$parts = explode( '.', $ip );
$key   = 'region_' . $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.0';

$cached = dl_cache_get( $key );
if ( null !== $cached ) {
	dl_json( array( 'region' => '' === $cached ? null : $cached ) );
}

$ch = curl_init( 'https://ipinfo.io/' . rawurlencode( $ip ) . '/json?token=' . rawurlencode( $token ) );
curl_setopt_array(
	$ch,
	array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 2, // a slow lookup must never hold up the page
		CURLOPT_CONNECTTIMEOUT => 1,
		CURLOPT_USERAGENT      => 'detox-locator.com',
	)
);
$body   = curl_exec( $ch );
$status = (int) curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );

$region = '';
if ( false !== $body && 200 === $status ) {
	$data = json_decode( (string) $body, true );
	// Only a US state. A region from anywhere else dropped into "Find
	// Treatment in ___ in Minutes" reads as broken.
	if ( is_array( $data ) && 'US' === ( $data['country'] ?? '' ) && ! empty( $data['region'] ) ) {
		$region = (string) ( dl_clean_place( (string) $data['region'] ) ?? '' );
	}
}

dl_cache_set( $key, $region, '' !== $region ? DL_REGION_TTL : DL_MISS_TTL );

dl_json( array( 'region' => '' === $region ? null : $region ) );
