<?php
/**
 * Landing pages, A family: /lpa-2/, /lpa-2-dkwi/, /lpa-2-dli/, /lpa-bing/.
 *
 * The live design rebuilt section for section: same order, same colours,
 * icons and entrance animations (data-reveal, app.js §8). The deliberate
 * changes are the benefits form beside the carriers, a call button in the
 * fixed header, the care section's own heading (live repeated the programs
 * one), and the reviews as cards on an edge-to-edge rail. Variant differences come
 * from $page (see app/routes.php): the tagline under the H1, the number,
 * the Bing copy, and on a carrier page the accepted badge and its form.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$carrier     = $page['carrier'];
$title       = $carrier ? 'Treatment That Accepts ' . $carrier['name'] . ' | Detox Locator' : 'Your Bridge to Recovery | Detox Locator';
$description = 'Get connected to quality addiction treatment. Same-day admissions, medically-supervised detox and a free, confidential insurance check.';
$css         = array( 'base.css', 'lpa.css' );
$preload     = array(
	'href'   => '/assets/img/photos/counseling-session.webp',
	'srcset' => '/assets/img/photos/counseling-session-720.webp 720w, /assets/img/photos/counseling-session.webp 1200w',
	'sizes'  => '(min-width: 900px) 49vw, 100vw',
);
$body_class  = 'lpa';
$theme       = '#f4f2ff';

$programs = array(
	array( 'detox', 58, 58, 'Medical Detox', 'Safe, comfortable withdrawal management with 24/7 medical supervision. Start your recovery journey with professional support every step of the way.' ),
	array( 'residential', 57, 52, 'Residential Treatment', 'Immersive healing environment with personalized therapy plans. Address underlying causes while building strong foundations for lasting recovery.' ),
	array( 'outpatient', 60, 61, 'Outpatient', 'Flexible programs to support your continued growth. Stay connected to care while transitioning back to daily life.' ),
);

$care = array(
	array( 'behavioral-therapies', 'Full Suite of Behavioral Therapies' ),
	array( 'holistic', 'Innovative holistic treatment approaches' ),
	array( 'dual-diagnosis', 'Dual-diagnosis mental health support' ),
	array( 'adventure', 'Adventure and experiential therapy programs' ),
	array( 'nutrition', 'Nutrition and wellness services' ),
	array( 'family', 'Family support and counseling' ),
);

// A carrier page leads the carrier grid with its own tile.
$tiles = dl_carrier_tiles();
if ( $carrier ) {
	usort( $tiles, fn( $a, $b ) => ( $b['file'] === $carrier['tile'] ) <=> ( $a['file'] === $carrier['tile'] ) );
}

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/lp-header.php';
?>
<main id="main">

<!-- ================= HERO ================= -->
<section class="hero">
  <div class="hero-copy">
    <?php if ( $carrier ) : $mark = dl_carrier_mark( $carrier['mark'] ); ?>
    <p class="accepted" data-reveal="fade">
      <img src="/assets/img/carriers/<?= e( $mark['file'] ) ?>.webp" width="<?= (int) $mark['w'] ?>" height="<?= (int) $mark['h'] ?>" style="height:<?= (int) round( $mark['show'] * .8 ) ?>px" alt="<?= e( $carrier['name'] ) ?>">
      <span><?= dl_icon( 'check' ) ?>PPO plans accepted</span>
    </p>
    <?php endif; ?>
    <h1 data-reveal="fade">Get Connected to Quality Treatment</h1>
    <div data-reveal="fade" data-reveal-delay="150">
      <p class="tagline"><span id="tagline"<?= dl_geo_attrs( $page ) ?>><?= e( $page['tagline'] ) ?></span></p>
      <ul class="checks">
        <li><?= dl_icon( 'check-o' ) ?>Same-Day Admissions Available</li>
        <li><?= dl_icon( 'check-o' ) ?>Medically-Supervised Detox Programs</li>
        <li><?= dl_icon( 'check-o' ) ?>Evidence-Based &amp; Holistic Therapies</li>
        <li><?= dl_icon( 'check-o' ) ?>Luxury Accommodation Programs</li>
        <li><?= dl_icon( 'check-o' ) ?>Full Continuum of Care Options</li>
        <li><?= dl_icon( 'check-o' ) ?>Dual-Diagnosis Treatment Options</li>
      </ul>
    </div>
    <div class="ctas" data-reveal="up" data-reveal-delay="300">
      <a class="btn btn-outline" href="<?= e( $page['tel'] ) ?>" data-cta="call-hero"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-primary" href="#insurance" data-cta="verify-hero">Verify Insurance Now</a>
    </div>
  </div>
  <div class="hero-media">
    <img src="/assets/img/photos/counseling-session.webp" srcset="/assets/img/photos/counseling-session-720.webp 720w, /assets/img/photos/counseling-session.webp 1200w" sizes="(min-width: 900px) 49vw, 100vw" width="1200" height="1165" alt="A couple talking with a counselor in a bright, comfortable room" fetchpriority="high">
  </div>
</section>

<!-- ================= FIRST STEP ================= -->
<section class="first-step on-dark">
  <div class="wrap first-step-grid">
    <div>
      <h2 data-reveal="fade">Take the First Step Forward</h2>
      <?php if ( $page['bing'] ) : ?>
      <p>Breaking free from addiction takes courage – and you don’t have to do it alone. Our premium treatment centers stand ready to help you or your loved one start fresh today.</p>
      <?php else : ?>
      <p>Breaking free from addiction takes courage – and you don’t have to do it alone. Our nationwide network of premium treatment centers stands ready to help you or your loved one start fresh today.</p>
      <?php endif; ?>
      <p>Connect with our caring admissions team now. We’ll match you with a program that fits your unique needs and circumstances within minutes.</p>
      <p>100% Confidential</p>
      <div data-reveal="up">
        <a class="btn btn-light" href="<?= e( $page['tel'] ) ?>" data-cta="call-first-step"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      </div>
    </div>
    <img class="first-step-mark" src="/assets/img/logo/mark-white.webp" width="200" height="200" alt="" loading="lazy">
  </div>
</section>

<!-- ================= PROGRAMS =================
     A white panel set into the indigo band, as live. -->
<section class="programs" aria-labelledby="programs-title">
  <div class="programs-panel">
    <div class="head" data-reveal="fade">
      <h2 id="programs-title">Available Treatment Programs</h2>
      <p>Comprehensive Care Options</p>
    </div>
    <div class="program-grid">
      <?php foreach ( $programs as $i => $pr ) : ?>
      <article class="program program-<?= e( $pr[0] ) ?>" data-reveal="zoom" data-reveal-delay="<?= $i * 120 ?>">
        <img src="/assets/img/icons/program-<?= e( $pr[0] ) ?>.svg" width="<?= (int) $pr[1] ?>" height="<?= (int) $pr[2] ?>" alt="" loading="lazy">
        <h3><?= e( $pr[3] ) ?></h3>
        <p><?= e( $pr[4] ) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="ctas center" data-reveal="up">
      <a class="btn btn-outline" href="<?= e( $page['tel'] ) ?>" data-cta="call-programs"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-primary" href="#insurance" data-cta="verify-programs">Verify Insurance Now</a>
    </div>
  </div>
</section>

<!-- ================= CARE OPTIONS ================= -->
<section class="care" aria-labelledby="care-title">
  <div class="wrap">
    <div class="head">
      <img class="care-mark" src="/assets/img/icons/program-mark.svg" width="62" height="59" alt="" loading="lazy" data-reveal="zoom">
      <h2 id="care-title" data-reveal="fade">Care for the Whole Person</h2>
      <p data-reveal="up">Therapies and Support Beyond Detox</p>
    </div>
    <ul class="care-grid">
      <?php foreach ( $care as $i => $c ) : ?>
      <li data-reveal="up" data-reveal-delay="<?= ( $i % 3 ) * 100 ?>"><img src="/assets/img/icons/<?= e( $c[0] ) ?>.webp" width="90" height="90" alt="" loading="lazy"><span><?= e( $c[1] ) ?></span></li>
      <?php endforeach; ?>
    </ul>
    <div class="believe" data-reveal="up">
      <p>We believe everyone deserves access to quality treatment. Let us help you find the perfect program match – we’ll handle the details while you focus on healing.</p>
      <a class="btn btn-primary" href="#insurance" data-cta="verify-believe">Verify Insurance Now</a>
    </div>
  </div>
</section>

<!-- ================= REVIEWS =================
     The live photo hangs over the band's top edge with the heading at its
     foot; the reviews run edge to edge beneath as the homepage's cards, the
     AI summary first and labelled as such. -->
<section class="stories on-dark" aria-labelledby="stories-title">
  <div class="wrap stories-top">
    <img class="stories-photo" src="/assets/img/photos/woman-on-phone-560.webp" srcset="/assets/img/photos/woman-on-phone-560.webp 560w, /assets/img/photos/woman-on-phone.webp 900w" sizes="(min-width: 900px) 300px, calc(100vw - 2 * var(--gutter))" width="560" height="801" alt="A smiling woman talking on her phone" loading="lazy">
    <div class="stories-intro" data-reveal="fade">
      <h2 id="stories-title">Real Recovery Stories</h2>
      <img class="google-reviews" src="/assets/img/icons/google-reviews.webp" width="340" height="192" alt="Google Reviews, five stars" loading="lazy">
    </div>
    <div class="rail-btns">
      <button class="rail-btn" type="button" data-rail="prev" aria-controls="stories-rail" aria-label="Previous reviews"><?= dl_icon( 'prev' ) ?></button>
      <button class="rail-btn" type="button" data-rail="next" aria-controls="stories-rail" aria-label="Next reviews"><?= dl_icon( 'next' ) ?></button>
    </div>
  </div>
  <div class="rail rail-bleed" id="stories-rail" tabindex="0" aria-label="Google reviews — scroll for more">
    <figure class="quote quote-summary">
      <p class="summary-tag"><?= dl_icon( 'spark' ) ?>AI-generated summary</p>
      <blockquote><p><?= e( dl_lp_summary( $page['bing'] ) ) ?></p></blockquote>
      <figcaption><?= dl_google_g( 16 ) ?><span>Based on Google reviews</span></figcaption>
    </figure>
    <?php foreach ( dl_lp_reviews() as $r ) : ?>
    <figure class="quote">
      <?= dl_stars() ?>
      <blockquote><p>“<?= e( $r['quote'] ) ?>”</p></blockquote>
      <figcaption><?= dl_google_g( 16 ) ?><span><?= e( $r['name'] ) ?></span></figcaption>
    </figure>
    <?php endforeach; ?>
  </div>
</section>

<!-- ================= INSURANCE =================
     The carriers beside the form, so the logos sit next to the ask. -->
<section class="insurance" id="insurance" aria-labelledby="insurance-title">
  <div class="wrap insurance-grid">
    <div data-reveal="fade">
      <h2 id="insurance-title">Verify Your Insurance Coverage</h2>
      <p class="insurance-sub">No-Cost, No-Obligation Benefits Check</p>
      <p>We work with most major PPO insurance providers:</p>
      <ul class="tiles">
        <?php foreach ( $tiles as $c ) : ?>
        <li><img src="/assets/img/carriers-tile/<?= e( $c['file'] ) ?>.webp" width="300" height="185" alt="<?= e( $c['alt'] ) ?>" loading="lazy"></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div id="verify">
      <?php
      $form = array(
          'id'     => 'lpa-form',
          'title'     => $carrier ? 'Check my ' . $carrier['name'] . ' benefits' : 'Check my benefits',
          'sub'       => 'A specialist reviews your coverage and calls you back.',
          'button'    => 'Verify Insurance Now',
          'insurance' => $carrier['name'] ?? '',
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
