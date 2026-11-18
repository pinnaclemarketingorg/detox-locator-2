<?php
/**
 * /programs/ — the three levels of care in more depth, then what is treated
 * and how. Built from copy already published on the homepage, /lpc/ and
 * Pinnacle Health Group's pages; nothing here is a new clinical claim.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'Programs: Medical Detox, Residential & Outpatient Rehab | Detox Locator';
$description = 'Medically-supervised detox, residential rehab at an upscale Los Angeles residence, and outpatient care — with dual-diagnosis therapy throughout.';
$css         = array( 'base.css', 'site.css' );
$body_class  = 'site inner';

$levels = array(
	array(
		'id'    => 'detox',
		'name'  => 'Medical detox',
		'span'  => 'Typically 7–10 days',
		'lede'  => 'Alleviate withdrawal symptoms with protocols designed for safety and comfort.',
		'body'  => 'Medically supervised withdrawal, with medication to keep you safe and comfortable and nursing staff monitoring you around the clock. The only job in detox is getting through it safely.',
		'items' => array( 'An assessment when you arrive, so your plan fits your substance, your use and your health', 'Nursing checks day and night', 'Medication-assisted treatment to ease withdrawal', 'A planned next step into residential care, with no gap' ),
		'img'   => array( 'facility-living-640', 'facility-living', 640, 427, 'A calm living room with floor-to-ceiling windows', 1200 ),
	),
	array(
		'id'    => 'residential',
		'name'  => 'Residential rehab',
		'span'  => '28+ days',
		'lede'  => 'Address underlying roots of addictive behavior with dual-diagnosis and trauma-informed therapy living at an upscale residence.',
		'body'  => 'Private and semi-private rooms in an upscale, low-density residence, with a therapy plan built around you and support on hand at any hour. Master’s-level licensed therapists and board-certified doctors lead daily one-to-one sessions alongside group work.',
		'items' => array( 'Daily 1:1 therapy, plus group and family sessions', 'Dual-diagnosis care for depression, anxiety, trauma and more', 'Chef-prepared meals, daily housekeeping and outdoor patios', 'Weekend outings, fitness equipment and mindfulness practice' ),
		'img'   => array( 'facility-exterior-760', 'facility-exterior', 760, 478, 'The residence: modern townhomes with balconies and gardens', 1400 ),
	),
	array(
		'id'    => 'outpatient',
		'name'  => 'Outpatient rehab',
		'span'  => 'Flexible',
		'lede'  => 'Strengthen the foundation of recovery with furthered therapy, and maintain engagement with your new support system.',
		'body'  => 'Structured therapy that works around a job, a family and the rest of your life — the step that turns 30 days of progress into a year of it.',
		'items' => array( 'Scheduled individual and group sessions', 'Relapse-prevention planning', 'Case management, discharge planning and aftercare support', 'Alumni programming that keeps you connected' ),
		'img'   => array( 'group-support-900', 'group-support', 900, 405, 'A support group in conversation', 1600 ),
	),
);

$treat = array( 'Alcohol addiction', 'Drug addiction', 'Mental health disorders', 'Co-occurring disorders', 'Impulse control disorders', 'Trauma' );
$therapies = array( 'Medical detox', 'Medication-assisted treatment', '1-on-1 counseling', 'Dual-diagnosis treatment', 'Meditation & mindfulness', 'CBT', 'DBT', 'Twelve step facilitation', 'Family and group therapy', 'Alumni programming', 'Case management', 'Discharge planning', 'Aftercare support' );

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">

<section class="page-hero on-dark">
  <div class="wrap">
    <h1>Our programs</h1>
    <p class="lede">A full continuum of care that doesn’t stop after detox: medically-supervised withdrawal, residential treatment and outpatient support, with dual-diagnosis care throughout.</p>
    <nav class="jump" aria-label="Programs on this page">
      <?php foreach ( $levels as $l ) : ?><a href="#<?= e( $l['id'] ) ?>"><?= e( $l['name'] ) ?></a><?php endforeach; ?>
    </nav>
  </div>
</section>

<?php foreach ( $levels as $i => $l ) : ?>
<section class="section level<?= 1 === $i ? ' tint' : '' ?>" id="<?= e( $l['id'] ) ?>">
  <div class="wrap split-grid<?= 1 === $i ? ' split-flip' : '' ?>">
    <figure class="split-media">
      <img src="/assets/img/photos/<?= e( $l['img'][0] ) ?>.webp" srcset="/assets/img/photos/<?= e( $l['img'][0] ) ?>.webp <?= (int) $l['img'][2] ?>w, /assets/img/photos/<?= e( $l['img'][1] ) ?>.webp <?= (int) $l['img'][5] ?>w" sizes="(min-width: 900px) 45vw, 100vw" width="<?= (int) $l['img'][2] ?>" height="<?= (int) $l['img'][3] ?>" alt="<?= e( $l['img'][4] ) ?>" loading="<?= 0 === $i ? 'eager' : 'lazy' ?>">
    </figure>
    <div class="split-copy">
      <p class="level-span"><?= dl_icon( 'clock' ) ?><?= e( $l['span'] ) ?></p>
      <h2 class="h-section"><?= e( $l['name'] ) ?></h2>
      <p class="lede"><?= e( $l['lede'] ) ?></p>
      <p><?= e( $l['body'] ) ?></p>
      <ul class="ticks">
        <?php foreach ( $l['items'] as $item ) : ?><li><?= dl_icon( 'check' ) ?><?= e( $item ) ?></li><?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
<?php endforeach; ?>

<section class="section">
  <div class="wrap lists">
    <div>
      <h2 class="h-sub">What we treat</h2>
      <ul class="ticks">
        <?php foreach ( $treat as $t ) : ?><li><?= dl_icon( 'check' ) ?><?= e( $t ) ?></li><?php endforeach; ?>
      </ul>
      <p class="aside">Withdrawal from alcohol and from benzodiazepines can be medically dangerous to attempt alone. If either is what you’re stopping, start with a call rather than a plan.</p>
    </div>
    <div>
      <h2 class="h-sub">Services and therapies</h2>
      <ul class="ticks ticks-2">
        <?php foreach ( $therapies as $t ) : ?><li><?= dl_icon( 'check' ) ?><?= e( $t ) ?></li><?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<?php require DL_ROOT . '/partials/site-cta.php'; ?>

</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
