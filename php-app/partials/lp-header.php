<?php
/**
 * Landing page header: logo, call, verify. Fixed to the top, clear over the
 * hero and solid once the page scrolls (app.js §7), like the live navbar,
 * with the call button the live one lacked.
 *
 * The number is always this page's own ($page['phone']), so /lpa-bing/ shows
 * the Bing number and nothing here can borrow another page's. No links off
 * the page: an ad destination keeps the visitor on it.
 *
 * Expects $page.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;
?>
<header class="lp-top" data-lp-top>
  <div class="lp-top-row">
    <img class="lp-top-logo" src="/assets/img/logo/logo.webp" width="480" height="204" alt="Detox Locator">
    <div class="lp-top-actions">
      <a class="btn btn-outline lp-top-call" href="<?= e( $page['tel'] ) ?>" data-cta="call-header"><?= dl_icon( 'phone' ) ?><span>Call <?= e( $page['phone'] ) ?></span></a>
      <a class="btn btn-primary lp-top-verify" href="#insurance" data-cta="verify-header">Verify Insurance Now</a>
    </div>
  </div>
</header>
