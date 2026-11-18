<?php
/**
 * Closing call-to-action band for the microsite's inner pages.
 *
 * Expects $page.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;
?>
<section class="section call-band on-dark site-cta">
  <div class="wrap site-cta-inner">
    <h2 class="h-section">Take the first step today</h2>
    <p class="lede">Admissions is open 24/7. Talking to us is free, confidential and commits you to nothing.</p>
    <div class="hero-ctas">
      <a class="btn btn-light btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-cta"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-ghost-light btn-lg" href="/contact/#verify" data-cta="verify-cta">Verify insurance</a>
    </div>
  </div>
</section>
