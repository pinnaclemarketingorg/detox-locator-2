<?php
/**
 * Landing page footer — the live one: logo, accreditation, the two policy
 * links and the help line. No menu and no licensing block; those belong to
 * the site's own pages, not to ad destinations.
 *
 * Expects $page and $footer_class. Bing variants add the LegitScript seal,
 * as the live /lpa-bing/ does.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;
?>
<footer class="<?= e( $footer_class ) ?>">
  <div class="wrap lp-foot-row">
    <img class="lp-foot-logo" src="/assets/img/logo/<?= str_contains( $footer_class, 'on-dark' ) ? 'logo-white' : 'logo' ?>.webp" width="180" height="77" alt="Detox Locator" loading="lazy">
    <div class="lp-foot-seals">
      <img src="/assets/img/accreditation/joint-commission.webp" width="64" height="64" alt="The Joint Commission National Quality Approval" loading="lazy">
      <a href="https://www.dhcs.ca.gov/" target="_blank" rel="noopener"><img src="/assets/img/accreditation/dhcs-logo.svg" width="168" height="44" alt="California Department of Health Care Services" loading="lazy"></a>
      <?php if ( $page['bing'] ) : ?>
      <?= dl_legitscript_seal() ?>
      <?php endif; ?>
    </div>
    <div class="lp-foot-links">
      <p><a href="/privacy-policy/">Privacy Policy</a><a href="/hipaa-policy/">HIPAA Policy</a></p>
      <p><a class="lp-foot-phone" href="<?= e( $page['tel'] ) ?>" data-cta="call-footer">24/7 Confidential Help: <?= e( $page['phone'] ) ?></a></p>
    </div>
  </div>
  <p class="lp-foot-legal">© <?= date( 'Y' ) ?> Detox Locator. All Rights Reserved.</p>
</footer>
