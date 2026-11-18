<?php
/**
 * Shared bootstrap: config, escaping, sanitisers, a tiny cache.
 *
 * Config is loaded from private_html, which sits beside public_html on
 * Cloudways and is never served by the web server. That is where the
 * CallTrackingMetrics FormReactor key and the IPInfo token live, so neither is
 * in the repository and neither can be fetched over HTTP.
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
 *   A. This app is deployed straight into public_html:
 *        applications/<app>/private_html/config.php
 *        applications/<app>/public_html/               <- DL_ROOT
 *
 *   B. The whole repo is deployed, webroot pointed at a subdirectory:
 *        applications/<app>/private_html/config.php
 *        applications/<app>/public_html/               <- git working tree
 *        applications/<app>/public_html/php-app/       <- DL_ROOT
 *
 * In layout B, public_html is itself a git working tree, so nothing untracked
 * may live there: a redeploy that resets or cleans the tree would delete it.
 */
function dl_config(): array {
	static $config = null;
	if ( null !== $config ) {
		return $config;
	}

	$candidates = array(
		dirname( dirname( DL_ROOT ) ) . '/private_html/config.php', // layout B
		dirname( DL_ROOT ) . '/private_html/config.php',            // layout A
		DL_ROOT . '/config.local.php',                              // local dev
	);

	$loaded = array();
	foreach ( $candidates as $path ) {
		if ( is_readable( $path ) ) {
			$file = require $path;
			if ( is_array( $file ) ) {
				$loaded = $file;
			}
			break;
		}
	}

	// Cache the merged result, not the raw file: caching the pre-merge value
	// means every call after the first sees no defaults at all.
	$config = $loaded + array(
		'phone'           => '(888) 367-8846',
		'phone_home'      => '(888) 891-2240',
		'phone_bing'      => '(888) 701-3206',
		'phone_lpc'       => '(866) 913-7063',
		'ctm_account'     => '374183',
		'ctm_formreactor' => '',
		'ctm_key'         => '',
		'gtm_id'          => 'GTM-T6QJLCFS',
		'clarity_id'      => 'q0ni5lkall',
		'ipinfo_token'    => '',
		'lead_log'        => '',
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

/** tel: href for a display number. */
function dl_tel( string $phone ): string {
	return 'tel:+1' . preg_replace( '/\D/', '', $phone );
}

/**
 * Inline a stylesheet rather than linking it.
 *
 * These are ad destinations: a visit is usually one pageview, so a cached
 * external stylesheet never gets a second page to pay off on, and inlining
 * removes a render-blocking round trip from the critical path.
 *
 * Comments and whitespace runs go. Nothing cleverer — a smarter minifier is
 * how you find out calc(100% - 2rem) needed its spaces.
 */
function dl_inline_css( string ...$files ): string {
	$css = '';
	foreach ( $files as $file ) {
		$path = DL_ROOT . '/assets/css/' . $file;
		$css .= is_readable( $path ) ? (string) file_get_contents( $path ) : '';
	}
	$css = (string) preg_replace( '#/\*.*?\*/#s', '', $css );
	$css = (string) preg_replace( '/\s+/', ' ', $css );
	return trim( $css );
}

/** Asset URL with a cache-busting version from the file's mtime. */
function dl_asset( string $path ): string {
	$file = DL_ROOT . '/assets/' . $path;
	$v    = is_file( $file ) ? (string) filemtime( $file ) : '1';
	return '/assets/' . $path . '?v=' . $v;
}

/**
 * Sanitise a user-supplied place name before it is printed on the page.
 *
 * Escaping happens at output; this is about plausibility. `?area=` is
 * attacker-controlled and lands in the hero of a paid landing page, so without
 * a whitelist anyone can hand out a link that makes the page claim whatever
 * they like — a brand problem and an Ads policy problem both.
 */
function dl_clean_place( ?string $raw ): ?string {
	if ( null === $raw || '' === $raw ) {
		return null;
	}

	$clean = trim( (string) preg_replace( '/\s+/', ' ', $raw ) );

	// Reject rather than strip: stripping turns "<script>" into a word that
	// then appears in the hero. A real place name never needs anything else.
	if ( preg_match( '/[^A-Za-z .,\'\-]/', $clean ) || strlen( $clean ) < 2 || strlen( $clean ) > 40 ) {
		return null;
	}

	// ucwords rather than mb_convert_case: the regex has already reduced this
	// to ASCII, so there is nothing multibyte left and no need for mbstring.
	// The delimiter list is what gets O'Brien and Winston-Salem right.
	$place = ucwords( strtolower( $clean ), " -'" );

	// "Austin, tx" -> "Austin, TX": a trailing two-letter state code.
	return (string) preg_replace_callback( '/, ([A-Za-z]{2})$/', static fn( $m ) => ', ' . strtoupper( $m[1] ), $place );
}

/**
 * The visitor's real IPv4 address.
 *
 * Only headers this stack actually sets are trusted. Reading a bare
 * X-Forwarded-For would let a client spoof its own location.
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
 * File-backed cache, so a burst of ad clicks costs one upstream call rather
 * than hundreds. No Redis dependency, so it keeps working if the app moves.
 */
function dl_cache_get( string $key ) {
	$path = sys_get_temp_dir() . '/dl_' . md5( $key ) . '.cache';
	if ( ! is_readable( $path ) ) {
		return null;
	}
	$raw = json_decode( (string) file_get_contents( $path ), true );
	if ( ! is_array( $raw ) || ! isset( $raw['expires'] ) || ! array_key_exists( 'value', $raw ) ) {
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
	@file_put_contents( $path, (string) json_encode( array( 'expires' => time() + $ttl, 'value' => $value ) ), LOCK_EX );
}

/** Truncate without cutting a multibyte character in half. */
function dl_truncate( string $value, int $max ): string {
	return function_exists( 'mb_substr' ) ? mb_substr( $value, 0, $max ) : substr( $value, 0, $max );
}

/** JSON response helper for the API endpoints. */
function dl_json( array $payload, int $status = 200 ): void {
	http_response_code( $status );
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Cache-Control: private, no-store' );
	echo json_encode( $payload );
	exit;
}

/**
 * Is this the Cloudways staging hostname rather than the live domain?
 *
 * Staging serves identical pages, so an indexed staging host is a complete
 * duplicate of the site competing with the real domain for its own content.
 */
function dl_is_staging_host(): bool {
	$host = strtolower( (string) ( $_SERVER['HTTP_HOST'] ?? '' ) );
	return '' === $host || str_contains( $host, 'cloudwaysapps.com' );
}

/**
 * Absolute URL on the current host.
 *
 * Always https outside local development: Cloudways terminates TLS in Nginx,
 * so $_SERVER['HTTPS'] is not a reliable signal here, and a canonical that
 * flips to http on some requests is worse than none.
 */
function dl_url( string $path ): string {
	$host   = strtolower( (string) ( $_SERVER['HTTP_HOST'] ?? 'detox-locator.com' ) );
	$local  = (bool) preg_match( '/^(localhost|127\.0\.0\.1)(:\d+)?$/', $host );
	return ( $local ? 'http' : 'https' ) . '://' . $host . $path;
}
