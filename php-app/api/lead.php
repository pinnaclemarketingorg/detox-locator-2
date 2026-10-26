<?php
/**
 * The form's destination.
 *
 * The mirrored markup is Gravity's, so the field names arriving here are
 * Gravity's too. One wrinkle: PHP rewrites '.' to '_' in POST keys, so the
 * field Gravity renders as `input_1.3` arrives as `input_1_3`. Both spellings
 * are accepted below rather than relying on that behaviour holding.
 *
 * This is a normal form POST, not fetch(), so the response is a redirect back
 * to the landing page with ?submitted=1 — which is what makes the page render
 * the confirmation block instead of the form.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

define( 'DL_APP', true );

require_once dirname( __DIR__ ) . '/app/bootstrap.php';
require_once dirname( __DIR__ ) . '/app/ctm.php';

/** Where to send the visitor afterwards. */
function dl_return_to( string $slug, bool $ok ): string {
	$paths = array(
		'home'       => '/',
		'lpa-2'      => '/lpa-2/',
		'lpa-2-dkwi' => '/lpa-2-dkwi/',
		'lpa-2-dli'  => '/lpa-2-dli/',
		'lpa-bing'   => '/lpa-bing/',
	);
	$path = $paths[ $slug ] ?? '/lpa-2/';

	return $path . ( $ok ? '?submitted=1' : '?submitted=0' ) . '#verify';
}

/** Read a field, accepting either spelling of a Gravity complex-field key. */
function dl_field( string ...$keys ): string {
	foreach ( $keys as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$value = is_array( $_POST[ $key ] ) ? reset( $_POST[ $key ] ) : $_POST[ $key ];
		$value = trim( (string) preg_replace( '/[\r\n\t]+/', ' ', (string) $value ) );
		if ( '' !== $value ) {
			return $value;
		}
	}
	return '';
}

function dl_cap( string $value, int $max ): string {
	return function_exists( 'mb_substr' ) ? mb_substr( $value, 0, $max ) : substr( $value, 0, $max );
}

if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
	header( 'Location: /lpa-2/', true, 303 );
	exit;
}

$slug = dl_field( 'dl_page' );
$slug = preg_match( '/^[a-z0-9-]{1,40}$/', $slug ) ? $slug : 'lpa-2';

/*
 * Honeypot. A field positioned off-screen and hidden from assistive tech;
 * a human never fills it, most naive bots do. Answer 303 as though it worked
 * so the bot has nothing to learn from the response.
 */
if ( '' !== dl_field( 'dl_hp' ) ) {
	header( 'Location: ' . dl_return_to( $slug, true ), true, 303 );
	exit;
}

$lead = array(
	'first'     => dl_cap( dl_field( 'input_1_3', 'input_1.3' ), 60 ),
	'last'      => dl_cap( dl_field( 'input_1_6', 'input_1.6' ), 60 ),
	'phone'     => dl_cap( dl_field( 'input_5' ), 30 ),
	'dob'       => dl_cap( dl_field( 'input_25' ), 20 ),
	'insurance' => dl_cap( dl_field( 'input_17' ), 80 ),
	'policy'    => dl_cap( dl_field( 'input_18' ), 60 ),
	'consent'   => dl_field( 'input_26_1', 'input_26.1' ),
	'page'      => $slug,
);

/*
 * Attribution. Gravity carried these in hidden fields populated by a script
 * that wrote to element IDs; here they come straight off the query string the
 * ad appended, which does not break when a field is reordered.
 */
/*
 * Attribution.
 *
 * Three sources, in priority order:
 *   1. The form's own posted value.
 *   2. The hidden input_19..input_24 fields the mirrored Gravity form still
 *      carries, for anything already populated client-side.
 *   3. Google/Microsoft ValueTrack parameters under their native spellings
 *      (campaignid, adgroupid, creative) mapped to the names CTM expects.
 */
$gravity_hidden = array(
	'gclid'        => 'input_19',
	'utm_source'   => 'input_20',
	'utm_medium'   => 'input_21',
	'utm_campaign' => 'input_22',
	'utm_content'  => 'input_23',
	'utm_term'     => 'input_24',
);

// our name => the query-string spellings to look for, in order
$sources = array(
	'gclid'        => array( 'gclid' ),
	'msclkid'      => array( 'msclkid' ),
	'utm_source'   => array( 'utm_source' ),
	'utm_medium'   => array( 'utm_medium' ),
	'utm_campaign' => array( 'utm_campaign' ),
	'utm_content'  => array( 'utm_content' ),
	'utm_term'     => array( 'utm_term' ),
	'campaign_id'  => array( 'campaign_id', 'campaignid' ),
	'adgroup_id'   => array( 'adgroup_id', 'adgroupid' ),
	'creative_id'  => array( 'creative_id', 'creative' ),
);

$attribution = array();
foreach ( $sources as $name => $spellings ) {
	$value = dl_field( ...$spellings );

	if ( '' === $value && isset( $gravity_hidden[ $name ] ) ) {
		$value = dl_field( $gravity_hidden[ $name ] );
	}

	if ( '' !== $value ) {
		$attribution[ $name ] = dl_cap( $value, 300 );
	}
}

// Phone is the only field admissions genuinely cannot work without.
if ( '' === dl_normalize_phone( $lead['phone'] ) ) {
	header( 'Location: ' . dl_return_to( $slug, false ), true, 303 );
	exit;
}

$result = dl_deliver_lead( $lead, $attribution );
dl_log_lead( $lead, $result );

/*
 * The visitor is told it worked even when CTM rejected it, because the lead
 * is already captured in the log and someone will call them either way.
 * Making them re-type it would lose more enquiries than it saves. The real
 * outcome is in the log and is the thing to alert on.
 */
header( 'Location: ' . dl_return_to( $slug, true ), true, 303 );
exit;
