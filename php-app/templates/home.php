<?php
/**
 * The homepage. Now the site's front door rather than a fifth landing page:
 * menu, programs, the residences, how a call works, reviews, FAQs, the
 * benefits form and the licensing footer.
 *
 * Copy is the live homepage's wherever it had some. The "what happens when
 * you call" steps and the FAQ answers come from Pinnacle Health Group's own
 * pages, which this site is part of.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'Detox Locator | Addiction Treatment in Los Angeles — Detox, Residential & Outpatient';
$description = 'Medically-supervised detox, residential rehab and outpatient care with dual-diagnosis therapy. Joint Commission accredited, DHCS licensed. Free, confidential insurance verification.';
$css         = array( 'base.css', 'site.css' );
$preload     = array(
	'href'   => '/assets/img/photos/group-support.webp',
	'srcset' => '/assets/img/photos/group-support-900.webp 900w, /assets/img/photos/group-support.webp 1600w',
	'sizes'  => '100vw',
);
$body_class  = 'site home';

$faqs = array(
	array( 'Can I speak with someone before deciding anything?', 'Of course. Reaching out doesn’t lock you into anything. Our admissions team is ready to listen, walk you through your options, and answer whatever’s on your mind — no pressure, no judgment. It’s a conversation, not a commitment.' ),
	array( 'How fast can I get started?', 'Same-day admissions are possible. If you or someone you love is in crisis or ready to take that step, call us and we’ll move as quickly as we can to get you into care.' ),
	array( 'Will my employer know I’m in treatment?', 'Your treatment is protected under federal HIPAA privacy law. Your employer doesn’t need to know, and you decide what gets shared and with whom. If you need documentation for a medical leave, we can supply general paperwork without revealing details about your treatment.' ),
	array( 'What insurance do you accept?', 'We take PPO insurance from most major carriers. We are unable to accept Medicaid or Medicare. Verifying your benefits is free and confidential, and there is no obligation.' ),
	array( 'Can you treat a mental health condition at the same time?', 'Yes. Many people seeking help for addiction also live with depression, anxiety, trauma or another condition. Dual-diagnosis care treats both on one plan, because treating one without the other rarely lasts.' ),
);

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">

<!-- ================= HERO ================= -->
<section class="home-hero on-dark">
  <img class="home-hero-img" src="/assets/img/photos/group-support.webp" srcset="/assets/img/photos/group-support-900.webp 900w, /assets/img/photos/group-support.webp 1600w" sizes="100vw" width="1600" height="721" alt="" fetchpriority="high">
  <div class="wrap home-hero-inner">
    <p class="kicker">Break the cycle</p>
    <h1>Get addiction treatment today</h1>
    <ul class="hero-list">
      <li><?= dl_icon( 'check' ) ?>Modern detox protocols</li>
      <li><?= dl_icon( 'check' ) ?>Dual-diagnosis therapy</li>
      <li><?= dl_icon( 'check' ) ?>Individualized treatment</li>
      <li><?= dl_icon( 'check' ) ?>Holistic &amp; traditional care</li>
      <li><?= dl_icon( 'check' ) ?>Luxurious accommodations</li>
      <li><?= dl_icon( 'check' ) ?>Free confidential assessment</li>
    </ul>
    <div class="hero-ctas">
      <a class="btn btn-light btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-hero"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
      <a class="btn btn-ghost-light btn-lg" href="#verify" data-cta="verify-hero">Verify insurance</a>
    </div>
    <a class="pinnacle" href="https://pinnaclehealthgroup.org/" target="_blank" rel="noopener">
      <img src="/assets/img/logo/pinnacle-mark.webp" width="40" height="40" alt="">
      <span>Powered by<strong>Pinnacle Health Group</strong></span>
    </a>
  </div>
</section>

<!-- ================= TRUST ================= -->
<section class="trust" aria-label="Accreditation and access">
  <ul class="wrap trust-row">
    <li><img src="/assets/img/accreditation/joint-commission.webp" width="40" height="40" alt="">Joint Commission accredited</li>
    <li><?= dl_icon( 'shield' ) ?>Licensed by California DHCS</li>
    <li><?= dl_icon( 'clock' ) ?>Admissions answered 24/7</li>
    <li><?= dl_icon( 'lock' ) ?>Confidential &amp; HIPAA protected</li>
  </ul>
</section>

<!-- ================= NEW LIFE ================= -->
<section class="section split">
  <div class="wrap split-grid">
    <figure class="split-media frame-offset">
      <img src="/assets/img/photos/sunrise.webp" width="412" height="600" alt="A person with arms raised toward a sunrise over open fields" loading="lazy">
    </figure>
    <div class="split-copy">
      <h2 class="h-section">Experience a new life</h2>
      <p class="lede">The burden of addiction isn’t easy to carry. It’s unrelenting and punishes those it affects, and even the ones around them. We’ve seen it. We’ve experienced it.</p>
      <p>Invigorate your life and live lighter by reaching out today. Let us support you or your loved one revitalize their ambitions for a more meaningful life.</p>
      <p>Our admissions team is available now to help begin the process of finding the program best fit to your individual situation.</p>
      <p class="note"><?= dl_icon( 'lock' ) ?>All communications are confidential</p>
    </div>
  </div>
</section>

<!-- ================= PROGRAMS ================= -->
<section class="section tint">
  <div class="wrap">
    <div class="sec-head">
      <h2 class="h-section">Our programs</h2>
      <p>One clinical team and one plan, from your first night through to living it on your own.</p>
    </div>
    <div class="cards">
      <a class="card" href="/programs/#detox">
        <span class="card-ic"><?= dl_icon( 'detox' ) ?></span>
        <h3>Medical detox</h3>
        <p>Alleviate withdrawal symptoms with protocols designed for safety and comfort.</p>
        <span class="card-more">About medical detox<?= dl_icon( 'arrow' ) ?></span>
      </a>
      <a class="card" href="/programs/#residential">
        <span class="card-ic"><?= dl_icon( 'home' ) ?></span>
        <h3>Residential rehab</h3>
        <p>Address underlying roots of addictive behavior with dual-diagnosis and trauma-informed therapy living at an upscale residence.</p>
        <span class="card-more">About residential rehab<?= dl_icon( 'arrow' ) ?></span>
      </a>
      <a class="card" href="/programs/#outpatient">
        <span class="card-ic"><?= dl_icon( 'path' ) ?></span>
        <h3>Outpatient rehab</h3>
        <p>Strengthen the foundation of recovery with furthered therapy, and maintain engagement with your new support system.</p>
        <span class="card-more">About outpatient rehab<?= dl_icon( 'arrow' ) ?></span>
      </a>
    </div>
  </div>
</section>

<!-- ================= WHY US ================= -->
<section class="section split">
  <div class="wrap split-grid split-flip">
    <figure class="split-media">
      <img src="/assets/img/photos/embrace.webp" width="600" height="400" alt="Two people embracing during a support group" loading="lazy">
    </figure>
    <div class="split-copy">
      <h2 class="h-section">Why us?</h2>
      <p class="lede">Our team ensures that every caller receives the kind of care we desire for our own family and friends. Each individual is unique, and we commit to a highly personalized approach, addressing not just addiction but also any co-occurring disorders like trauma, depression, or anxiety.</p>
      <p>For the highest chance of success, we strongly believe a comprehensive recovery plan with both traditional and holistic methods is needed. And our mission here is to help as many people as we possibly can receive that standard of care.</p>
      <a class="text-link" href="/about/">More about us<?= dl_icon( 'arrow' ) ?></a>
    </div>
  </div>
</section>

<!-- ================= RESIDENCES ================= -->
<section class="section residences">
  <div class="wrap">
    <div class="sec-head">
      <h2 class="h-section">Somewhere you want to be</h2>
      <p>Private and semi-private rooms, chef-prepared meals and outdoor patios, in an upscale residence in Los Angeles.</p>
    </div>
    <div class="mosaic">
      <img class="m-a" src="/assets/img/photos/facility-exterior-760.webp" srcset="/assets/img/photos/facility-exterior-760.webp 760w, /assets/img/photos/facility-exterior.webp 1400w" sizes="(min-width: 900px) 50vw, 100vw" width="760" height="478" alt="The residence: modern white townhomes with balconies and gardens" loading="lazy">
      <img class="m-b" src="/assets/img/photos/facility-living-640.webp" width="640" height="427" alt="Living room with floor-to-ceiling windows" loading="lazy">
      <img class="m-c" src="/assets/img/photos/facility-kitchen-640.webp" width="640" height="483" alt="Kitchen and dining table" loading="lazy">
    </div>
  </div>
</section>

<!-- ================= THE CALL ================= -->
<section class="section call-band on-dark">
  <div class="wrap call-grid">
    <div>
      <h2 class="h-section">The hardest part is the phone call</h2>
      <p class="lede">Tell us what’s going on. We’ll give you a straight read on which level of care fits, what your insurance covers, and what happens next.</p>
      <a class="btn btn-light btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-band"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
    </div>
    <ol class="steps">
      <li>
        <h3>You talk, we check the coverage</h3>
        <p>A confidential assessment over the phone — what you’re using, how long, what’s been tried. We verify your benefits with your carrier while you’re on the line.</p>
      </li>
      <li>
        <h3>We match the program and arrange your arrival</h3>
        <p>You get a specific recommendation, a cost you can plan around, and a date. Same-day admission is often possible.</p>
      </li>
    </ol>
  </div>
</section>

<!-- ================= STORIES ================= -->
<section class="section stories" aria-labelledby="stories-title">
  <div class="wrap">
    <div class="stories-top">
      <div>
        <h2 class="h-section" id="stories-title">Our success stories</h2>
        <p class="summary-src"><?= dl_icon( 'spark' ) ?>AI-generated summary, based on Google reviews</p>
      </div>
      <div class="rail-btns">
        <button class="rail-btn" type="button" data-rail="prev" aria-controls="home-rail" aria-label="Previous reviews"><?= dl_icon( 'prev' ) ?></button>
        <button class="rail-btn" type="button" data-rail="next" aria-controls="home-rail" aria-label="Next reviews"><?= dl_icon( 'next' ) ?></button>
      </div>
    </div>
    <p class="summary"><?= e( dl_home_summary() ) ?></p>
    <div class="rail" id="home-rail" tabindex="0" aria-label="Google reviews — scroll for more">
      <?php foreach ( dl_full_reviews() as $r ) : ?>
      <figure class="quote">
        <?= dl_stars() ?>
        <blockquote><?php foreach ( $r['paras'] as $p ) : ?><p><?= e( $p ) ?></p><?php endforeach; ?></blockquote>
        <figcaption><?= dl_google_g( 16 ) ?><span><?= e( $r['name_home'] ?? $r['name'] ) ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= FAQ ================= -->
<section class="section tint">
  <div class="wrap faq-grid">
    <div>
      <h2 class="h-section">Questions people ask first</h2>
      <p class="faq-aside">Not covered here? <a href="<?= e( $page['tel'] ) ?>" data-cta="call-faq">Call <?= e( $page['phone'] ) ?></a> and ask us.</p>
    </div>
    <div class="faqs">
      <?php foreach ( $faqs as $i => $f ) : ?>
      <details class="faq"<?= 0 === $i ? ' open' : '' ?>>
        <summary><?= e( $f[0] ) ?></summary>
        <p><?= e( $f[1] ) ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= VERIFY ================= -->
<section class="section verify" id="verify">
  <div class="wrap verify-grid">
    <div>
      <h2 class="h-section">Obligation-free insurance verification</h2>
      <p class="lede">We take all PPO insurance. No Medicaid or Medicare.</p>
      <ul class="marks">
        <?php foreach ( dl_carrier_marks() as $c ) : ?>
        <li><img src="/assets/img/carriers/<?= e( $c['file'] ) ?>.webp" width="<?= (int) $c['w'] ?>" height="<?= (int) $c['h'] ?>" style="height:<?= (int) $c['show'] ?>px" alt="<?= e( $c['alt'] ) ?>" loading="lazy"></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php
    $form = array(
        'id'     => 'home-form',
        'title'  => 'Check my benefits',
        'sub'    => 'A specialist reviews your coverage and calls you back.',
        'button' => 'Check my benefits',
    );
    require DL_ROOT . '/partials/form.php';
    ?>
  </div>
</section>

</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
