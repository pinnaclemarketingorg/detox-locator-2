<?php
/**
 * Route table. Which URL is which page, and what differs between them.
 *
 * The landing variants differ in three ways only: which template draws them,
 * how the line under the headline is filled, and which tracked number they
 * show. Everything else is shared, so a copy change lands on every variant of
 * a family at once.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

defined( 'DL_ROOT' ) || exit;

/*
 * template  file in templates/ that draws the page
 * phone     config key holding the page's tracked number
 * mode      static: fixed line under the H1
 *           area:   "Find Treatment in <area> in Minutes" from ?area=, rendered
 *                   server-side so there is no flash (dynamic keyword insertion)
 *           geo:    the same line from the visitor's state, swapped in after
 *                   paint by a browser call to ipinfo.io, as the live page
 *                   did (dynamic location insertion)
 * bing      Bing copy: no "nationwide", no "Detox Locator's partner programs",
 *           and the LegitScript seal in the footer — as on the live /lpa-bing/
 * index     whether search engines should index it
 */
const DL_ROUTES = array(
	// Microsite — the site's own pages, with the menu and licensing footer.
	''               => array( 'template' => 'home',     'phone' => 'phone', 'index' => true ),
	'programs'       => array( 'template' => 'programs', 'phone' => 'phone', 'index' => true ),
	'about'          => array( 'template' => 'about',    'phone' => 'phone', 'index' => true ),
	'contact'        => array( 'template' => 'contact',  'phone' => 'phone', 'index' => true ),
	'privacy-policy' => array( 'template' => 'policy',   'phone' => 'phone', 'index' => true ),
	'hipaa-policy'   => array( 'template' => 'policy',   'phone' => 'phone', 'index' => true ),

	// Landing pages, A family — the live design, rebuilt, with a better form.
	'lpa-2'          => array( 'template' => 'lpa', 'phone' => 'phone',      'mode' => 'static' ),
	'lpa-2-dkwi'     => array( 'template' => 'lpa', 'phone' => 'phone',      'mode' => 'area' ),
	'lpa-2-dli'      => array( 'template' => 'lpa', 'phone' => 'phone',      'mode' => 'geo' ),
	'lpa-bing'       => array( 'template' => 'lpa', 'phone' => 'phone_bing', 'mode' => 'static', 'bing' => true ),

	// Invigorate co-brand.
	'lpc'            => array( 'template' => 'lpc', 'phone' => 'phone' ),

	// Landing pages, D family — same brief copy as A, modern layout.
	'lpd'            => array( 'template' => 'lpd', 'phone' => 'phone',      'mode' => 'static' ),
	'lpd-dkwi'       => array( 'template' => 'lpd', 'phone' => 'phone',      'mode' => 'area' ),
	'lpd-dli'        => array( 'template' => 'lpd', 'phone' => 'phone',      'mode' => 'geo' ),
	'lpd-bing'       => array( 'template' => 'lpd', 'phone' => 'phone_bing', 'mode' => 'static', 'bing' => true ),
);

/** The request path as a route key: lowercase, no slashes. */
function dl_request_slug(): string {
	$path = (string) parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
	return strtolower( trim( rawurldecode( $path ), '/' ) );
}

/**
 * Resolve the current request to a page.
 *
 * @return array{slug:string,template:string,found:bool,phone:string,tel:string,
 *               mode:string,bing:bool,tagline:string,place:?string,geo:bool,
 *               index:bool,canonical:string}
 */
function dl_resolve_page(): array {
	$slug  = dl_request_slug();
	$found = isset( DL_ROUTES[ $slug ] );
	$route = $found ? DL_ROUTES[ $slug ] : array( 'template' => '404', 'phone' => 'phone', 'index' => false );

	$phone = dl_cfg( $route['phone'], dl_cfg( 'phone' ) );
	$mode  = $route['mode'] ?? 'static';
	$bing  = ! empty( $route['bing'] );

	$page = array(
		'slug'      => $slug,
		'template'  => $route['template'],
		'found'     => $found,
		'phone'     => $phone,
		'tel'       => dl_tel( $phone ),
		'mode'      => $mode,
		'bing'      => $bing,
		'tagline'   => $bing ? 'Find Treatment in Minutes' : 'Find Treatment Nationwide in Minutes',
		'place'     => null,
		'geo'       => 'geo' === $mode,
		// Ad destinations stay out of the index: they are near-duplicates of
		// one another, and indexing the Bing variant would leak its number
		// into Google. Ad platforms fetch the URL directly either way.
		'index'     => ! empty( $route['index'] ) && ! dl_is_staging_host(),
		'canonical' => dl_url( '' === $slug ? '/' : '/' . $slug . '/' ),
	);

	if ( 'area' === $mode ) {
		$page['place'] = dl_clean_place( isset( $_GET['area'] ) ? (string) $_GET['area'] : null );
		if ( null !== $page['place'] ) {
			$page['tagline'] = 'Find Treatment in ' . $page['place'] . ' in Minutes';
		}
	}

	return $page;
}
