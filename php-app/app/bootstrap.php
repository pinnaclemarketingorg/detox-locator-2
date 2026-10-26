<?php
/**
 * Shared bootstrap for the Detox Locator pages.
 *
 * Loads configuration from private_html, which sits one level above the
 * webroot on Cloudways and is never served by the web server. That is where
 * the CallTrackingMetrics FormReactor key and the IPInfo token live, so
 * neither is in the repository and neither can be fetched over HTTP.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

define( 'DL_ROOT', dirname( __DIR__ ) );

/**
 * Load config from private_html, falling back to a local file for development.
 *
 * Two Cloudways layouts are supported, because they put public_html in
 * different places relative to this file:
 *
 *   A. This app is the whole repo, deployed straight into public_html:
 *        applications/<app>/private_html/config.php   <- secrets, not in git
 *        applications/<app>/public_html/              <- this repo
 *
 *   B. Monorepo, with the webroot pointed at a subdirectory:
 *        applications/<app>/private_html/config.php   <- secrets, not in git
 *        applications/<app>/public_html/              <- this repo (a git tree)
 *        applications/<app>/public_html/php-app/      <- the webroot
 *
 * In layout B, public_html is itself a git working tree, so nothing untracked
 * may live there: a redeploy that resets or cleans the tree would delete it.
 * That is why private_html is resolved as a sibling of public_html first.
 */
function dl_config(): array {
	static $config = null;
	if ( null !== $config ) {
		return $config;
	}

	$candidates = array(
		// Layout B: DL_ROOT is public_html/php-app, so private_html is two up.
		dirname( dirname( DL_ROOT ) ) . '/private_html/config.php',
		// Layout A: DL_ROOT is public_html, so private_html is one up.
		dirname( DL_ROOT ) . '/private_html/config.php',
		DL_ROOT . '/config.local.php',
	);

	$loaded_config = array();
	foreach ( $candidates as $path ) {
		if ( is_readable( $path ) ) {
			$loaded = require $path;
			if ( is_array( $loaded ) ) {
				$loaded_config = $loaded;
			}
			break;
		}
	}

	// Assign the *merged* result to the static, not the raw file contents.
	// Caching the pre-merge value means every call after the first returns a
	// config with no defaults in it at all.
	$config = $loaded_config + array(
		'ctm_account'     => '',
		'ctm_formreactor' => '',
		'ctm_key'         => '',
		'ipinfo_token'    => '',
		// The numbers currently on the live pages.
		'phone'           => '(888) 367-8846',
		'phone_home'      => '(888) 891-2240',
		'phone_bing'      => '(888) 701-3206',
		'lead_log'        => '',
		'lead_email'      => '',
	);

	return $config;
}

function dl_cfg( string $key, string $default = '' ): string {
	$config = dl_config();
	return isset( $config[ $key ] ) && '' !== $config[ $key ] ? (string) $config[ $key ] : $default;
}

/** Escape for HTML output. */
function e( ?string $value ): string {
	return htmlspecialchars( (string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
}

/* -------------------------------------------------------------------------
 * Per-request page state
 *
 * index.php sets these once from the route table; the page templates read
 * them. Keeping them in globals rather than passing them down means the
 * mirrored markup did not have to be restructured to accept parameters.
 * ---------------------------------------------------------------------- */

$GLOBALS['dl_page'] = array(
	'slug'      => 'lpa-2',
	'phone_key' => 'phone',
);

/** The current page's slug, e.g. "lpa-bing". */
function dl_slug(): string {
	return (string) $GLOBALS['dl_page']['slug'];
}

/**
 * The current page's tracked number, formatted for display.
 *
 * Each ad destination carries its own number on the live site — that is why
 * /lpa-bing/ differs from /lpa-2/. One number across two ad platforms is one
 * merged, useless attribution report.
 */
function dl_phone(): string {
	return dl_cfg( (string) $GLOBALS['dl_page']['phone_key'], '(888) 367-8846' );
}

/** The same number as a tel: URI, including the scheme. */
function dl_phone_href(): string {
	$digits = preg_replace( '/\D/', '', dl_phone() );
	if ( 10 === strlen( (string) $digits ) ) {
		$digits = '1' . $digits;
	}
	return 'tel:+' . $digits;
}

/** True when the visitor has just submitted the form successfully. */
function dl_submitted(): bool {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only flag.
	return isset( $_GET['submitted'] ) && '1' === $_GET['submitted'];
}

/**
 * Sanitise a user-supplied place name before it is printed on the page.
 *
 * Escaping happens at output; this is about plausibility. `?area=` is
 * attacker-controlled and lands in the hero on a paid landing page, so
 * without a constraint anyone can hand out a link that makes the page claim
 * whatever they like — a brand problem and an Ads policy problem both.
 */
function dl_clean_place( ?string $raw ): ?string {
	if ( null === $raw || '' === $raw ) {
		return null;
	}

	$clean = preg_replace( '/[^A-Za-z .\'\-]/', '', $raw );
	$clean = trim( (string) preg_replace( '/\s+/', ' ', (string) $clean ) );

	if ( strlen( $clean ) < 2 || strlen( $clean ) > 40 ) {
		return null;
	}

	// ucwords rather than mb_convert_case: the regex above has already reduced
	// this to ASCII, so there is nothing multibyte left to handle and no reason
	// to require mbstring. The delimiter list gets O'Fallon and Winston-Salem
	// right.
	return ucwords( strtolower( $clean ), " -'" );
}

/**
 * The visitor's real IPv4 address.
 *
 * Only proxy headers this stack actually sets are trusted, in the order the
 * request travels. Reading a bare X-Forwarded-For would let a client spoof
 * its own location.
 */
function dl_visitor_ip(): ?string {
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_TRUE_CLIENT_IP', 'REMOTE_ADDR' ) as $header ) {
		if ( empty( $_SERVER[ $header ] ) ) {
			continue;
		}
		$ip = trim( explode( ',', (string) $_SERVER[ $header ] )[0] );
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
			return $ip;
		}
	}
	return null;
}

/**
 * Tiny file-backed cache, so a burst of ad clicks costs one upstream call
 * rather than hundreds. No Redis dependency: the server has it, but this has
 * to keep working if the app is moved somewhere that does not.
 */
function dl_cache_get( string $key ) {
	$path = sys_get_temp_dir() . '/dl_' . md5( $key ) . '.cache';
	if ( ! is_readable( $path ) ) {
		return null;
	}

	$raw = json_decode( (string) file_get_contents( $path ), true );
	if ( ! is_array( $raw ) || ! isset( $raw['expires'], $raw['value'] ) ) {
		return null;
	}
	if ( $raw['expires'] < time() ) {
		@unlink( $path );
		return null;
	}

	return $raw['value'];
}

function dl_cache_set( string $key, $value, int $ttl ): void {
	$path = sys_get_temp_dir() . '/dl_' . md5( $key ) . '.cache';
	@file_put_contents(
		$path,
		(string) json_encode( array( 'expires' => time() + $ttl, 'value' => $value ) ),
		LOCK_EX
	);
}

/** JSON response helper for the API endpoints. */
function dl_json( array $payload, int $status = 200 ): void {
	http_response_code( $status );
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Cache-Control: private, no-store' );
	echo json_encode( $payload );
	exit;
}
