<?php
/**
 * Landing pages, A family: /lpa-2/, /lpa-2-dkwi/, /lpa-2-dli/, /lpa-bing/.
 *
 * The live design rebuilt section for section, same copy, same order. The one
 * deliberate change is the benefits form. Variant differences come from
 * $page (see app/routes.php): the tagline under the H1, the number, and the
 * Bing copy.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'Your Bridge to Recovery | Detox Locator';
$description = 'Get connected to quality addiction treatment. Same-day admissions, medically-supervised detox and a free, confidential insurance check.';
$css         = array( 'base.css', 'lpa.css' );
$preload     = array(
	'href'   => '/assets/img/photos/counseling-session.webp',
	'srcset' => '/assets/img/photos/counseling-session-720.webp 720w, /assets/img/photos/counseling-session.webp 1200w',
	'sizes'  => '(min-width: 900px) 50vw, 100vw',
);
$body_class  = 'lpa';
$theme       = '#f4f2ff';

require DL_ROOT . '/partials/head.php';
?>
<main id="main">

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="hero-copy">
    <img class="hero-logo" src="/assets/img/logo/logo.webp" width="240" height="102" alt="Detox Locator">
    <h1>Get Connected to Quality Treatment</h1>
    <p class="tagline"><span id="tagline" <?= $page['geo'] ? 'data-geo="Find Treatment in {state} in Minutes"' : '' ?>><?= e( $page['tagline'] ) ?></span></p>
    <ul class="checks">
      <li><?= dl_icon( 'check-o' ) ?>Same-Day Admissions Available</li>
      <li><?= dl_icon( 'check-o' ) ?>Medically-Supervised Detox Programs</li>
      <li><?= dl_icon( 'check-o' ) ?>Evidence-Based &amp; Holistic Therapies</li>
      <li><?= dl_icon( 'check-o' ) ?>Luxury Accommodation Programs</li>
      <li><?= dl_icon( 'check-o' ) ?>Full Continuum of Care Options</li>
      <li><?= dl_icon( 'check-o' ) ?>Dual-Diagnosis Treatment Options</li>
    </ul>
    <div class="ctas">
      <a class="btn btn-outline" href="<?= e( $page['tel'] ) ?>" data-cta="call-hero"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-primary" href="#insurance" data-cta="verify-hero">Verify Insurance Now</a>
    </div>
  </div>
  <div class="hero-media">
    <img src="/assets/img/photos/counseling-session.webp" srcset="/assets/img/photos/counseling-session-720.webp 720w, /assets/img/photos/counseling-session.webp 1200w" sizes="(min-width: 900px) 50vw, 100vw" width="1200" height="1165" alt="A couple talking with a counselor in a bright, comfortable room" fetchpriority="high">
    <a class="btn btn-primary hero-pill" href="#insurance" data-cta="verify-pill">Verify Insurance Now</a>
  </div>
</section>

<!-- ================= FIRST STEP ================= -->
<section class="first-step on-dark">
  <div class="wrap first-step-grid">
    <div>
      <h2>Take the First Step Forward</h2>
      <?php if ( $page['bing'] ) : ?>
      <p>Breaking free from addiction takes courage – and you don’t have to do it alone. Our premium treatment centers stand ready to help you or your loved one start fresh today.</p>
      <?php else : ?>
      <p>Breaking free from addiction takes courage – and you don’t have to do it alone. Our nationwide network of premium treatment centers stands ready to help you or your loved one start fresh today.</p>
      <?php endif; ?>
      <p>Connect with our caring admissions team now. We’ll match you with a program that fits your unique needs and circumstances within minutes.</p>
      <p class="confidential"><?= dl_icon( 'lock' ) ?>100% Confidential</p>
      <a class="btn btn-light" href="<?= e( $page['tel'] ) ?>" data-cta="call-first-step"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
    </div>
    <img class="first-step-mark" src="/assets/img/logo/mark-white.webp" width="200" height="200" alt="" loading="lazy">
  </div>
</section>

<!-- ================= PROGRAMS ================= -->
<section class="section programs">
  <div class="wrap">
    <div class="head">
      <h2>Available Treatment Programs</h2>
      <p>Comprehensive Care Options</p>
    </div>
    <div class="program-grid">
      <article class="program">
        <span class="program-ic"><?= dl_icon( 'detox' ) ?></span>
        <h3>Medical Detox</h3>
        <p>Safe, comfortable withdrawal management with 24/7 medical supervision. Start your recovery journey with professional support every step of the way.</p>
      </article>
      <article class="program">
        <span class="program-ic"><?= dl_icon( 'home' ) ?></span>
        <h3>Residential Treatment</h3>
        <p>Immersive healing environment with personalized therapy plans. Address underlying causes while building strong foundations for lasting recovery.</p>
      </article>
      <article class="program">
        <span class="program-ic"><?= dl_icon( 'path' ) ?></span>
        <h3>Outpatient</h3>
        <p>Flexible programs to support your continued growth. Stay connected to care while transitioning back to daily life.</p>
      </article>
    </div>
    <div class="ctas center">
      <a class="btn btn-outline" href="<?= e( $page['tel'] ) ?>" data-cta="call-programs"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-primary" href="#insurance" data-cta="verify-programs">Verify Insurance Now</a>
    </div>
  </div>
</section>

<!-- ================= CARE OPTIONS ================= -->
<section class="section care">
  <div class="wrap">
    <div class="head">
      <h2>Comprehensive Care Options</h2>
    </div>
    <ul class="care-grid">
      <li><img src="/assets/img/icons/behavioral-therapies.webp" width="72" height="72" alt="" loading="lazy">Full Suite of Behavioral Therapies</li>
      <li><img src="/assets/img/icons/holistic.webp" width="72" height="72" alt="" loading="lazy">Innovative holistic treatment approaches</li>
      <li><img src="/assets/img/icons/dual-diagnosis.webp" width="72" height="72" alt="" loading="lazy">Dual-diagnosis mental health support</li>
      <li><img src="/assets/img/icons/adventure.webp" width="72" height="72" alt="" loading="lazy">Adventure and experiential therapy programs</li>
      <li><img src="/assets/img/icons/nutrition.webp" width="72" height="72" alt="" loading="lazy">Nutrition and wellness services</li>
      <li><img src="/assets/img/icons/family.webp" width="72" height="72" alt="" loading="lazy">Family support and counseling</li>
    </ul>
    <div class="believe">
      <p>We believe everyone deserves access to quality treatment. Let us help you find the perfect program match – we’ll handle the details while you focus on healing.</p>
      <a class="btn btn-primary" href="#insurance" data-cta="verify-believe">Verify Insurance Now</a>
    </div>
  </div>
</section>

<!-- ================= REVIEWS ================= -->
<section class="section stories on-dark" aria-labelledby="stories-title">
  <div class="wrap">
    <div class="stories-head">
      <h2 id="stories-title">Real Recovery Stories</h2>
      <div class="rail-btns">
        <button class="rail-btn" type="button" data-rail="prev" aria-controls="stories-rail" aria-label="Previous reviews"><?= dl_icon( 'prev' ) ?></button>
        <button class="rail-btn" type="button" data-rail="next" aria-controls="stories-rail" aria-label="Next reviews"><?= dl_icon( 'next' ) ?></button>
      </div>
    </div>
    <div class="rail" id="stories-rail" tabindex="0" aria-label="Reviews — scroll for more">
      <figure class="story story-summary">
        <div class="story-who">
          <img src="/assets/img/icons/ai-logo.webp" width="40" height="40" alt="">
          <div><p class="story-name">AI-Generated Summary</p><p class="story-src">Based on Google reviews</p></div>
        </div>
        <?= dl_stars() ?>
        <blockquote><p><?= e( dl_lp_summary( $page['bing'] ) ) ?></p></blockquote>
      </figure>
      <?php foreach ( dl_lp_reviews() as $r ) : ?>
      <figure class="story">
        <?= dl_stars() ?>
        <blockquote><p>“<?= e( $r['quote'] ) ?>”</p></blockquote>
        <figcaption class="story-who">
          <img src="/assets/img/icons/g-logo.webp" width="28" height="28" alt="" loading="lazy">
          <div><p class="story-name"><?= e( $r['name'] ) ?></p><p class="story-src">Based on Google reviews</p></div>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= INSURANCE ================= -->
<section class="section insurance" id="insurance">
  <div class="wrap insurance-grid">
    <div class="insurance-copy">
      <h2>Verify Your Insurance Coverage</h2>
      <p class="insurance-sub">No-Cost, No-Obligation Benefits Check</p>
      <p>We work with most major PPO insurance providers:</p>
      <ul class="tiles">
        <?php foreach ( dl_carrier_tiles() as $c ) : ?>
        <li><img src="/assets/img/carriers-tile/<?= e( $c['file'] ) ?>.webp" width="300" height="185" alt="<?= e( $c['alt'] ) ?>" loading="lazy"></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div id="verify">
      <?php
      $form = array(
          'id'     => 'lpa-form',
          'title'  => 'Check my benefits',
          'sub'    => 'A specialist reviews your coverage and calls you back.',
          'button' => 'Verify Insurance Now',
      );
      require DL_ROOT . '/partials/form.php';
      ?>
    </div>
  </div>
</section>

</main>

<?php
$footer_class = 'lp-foot lpa-foot';
require DL_ROOT . '/partials/lp-footer.php';
?>
</body>
</html>
