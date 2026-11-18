<?php
/**
 * /about/ — who runs this site, how they work, and the credentials behind it.
 *
 * The operator line is taken from the privacy policy ("Arise Treatment Homes
 * LLC, doing business as Invigorate Behavioral Health … owner and operator of
 * detox-locator.com"), so the two pages cannot tell different stories.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'About Us | Detox Locator';
$description = 'Detox Locator is operated by Arise Treatment Homes LLC, doing business as Invigorate Behavioral Health, part of Pinnacle Health Group. Joint Commission accredited and DHCS licensed.';
$css         = array( 'base.css', 'site.css' );
$body_class  = 'site inner';

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">

<section class="page-hero on-dark">
  <div class="wrap">
    <h1>About Detox Locator</h1>
    <p class="lede">Detox Locator is operated by Arise Treatment Homes LLC, doing business as Invigorate Behavioral Health, a licensed addiction treatment provider in Los Angeles and part of Pinnacle Health Group.</p>
  </div>
</section>

<section class="section split">
  <div class="wrap split-grid">
    <figure class="split-media frame-offset">
      <img src="/assets/img/photos/embrace.webp" width="600" height="400" alt="Two people embracing during a support group" loading="eager">
    </figure>
    <div class="split-copy">
      <h2 class="h-section">Why us?</h2>
      <p class="lede">Our team ensures that every caller receives the kind of care we desire for our own family and friends. Each individual is unique, and we commit to a highly personalized approach, addressing not just addiction but also any co-occurring disorders like trauma, depression, or anxiety.</p>
      <p>For the highest chance of success, we strongly believe a comprehensive recovery plan with both traditional and holistic methods is needed. And our mission here is to help as many people as we possibly can receive that standard of care.</p>
      <p>Whether you’re starting this journey for yourself or supporting someone else in theirs, our team is here to light the way through every step towards reclaiming a life of vitality and purpose.</p>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head">
      <h2 class="h-section">Licensed and accredited</h2>
      <p>Checked by the people whose job is to check.</p>
    </div>
    <ul class="creds">
      <li>
        <img src="/assets/img/accreditation/joint-commission.webp" width="88" height="88" alt="" loading="lazy">
        <h3>The Joint Commission</h3>
        <p>Accredited with the Gold Seal of Approval, the national standard for quality and safety in healthcare.</p>
      </li>
      <li>
        <img src="/assets/img/accreditation/dhcs-logo.svg" width="168" height="44" alt="" loading="lazy" class="cred-wide">
        <h3>California DHCS</h3>
        <p>Licensed by the State Department of Health Care Services, license <?= e( DL_LICENSE['number'] ) ?>, expiring <?= e( DL_LICENSE['expires'] ) ?>.</p>
        <a class="text-link" href="https://www.dhcs.ca.gov/" target="_blank" rel="noopener">dhcs.ca.gov<?= dl_icon( 'external' ) ?></a>
      </li>
      <li>
        <a href="https://www.legitscript.com/websites/?checker_keywords=detox-locator.com" target="_blank" rel="noopener" title="Verify LegitScript Approval for www.detox-locator.com"><img src="https://static.legitscript.com/seals/43156119.png" width="73" height="79" alt="Verify Approval for detox-locator.com" loading="lazy"></a>
        <h3>LegitScript certified</h3>
        <p>Independently verified to meet advertising and operational standards for addiction treatment.</p>
      </li>
    </ul>
  </div>
</section>

<section class="section">
  <div class="wrap split-grid split-flip">
    <figure class="split-media">
      <img src="/assets/img/photos/facility-exterior-760.webp" srcset="/assets/img/photos/facility-exterior-760.webp 760w, /assets/img/photos/facility-exterior.webp 1400w" sizes="(min-width: 900px) 45vw, 100vw" width="760" height="478" alt="The residence: modern white townhomes with balconies and gardens" loading="lazy">
    </figure>
    <div class="split-copy">
      <h2 class="h-section">Our residences</h2>
      <p class="lede">Upscale residential housing in Los Angeles, with private and semi-private rooms, chef-prepared meals and outdoor patios.</p>
      <ul class="addr-list">
        <?php foreach ( DL_ADDRESSES as $a ) : ?>
        <li><?= dl_icon( 'pin' ) ?><span><?= e( $a['line1'] ) ?><br><?= e( $a['line2'] ) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <h3 class="h-mini">Who we treat</h3>
      <ul class="pills">
        <li>Men</li><li>Women</li><li>Adults 18+</li><li>Professionals</li>
      </ul>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap pinnacle-block">
    <img src="/assets/img/logo/pinnacle-mark.webp" width="70" height="69" alt="" loading="lazy">
    <div>
      <h2 class="h-sub">Part of Pinnacle Health Group</h2>
      <p>Pinnacle Health Group is a network of licensed addiction treatment providers. Being part of it means one admissions team can look across more than one program to find the right fit for you.</p>
      <a class="text-link" href="https://pinnaclehealthgroup.org/" target="_blank" rel="noopener">pinnaclehealthgroup.org<?= dl_icon( 'external' ) ?></a>
    </div>
  </div>
</section>

<?php require DL_ROOT . '/partials/site-cta.php'; ?>

</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
