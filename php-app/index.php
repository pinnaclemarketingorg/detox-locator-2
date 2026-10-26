<?php
/**
 * Front controller.
 *
 * Every page request lands here. The route table in app/page.php decides
 * which template in pages/ renders and which tracked number it carries.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

define( 'DL_APP', true );

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/app/page.php';

$dl_path = (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
$dl_route = dl_resolve( $dl_path );

if ( null === $dl_route ) {
	http_response_code( 404 );
	header( 'Content-Type: text/html; charset=utf-8' );
	echo '<!doctype html><meta charset="utf-8"><title>Not found</title>'
		. '<p style="font:16px/1.6 system-ui;padding:40px">Not found. '
		. '<a href="/">Go to the homepage</a>.</p>';
	exit;
}

$GLOBALS['dl_page'] = $dl_route;

$dl_template = __DIR__ . '/pages/' . $dl_route['slug'] . '.php';
if ( ! is_readable( $dl_template ) ) {
	http_response_code( 500 );
	exit( 'Template missing.' );
}

/*
 * The landing pages are ad destinations and must not be indexed — they would
 * compete with the site's own organic pages for nothing. The homepage is the
 * exception and stays indexable.
 *
 * Sent as a header rather than a meta tag so the mirrored markup did not need
 * editing, and so it applies even if a page is fetched as a subresource.
 */
if ( ! in_array( $dl_route['slug'], dl_indexable(), true ) ) {
	header( 'X-Robots-Tag: noindex, nofollow', true );
}

header( 'Content-Type: text/html; charset=UTF-8' );

require $dl_template;
