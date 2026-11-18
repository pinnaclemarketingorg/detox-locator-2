<?php
/**
 * Dev-only router for `php -S`, mirroring .htaccess so every URL behaves
 * locally the way it will on Cloudways. Denied by .htaccess; never used live.
 *
 *   php -S 127.0.0.1:8000 router.dev.php
 */

$path = (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

if ( preg_match( '#^/(app|templates|partials)/#', $path ) ) {
	http_response_code( 404 );
	return true;
}

$file = __DIR__ . $path;
if ( '/' !== $path && is_file( $file ) ) {
	return false; // let the built-in server serve real files
}

if ( '/' !== $path && ! str_ends_with( $path, '/' ) && ! preg_match( '/\.[a-z0-9]{2,5}$/i', $path ) ) {
	$qs = isset( $_SERVER['QUERY_STRING'] ) && '' !== $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
	header( 'Location: ' . $path . '/' . $qs, true, 301 );
	return true;
}

require __DIR__ . '/index.php';
return true;
