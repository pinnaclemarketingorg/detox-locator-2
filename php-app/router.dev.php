<?php
/**
 * Router for PHP's built-in server. Local preview only.
 *
 *   cd php-app
 *   php -S localhost:8000 router.dev.php
 *   open http://localhost:8000/lpa-2/
 *
 * The built-in server has no .htaccess, so this reproduces the two rules that
 * matter: serve a real file if one exists, otherwise hand the request to
 * index.php. Denied over HTTP in production by .htaccess.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

$path = (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
$file = __DIR__ . $path;

// Never let the dev server hand out the application's innards.
if ( preg_match( '#^/(app|pages|tools)/#', $path )
	|| preg_match( '#/(config\.(sample|local)\.php|router\.dev\.php)$#', $path ) ) {
	http_response_code( 404 );
	echo 'Not found.';
	return true;
}

// /api/lead.php is a real file and is served directly.
if ( '' !== $path && '/' !== $path && is_file( $file ) ) {
	return false; // let the built-in server serve it
}

require __DIR__ . '/index.php';
return true;
