<?php
/**
 * Microsite footer — locations, licensing and accreditation. Home and the
 * site's own pages only; the landing pages use partials/lp-footer.php.
 *
 * Licence #191329BP, expiring 4/30/2027 (the live site still shows the old
 * #191329AP and 10/31/2026). Both live in DL_LICENSE below so the footer and
 * the About page cannot disagree.
 *
 * Expects $page.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;
?>
<footer class="site-foot on-dark">
  <div class="wrap site-foot-grid">
    <div class="site-foot-brand">
      <img class="site-foot-logo" src="/assets/img/logo/logo-white.webp" width="480" height="204" alt="Detox Locator" loading="lazy">
      <a class="btn btn-light" href="<?= e( $page['tel'] ) ?>" data-cta="call-footer"><?= dl_icon( 'phone' ) ?>Call: <?= e( $page['phone'] ) ?></a>
      <address class="site-foot-addr">
        <?php foreach ( DL_ADDRESSES as $a ) : ?>
        <p><?= e( $a['line1'] ) ?><br><?= e( $a['line2'] ) ?></p>
        <?php endforeach; ?>
      </address>
      <p class="site-foot-op">An Arise Treatment Homes LLC Facility Website</p>
      <p class="site-foot-policies"><a href="/privacy-policy/">Privacy Policy</a><a href="/hipaa-policy/">HIPAA Policy</a></p>
    </div>

    <div class="site-foot-trust">
      <div class="site-foot-seals">
        <img src="/assets/img/accreditation/joint-commission.webp" width="96" height="96" alt="The Joint Commission National Quality Approval" loading="lazy">
        <a href="https://www.dhcs.ca.gov/" target="_blank" rel="noopener"><img src="/assets/img/accreditation/dhcs.webp" width="216" height="81" alt="California Department of Health Care Services" loading="lazy"></a>
        <?= dl_legitscript_seal() ?>
      </div>
      <div class="site-foot-license">
        <p>Licensed by the State Department of Health Care Services</p>
        <p><?= e( DL_LICENSE['number'] ) ?></p>
        <p>Expiration Date: <?= e( DL_LICENSE['expires'] ) ?></p>
        <p><a href="https://www.dhcs.ca.gov/" target="_blank" rel="noopener">https://www.dhcs.ca.gov/</a></p>
      </div>
    </div>
  </div>

  <div class="wrap site-foot-bar">
    <nav aria-label="Footer">
      <a href="/programs/">Programs</a>
      <a href="/about/">About</a>
      <a href="/contact/">Contact</a>
    </nav>
    <p>© <?= date( 'Y' ) ?> Detox Locator. All Rights Reserved.</p>
  </div>
  <p class="wrap site-foot-note">Treatment recommendations depend on a clinical assessment, and outcomes vary from person to person. In an emergency, call 911. For 24/7 crisis support, call or text 988.</p>
</footer>
