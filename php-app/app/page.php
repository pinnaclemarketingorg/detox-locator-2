<?php
/**
 * Route table.
 *
 * Which URL is which page, and which tracked number it carries. This is the
 * file to edit when a new landing variant is added — add a row, drop a
 * template in pages/, done.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

defined( 'DL_APP' ) || exit;

/**
 * URL path => page definition.
 *
 * phone_key indexes into config.php. Separate keys per destination is the
 * whole point: one number shared across two ad platforms gives you one
 * merged, useless attribution report.
 *
 * @return array<string,array{slug:string,phone_key:string}>
 */
function dl_routes(): array {
	return array(
		'/' => array(
			'slug'      => 'home',
			'phone_key' => 'phone_home',
		),
		'/lpa-2/' => array(
			'slug'      => 'lpa-2',
			'phone_key' => 'phone',
		),
		'/lpa-2-dkwi/' => array(
			'slug'      => 'lpa-2-dkwi',
			'phone_key' => 'phone',
		),
		'/lpa-2-dli/' => array(
			'slug'      => 'lpa-2-dli',
			'phone_key' => 'phone',
		),
		'/lpa-bing/' => array(
			'slug'      => 'lpa-bing',
			'phone_key' => 'phone_bing',
		),

		// Policy pages. Wording is untouched from the live site — the only
		// change is the shared header/footer palette, which now matches the
		// landing pages.
		'/privacy-policy/' => array(
			'slug'      => 'privacy-policy',
			'phone_key' => 'phone_home',
		),
		'/hipaa-policy/' => array(
			'slug'      => 'hipaa-policy',
			'phone_key' => 'phone_home',
		),
	);
}

/** Pages that should stay in the search index. */
function dl_indexable(): array {
	return array( 'home', 'privacy-policy', 'hipaa-policy' );
}

/**
 * Resolve a request path to a page, or null for a 404.
 *
 * Trailing slash is normalised so /lpa-2 and /lpa-2/ both land, which matters
 * because an ad's final URL is not always written with the slash.
 *
 * @param string $path Request path, without query string.
 * @return array{slug:string,phone_key:string}|null
 */
function dl_resolve( string $path ): ?array {
	$routes = dl_routes();

	if ( isset( $routes[ $path ] ) ) {
		return $routes[ $path ];
	}

	$normalised = '/' . trim( $path, '/' ) . '/';
	if ( '//' === $normalised ) {
		$normalised = '/';
	}

	return $routes[ $normalised ] ?? null;
}
