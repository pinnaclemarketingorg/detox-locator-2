<?php
/**
 * /lpc/ — the Invigorate Behavioral Health co-brand page.
 *
 * Laid out like the live one: a directory-style facility profile with the
 * form beside it, an anchor bar, the centre overview and full reviews. It
 * carries two numbers, as the live page does: the page's tracked number in
 * the header and footer, and the Invigorate help line on the hero card.
 *
 * Three typos on the live page are corrected here ("Inigorate",
 * "Master ‘s-level", "housingkeeping"); nothing else in the copy changed.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$help     = dl_cfg( 'phone_lpc' );
$help_tel = dl_tel( $help );

$title       = 'Premier Addiction Treatment Program | Invigorate Behavioral Health';
$description = 'Invigorate Behavioral Health: luxury residential detox and rehab with outpatient follow-up care. Private insurance accepted. Free, confidential insurance verification.';
$css         = array( 'base.css', 'lpc.css' );
$preload     = array(
	'href'   => '/assets/img/photos/facility-exterior.webp',
	'srcset' => '/assets/img/photos/facility-exterior-760.webp 760w, /assets/img/photos/facility-exterior.webp 1400w',
	'sizes'  => '100vw',
);
$body_class  = 'lpc';
$theme       = '#ffffff';

$overview = array(
	'What we treat'               => array( 'Alcohol addiction', 'Drug addiction', 'Mental health disorder', 'Co-occurring disorders', 'Impulse control disorders', 'Trauma' ),
	'Who we treat'                => array( 'Men', 'Women', 'Adults 18+', 'Professionals' ),
	'Amenities & accommodations' => array( 'Private & semi-private rooms', 'Safe environment', 'Upscale residential housing', 'In-house fitness equipment', 'Transportation assistance', 'Chef prepared meals', 'Daily housekeeping', 'Outdoor patios', 'Weekend outings' ),
	'Services and therapies'      => array( 'Medical detox', 'Medication-assisted treatment', '1-on-1 counseling', 'Dual-diagnosis treatment', 'Meditation & mindfulness', 'CBT', 'DBT', 'Twelve step facilitation', 'Family and group therapy', 'Alumni programming', 'Case management', 'Discharge planning', 'Aftercare support' ),
);

require DL_ROOT . '/partials/head.php';
?>
<header class="lpc-top">
  <div class="wrap lpc-top-row">
    <img class="lpc-logo" src="/assets/img/logo/logo.webp" width="180" height="77" alt="Detox Locator">
    <div class="lpc-top-actions">
      <a class="btn btn-outline" href="<?= e( $page['tel'] ) ?>" data-cta="call-header"><?= dl_icon( 'phone' ) ?><?= e( $page['phone'] ) ?></a>
      <a class="btn btn-primary lpc-top-verify" href="#verify" data-cta="verify-header">Verify Insurance</a>
    </div>
  </div>
</header>

<main id="main">

<section class="lpc-hero">
  <img src="/assets/img/photos/facility-exterior.webp" srcset="/assets/img/photos/facility-exterior-760.webp 760w, /assets/img/photos/facility-exterior.webp 1400w" sizes="100vw" width="1400" height="881" alt="The Invigorate Behavioral Health residence: modern white townhomes with balconies and gardens" fetchpriority="high">
  <div class="wrap">
    <div class="helpline">
      <div>
        <p class="helpline-title">24/7 Confidential Help Line</p>
        <p class="helpline-sub">Free Insurance Verification</p>
      </div>
      <a class="btn btn-call" href="<?= e( $help_tel ) ?>" data-cta="call-helpline"><?= dl_icon( 'phone' ) ?><?= e( $help ) ?></a>
    </div>
  </div>
</section>

<nav class="tabs" aria-label="On this page">
  <div class="wrap">
    <a href="#about">About</a>
    <a href="#insurance">Insurance</a>
    <a href="#treatment">Treatment</a>
    <a href="#amenities">Amenities</a>
    <a href="#reviews">Reviews</a>
  </div>
</nav>

<section class="profile" id="about">
  <div class="wrap profile-grid">
    <div class="profile-copy">
      <h1>Invigorate Behavioral Health <span class="verified"><?= dl_icon( 'check-o' ) ?>Verified</span></h1>
      <?= dl_stars() ?>
      <ul class="chips">
        <li><?= dl_icon( 'shield' ) ?>Private insurance accepted</li>
        <li><?= dl_icon( 'check-o' ) ?>Cash pay options available.</li>
      </ul>
      <p>Invigorate Behavioral Health offers a luxurious residential detox and rehabilitation experience with outpatient follow-up care. Programs are medically-supervised to provide the utmost comfort and safety for their patients.</p>
      <p>Invigorate’s innovative therapies aim to heal the whole person and address core causes of addiction.</p>
    </div>
    <div id="verify">
      <?php
      $form = array(
          'id'     => 'lpc-form',
          'title'  => 'Connect with Invigorate',
          'sub'    => 'Free, confidential insurance verification.',
          'button' => 'Verify Insurance',
      );
      require DL_ROOT . '/partials/form.php';
      ?>
    </div>
  </div>
</section>

<section class="lpc-insurance" id="insurance">
  <div class="wrap">
    <h2 class="sr-only">Insurance</h2>
    <ul class="ticks">
      <li><?= dl_icon( 'check' ) ?>Most private insurance accepted</li>
      <li><?= dl_icon( 'check' ) ?>Exceptional remission rates</li>
      <li><?= dl_icon( 'check' ) ?>Career and educational planning</li>
    </ul>
    <ul class="tiles">
      <?php foreach ( dl_carrier_tiles() as $c ) : ?>
      <li><img src="/assets/img/carriers-tile/<?= e( $c['file'] ) ?>.webp" width="300" height="185" alt="<?= e( $c['alt'] ) ?>" loading="lazy"></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="treatment" id="treatment">
  <div class="wrap">
    <div class="treatment-box">
      <h2>Drug and Alcohol Addiction Treatment</h2>
      <div>
        <p>Invigorate Behavioral Health is a boutique addiction treatment center offering dual-diagnosis care to address co-occurring mental health and addiction — a full-suite behavioral health approach. Their full continuum of care doesn’t stop after detoxification and inpatient care.</p>
        <p>Master’s-level licensed, board-certified therapists and medical doctors at Invigorate Behavioral Health put a personal spin on behavioral health care, ensuring each patient has a unique plan catered for their daily 1:1 addiction therapy sessions.</p>
        <a class="btn btn-call" href="<?= e( $page['tel'] ) ?>" data-cta="call-treatment"><?= dl_icon( 'phone' ) ?><?= e( $page['phone'] ) ?></a>
      </div>
    </div>
  </div>
</section>

<section class="path">
  <div class="wrap">
    <div class="path-head">
      <h2>Your Path to Recovery</h2>
      <p>Guiding You Through Every Stage of Recovery</p>
    </div>
    <div class="levels on-dark">
      <h3 class="levels-title">Levels of Care</h3>
      <ol class="level-list">
        <li><h4>Medical Detox</h4><p>Alleviate withdrawal symptoms with protocols designed for safety and comfort.</p></li>
        <li><h4>Residential Rehab</h4><p>Address underlying roots of addictive behavior with dual-diagnosis and trauma-informed therapy living at an upscale residence.</p></li>
        <li><h4>Outpatient Rehab</h4><p>Strengthen the foundation of recovery with furthered therapy, and maintain engagement with your new support system.</p></li>
      </ol>
      <a class="btn btn-light" href="<?= e( $page['tel'] ) ?>" data-cta="call-levels"><?= dl_icon( 'phone' ) ?><?= e( $page['phone'] ) ?></a>
    </div>
  </div>
</section>

<section class="overview" id="amenities">
  <div class="wrap">
    <h2 class="overview-title"><?= dl_icon( 'home' ) ?>Center Overview</h2>
    <div class="overview-grid">
      <?php foreach ( $overview as $heading => $items ) : ?>
      <div>
        <h3><?= e( $heading ) ?></h3>
        <ul>
          <?php foreach ( $items as $item ) : ?><li><?= dl_icon( 'check' ) ?><?= e( $item ) ?></li><?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
    <ul class="gallery">
      <li><img src="/assets/img/photos/facility-living-640.webp" srcset="/assets/img/photos/facility-living-640.webp 640w, /assets/img/photos/facility-living.webp 1200w" sizes="(min-width: 900px) 33vw, 100vw" width="640" height="427" alt="Living room with floor-to-ceiling windows and a balcony" loading="lazy"></li>
      <li><img src="/assets/img/photos/facility-lounge-640.webp" srcset="/assets/img/photos/facility-lounge-640.webp 640w, /assets/img/photos/facility-lounge.webp 1200w" sizes="(min-width: 900px) 33vw, 100vw" width="640" height="427" alt="Open-plan lounge and kitchen" loading="lazy"></li>
      <li><img src="/assets/img/photos/facility-kitchen-640.webp" srcset="/assets/img/photos/facility-kitchen-640.webp 640w, /assets/img/photos/facility-kitchen.webp 1200w" sizes="(min-width: 900px) 33vw, 100vw" width="640" height="483" alt="Kitchen and dining table" loading="lazy"></li>
    </ul>
  </div>
</section>

<section class="reviews" id="reviews">
  <div class="wrap">
    <h2 class="reviews-title"><?= dl_google_g( 26 ) ?>Reviews</h2>
    <div class="review-grid">
      <?php foreach ( dl_full_reviews() as $r ) : ?>
      <figure class="review">
        <figcaption class="review-who">
          <img src="/assets/img/avatars/<?= e( $r['avatar'] ) ?>.webp" width="40" height="40" alt="" loading="lazy">
          <span><?= e( $r['name'] ) ?></span>
          <?= dl_icon( 'check-o', 'ic review-verified' ) ?>
        </figcaption>
        <?= dl_stars() ?>
        <blockquote>
          <?php foreach ( $r['paras'] as $p ) : ?><p><?= e( $p ) ?></p><?php endforeach; ?>
        </blockquote>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

</main>

<footer class="lpc-foot">
  <div class="wrap lpc-foot-row">
    <img src="/assets/img/logo/logo.webp" width="160" height="68" alt="Detox Locator" loading="lazy">
    <p class="lpc-foot-call">Call Us<a href="<?= e( $page['tel'] ) ?>" data-cta="call-footer"><?= e( $page['phone'] ) ?></a></p>
  </div>
  <div class="wrap lpc-foot-legal">
    <p>Copyright © <?= date( 'Y' ) ?> Detox Locator. All Rights Reserved.</p>
    <p><a href="/privacy-policy/">Privacy Policy</a><a href="/hipaa-policy/">HIPAA Policy</a></p>
  </div>
</footer>
</body>
</html>
