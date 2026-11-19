<?php
/**
 * Inline SVG icons. The live pages load the whole of Font Awesome to draw
 * about a dozen glyphs; these are the dozen.
 *
 * Every icon is decorative (aria-hidden) — anything that needs a name gets it
 * from visible text or an aria-label on its control.
 *
 * @package dl-locator
 */

declare( strict_types = 1 );

defined( 'DL_ROOT' ) || exit;

const DL_ICONS = array(
	'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
	'check'    => '<path d="M20 6 9 17l-5-5"/>',
	'check-o'  => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.2 2.4 2.4 4.6-4.9"/>',
	'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
	'lock'     => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
	'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	'down'     => '<path d="M12 5v14M6 13l6 6 6-6"/>',
	'prev'     => '<path d="m15 18-6-6 6-6"/>',
	'next'     => '<path d="m9 18 6-6-6-6"/>',
	'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
	'close'    => '<path d="M6 6l12 12M18 6 6 18"/>',
	'pin'      => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
	'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
	'external' => '<path d="M7 17 17 7M10 7h7v7"/>',
	'spark'    => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M6 18l2.5-2.5M15.5 8.5 18 6"/>',
	// Care types.
	'detox'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v8M8 12h8"/>',
	'home'     => '<path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9.5z"/><path d="M9.5 21v-6h5v6"/>',
	'path'     => '<circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><path d="M8 18h5a3 3 0 0 0 0-6h-2a3 3 0 0 1 0-6h5"/>',
	'chat'     => '<path d="M8 13h8M8 9h5"/><path d="M4 5h16v11H8l-4 4V5z"/>',
	'leaf'     => '<path d="M5 19c0-8 5-14 15-14 0 10-6 15-14 15"/><path d="M5 19 13 11"/>',
	'brain'    => '<path d="M9.5 3A2.5 2.5 0 0 0 7 5.5 2.5 2.5 0 0 0 4.5 8c0 .9.5 1.7 1.2 2.1A2.6 2.6 0 0 0 4.5 12c0 1 .6 1.9 1.4 2.3-.3.4-.4.9-.4 1.4A2.8 2.8 0 0 0 8.3 18.5c.2 1.4 1.4 2.5 2.9 2.5.4 0 .8-.5.8-1V4c0-.6-.5-1-1.1-1z"/><path d="M14.5 3A2.5 2.5 0 0 1 17 5.5 2.5 2.5 0 0 1 19.5 8c0 .9-.5 1.7-1.2 2.1.7.4 1.2 1.1 1.2 1.9 0 1-.6 1.9-1.4 2.3.3.4.4.9.4 1.4a2.8 2.8 0 0 1-2.8 2.8c-.2 1.4-1.4 2.5-2.9 2.5-.4 0-.8-.5-.8-1V4c0-.6.5-1 1.1-1z"/>',
	'compass'  => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5 5-2z"/>',
	'apple'    => '<path d="M12 7c-1.5-1-5-1.5-6.5 1.5S5 16 7.5 19c1.3 1.6 3 1.6 4.5.8 1.5.8 3.2.8 4.5-.8 2.5-3 3.5-7.5 2-10.5S13.5 6 12 7z"/><path d="M12 7c0-2 1-3.5 3-4"/>',
	'family'   => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 5.2a3.5 3.5 0 0 1 0 5.6M17.5 14.2A6.5 6.5 0 0 1 21.5 20"/>',
	'heart'    => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/>',
);

/** Stroke icon. */
function dl_icon( string $name, string $class = 'ic' ): string {
	$body = DL_ICONS[ $name ] ?? '';
	return '<svg class="' . e( $class ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $body . '</svg>';
}

/** Five filled stars with one accessible name. */
function dl_stars( string $class = 'stars' ): string {
	$star = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1L3.2 9.4l6.1-.9L12 3z"/></svg>';
	return '<span class="' . e( $class ) . '" role="img" aria-label="Rated 5 out of 5">' . str_repeat( $star, 5 ) . '</span>';
}

/** Google's G, for "Based on Google reviews". */
function dl_google_g( int $size = 18 ): string {
	return '<svg viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '" aria-hidden="true" focusable="false"><path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5a5.6 5.6 0 0 1-2.4 3.7v3h3.9c2.3-2.1 3.5-5.2 3.5-8.9z"/><path fill="#34A853" d="M12 24c3.2 0 5.9-1.1 7.9-2.9l-3.9-3c-1.1.7-2.4 1.2-4 1.2-3.1 0-5.7-2.1-6.6-4.9H1.4v3.1A12 12 0 0 0 12 24z"/><path fill="#FBBC04" d="M5.4 14.4a7.2 7.2 0 0 1 0-4.6V6.7H1.4a12 12 0 0 0 0 10.8l4-3.1z"/><path fill="#EA4335" d="M12 4.7c1.8 0 3.3.6 4.6 1.8l3.4-3.4A12 12 0 0 0 1.4 6.7l4 3.1C6.3 6.9 8.9 4.7 12 4.7z"/></svg>';
}

/**
 * The LegitScript seal, served by LegitScript with its verification link, as
 * their snippet has it. Blockers often stop static.legitscript.com; a local
 * copy then takes its place so the badge never shows as a broken image.
 */
function dl_legitscript_seal(): string {
	return '<a class="legitscript" href="https://www.legitscript.com/websites/?checker_keywords=detox-locator.com" target="_blank" rel="noopener" title="Verify LegitScript Approval for detox-locator.com">'
		. '<img src="https://static.legitscript.com/seals/43156119.png" width="73" height="79" alt="Verify Approval for detox-locator.com" loading="lazy" onerror="this.onerror=null;this.src=\'/assets/img/accreditation/legitscript.webp\'">'
		. '</a>';
}
