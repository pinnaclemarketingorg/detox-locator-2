<?php
/**
 * Landing pages, D family: /lpd/, /lpd-dkwi/, /lpd-dli/, /lpd-bing/.
 *
 * The A-family copy, word for word where it appears, in a calmer and more
 * current layout. What moved, and why:
 *
 *   - The benefits form is in the hero. On the A pages it is the last thing
 *     before the footer, so paid traffic has to read the whole page to reach
 *     the only conversion that is not a phone call.
 *   - The three programs read as one continuum, because they are one: a
 *     sequence is the honest shape for detox → residential → outpatient.
 *   - The reviews no longer repeat three times over in the markup.
 *   - Phones get a sticky call / verify bar, since the header scrolls away.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'Get Connected to Quality Treatment | Detox Locator';
$description = 'Find treatment in minutes. Same-day admissions, medically-supervised detox, and a free, no-obligation insurance check.';
$css         = array( 'base.css', 'lpd.css' );
$body_class  = 'lpd';

require DL_ROOT . '/partials/head.php';
?>
<!-- Sits over the hero rather than above it, so the indigo runs edge to edge. -->
<header class="top on-dark">
  <div class="wrap top-row">
    <img class="top-logo" src="/assets/img/logo/logo-white.webp" width="480" height="204" alt="Detox Locator">
    <a class="top-call" href="<?= e( $page['tel'] ) ?>" data-cta="call-header"><?= dl_icon( 'phone' ) ?><span class="top-call-label">24/7 Confidential Help</span><span class="top-call-num"><?= e( $page['phone'] ) ?></span></a>
  </div>
</header>

<main id="main">
  <section class="hero on-dark">
    <img class="hero-mark" src="/assets/img/logo/mark-white.webp" width="200" height="200" alt="">
    <div class="wrap hero-grid">
      <div class="hero-copy">
        <h1>Get connected to quality treatment</h1>
        <p class="tagline"><span id="tagline" <?= $page['geo'] ? 'data-geo="Find Treatment in {state} in Minutes"' : '' ?>><?= e( $page['tagline'] ) ?></span></p>
        <div class="hero-ctas">
          <a class="btn btn-light btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-hero"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
        </div>
        <ul class="hero-checks">
          <li><?= dl_icon( 'check' ) ?>Same-day admissions available</li>
          <li><?= dl_icon( 'check' ) ?>Medically-supervised detox programs</li>
          <li><?= dl_icon( 'check' ) ?>Evidence-based &amp; holistic therapies</li>
          <li><?= dl_icon( 'check' ) ?>Luxury accommodation programs</li>
          <li><?= dl_icon( 'check' ) ?>Full continuum of care options</li>
          <li><?= dl_icon( 'check' ) ?>Dual-diagnosis treatment options</li>
        </ul>
      </div>

      <div class="hero-form" id="verify">
        <?php
        $form = array(
            'id'     => 'lpd-form',
            'title'  => 'Verify your insurance coverage',
            'sub'    => 'No-cost, no-obligation benefits check.',
            'button' => 'Verify insurance now',
        );
        require DL_ROOT . '/partials/form.php';
        ?>
      </div>
    </div>
  </section>

<section class="carriers" aria-labelledby="carriers-title">
  <div class="wrap">
    <p class="carriers-title" id="carriers-title">We work with most major PPO insurance providers</p>
    <ul class="carrier-row">
      <?php foreach ( dl_carrier_marks() as $c ) : ?>
      <li><img src="/assets/img/carriers/<?= e( $c['file'] ) ?>.webp" width="<?= (int) $c['w'] ?>" height="<?= (int) $c['h'] ?>" style="height:<?= (int) $c['show'] ?>px" alt="<?= e( $c['alt'] ) ?>" loading="lazy"></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section first-step">
  <div class="wrap first-step-grid">
    <figure class="arch">
      <img src="/assets/img/photos/woman-on-phone-560.webp" srcset="/assets/img/photos/woman-on-phone-560.webp 560w, /assets/img/photos/woman-on-phone.webp 900w" sizes="(min-width: 900px) 420px, 80vw" width="560" height="801" alt="A woman smiling as she talks on the phone" loading="lazy">
    </figure>
    <div class="first-step-copy">
      <h2 class="section-title">Take the first step forward</h2>
      <?php if ( $page['bing'] ) : ?>
      <p class="lede">Breaking free from addiction takes courage – and you don’t have to do it alone. Our premium treatment centers stand ready to help you or your loved one start fresh today.</p>
      <?php else : ?>
      <p class="lede">Breaking free from addiction takes courage – and you don’t have to do it alone. Our nationwide network of premium treatment centers stands ready to help you or your loved one start fresh today.</p>
      <?php endif; ?>
      <p>Connect with our caring admissions team now. We’ll match you with a program that fits your unique needs and circumstances within minutes.</p>
      <p class="confidential"><?= dl_icon( 'lock' ) ?>100% confidential</p>
      <a class="btn btn-primary btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-first-step"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
    </div>
  </div>
</section>

<section class="section programs">
  <div class="wrap">
    <h2 class="section-title">Available treatment programs</h2>
    <ol class="continuum">
      <li>
        <span class="node"><?= dl_icon( 'detox' ) ?></span>
        <h3>Medical detox</h3>
        <p>Safe, comfortable withdrawal management with 24/7 medical supervision. Start your recovery journey with professional support every step of the way.</p>
      </li>
      <li>
        <span class="node"><?= dl_icon( 'home' ) ?></span>
        <h3>Residential treatment</h3>
        <p>Immersive healing environment with personalized therapy plans. Address underlying causes while building strong foundations for lasting recovery.</p>
      </li>
      <li>
        <span class="node"><?= dl_icon( 'path' ) ?></span>
        <h3>Outpatient</h3>
        <p>Flexible programs to support your continued growth. Stay connected to care while transitioning back to daily life.</p>
      </li>
    </ol>

    <div class="care">
      <h3 class="care-title">Comprehensive care options</h3>
      <ul class="care-list">
        <li><span><?= dl_icon( 'chat' ) ?></span>Full suite of behavioral therapies</li>
        <li><span><?= dl_icon( 'leaf' ) ?></span>Innovative holistic treatment approaches</li>
        <li><span><?= dl_icon( 'brain' ) ?></span>Dual-diagnosis mental health support</li>
        <li><span><?= dl_icon( 'compass' ) ?></span>Adventure and experiential therapy programs</li>
        <li><span><?= dl_icon( 'apple' ) ?></span>Nutrition and wellness services</li>
        <li><span><?= dl_icon( 'family' ) ?></span>Family support and counseling</li>
      </ul>
    </div>

    <div class="program-ctas">
      <a class="btn btn-primary" href="<?= e( $page['tel'] ) ?>" data-cta="call-programs"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-outline" href="#verify" data-cta="verify-programs">Verify insurance now</a>
    </div>
  </div>
</section>

<section class="section stories" aria-labelledby="stories-title">
  <div class="wrap stories-grid">
    <div class="stories-intro">
      <h2 class="section-title" id="stories-title">Real recovery stories</h2>
      <p class="summary"><?= e( dl_lp_summary( $page['bing'] ) ) ?></p>
      <p class="summary-src"><?= dl_icon( 'spark' ) ?>AI-generated summary, based on Google reviews</p>
      <div class="rail-btns">
        <button class="rail-btn" type="button" data-rail="prev" aria-controls="lpd-rail" aria-label="Previous reviews"><?= dl_icon( 'prev' ) ?></button>
        <button class="rail-btn" type="button" data-rail="next" aria-controls="lpd-rail" aria-label="Next reviews"><?= dl_icon( 'next' ) ?></button>
      </div>
    </div>
    <div class="rail" id="lpd-rail" tabindex="0" aria-label="Google reviews — scroll for more">
      <?php foreach ( dl_lp_reviews() as $r ) : ?>
      <figure class="quote">
        <?= dl_stars() ?>
        <blockquote><p><?= e( $r['quote'] ) ?></p></blockquote>
        <figcaption><?= dl_google_g( 16 ) ?><span><?= e( $r['name'] ) ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="closing on-dark">
  <div class="wrap closing-inner">
    <p class="closing-line">We believe everyone deserves access to quality treatment.</p>
    <p class="closing-sub">Let us help you find the perfect program match – we’ll handle the details while you focus on healing.</p>
    <div class="closing-ctas">
      <a class="btn btn-light btn-lg" href="#verify" data-cta="verify-closing">Verify insurance now</a>
      <a class="btn btn-ghost-light btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-closing"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
    </div>
  </div>
</section>
</main>

<?php
$footer_class = 'lp-foot lpd-foot';
require DL_ROOT . '/partials/lp-footer.php';
?>

<!-- Phones only. The header's number scrolls away and this is a page whose
     whole job is a call or a form. -->
<nav class="dock" aria-label="Quick actions">
  <a class="btn btn-primary" href="<?= e( $page['tel'] ) ?>" data-cta="call-dock"><?= dl_icon( 'phone' ) ?>Call now</a>
  <a class="btn btn-light" href="#verify" data-cta="verify-dock">Verify insurance</a>
</nav>
</body>
</html>
