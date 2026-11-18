<?php
/**
 * Microsite header: logo, the three-item menu, the number. Used by the
 * homepage and the site's own pages — never by a landing page, which keeps
 * its visitors on the one thing it asks them to do.
 *
 * Expects $page.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$menu = array(
	'programs' => 'Programs',
	'about'    => 'About',
	'contact'  => 'Contact',
);
?>
<header class="site-top">
  <div class="wrap site-top-row">
    <a class="site-logo" href="/" aria-label="Detox Locator home">
      <img src="/assets/img/logo/logo.webp" width="480" height="204" alt="Detox Locator">
    </a>

    <nav class="site-nav" id="site-nav" aria-label="Main">
      <ul>
        <?php foreach ( $menu as $slug => $label ) : ?>
        <li><a href="/<?= e( $slug ) ?>/"<?= $page['slug'] === $slug ? ' aria-current="page"' : '' ?>><?= e( $label ) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a class="site-nav-call" href="<?= e( $page['tel'] ) ?>" data-cta="call-menu"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
    </nav>

    <div class="site-top-actions">
      <a class="site-phone" href="<?= e( $page['tel'] ) ?>" data-cta="call-header"><?= e( $page['phone'] ) ?></a>
      <a class="btn btn-primary site-call" href="<?= e( $page['tel'] ) ?>" data-cta="call-header-btn"><?= dl_icon( 'phone' ) ?><span>Call now</span></a>
      <button class="site-toggle" type="button" data-nav-toggle aria-controls="site-nav" aria-expanded="false" aria-label="Open menu">
        <?= dl_icon( 'menu', 'ic ic-open' ) ?><?= dl_icon( 'close', 'ic ic-close' ) ?>
      </button>
    </div>
  </div>
</header>
