<?php
/**
 * Front controller. Every page URL lands here; app/routes.php decides which
 * template draws it, and templates/ holds one file per page family.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/routes.php';
require_once __DIR__ . '/app/icons.php';
require_once __DIR__ . '/app/content.php';

$page = dl_resolve_page();

if ( ! $page['found'] ) {
	http_response_code( 404 );
}

// Without JS the form posts natively and comes back with ?submitted=1.
$submitted = isset( $_GET['submitted'] ) && '1' === $_GET['submitted'];

require __DIR__ . '/templates/' . $page['template'] . '.php';
