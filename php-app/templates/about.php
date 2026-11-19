<?php
/**
 * /about/ — who runs this site, how they work, the people, the residence,
 * the credentials and the network behind it.
 *
 * The operator line is taken from the privacy policy ("Arise Treatment Homes
 * LLC, doing business as Invigorate Behavioral Health … owner and operator of
 * detox-locator.com"), so the two pages cannot tell different stories. The
 * rest is drawn from invigoratebh.com (About, Our Team, Our Facility) and
 * go.pinnaclehealthgroup.org.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'About Us | Detox Locator';
$description = 'Detox Locator is operated by Arise Treatment Homes LLC, doing business as Invigorate Behavioral Health, part of Pinnacle Health Group. Meet the team, see the Los Angeles residence, and check our accreditation.';
$css         = array( 'base.css', 'site.css' );
$preload     = array(
	'href'   => '/assets/img/photos/los-angeles.webp',
	'srcset' => '/assets/img/photos/los-angeles-900.webp 900w, /assets/img/photos/los-angeles.webp 1600w',
	'sizes'  => '100vw',
);
$body_class  = 'site inner';

$approach = array(
	array( 'compass', 'A plan written for you', 'Everyone arrives with their own history. Your plan is built around yours, and changes as you do.' ),
	array( 'brain', 'Both conditions, one team', 'Depression, anxiety and trauma are treated alongside the addiction, by the same clinicians, on the same plan.' ),
	array( 'leaf', 'Evidence-based and holistic', 'CBT, DBT, motivational interviewing and relapse prevention, alongside life skills, nutrition and mindfulness.' ),
	array( 'family', 'Family included', 'Family therapy and support for the people who love you, at whatever level of involvement you choose.' ),
);

$gallery = array(
	array( 'residence-entry', 1200, 837, 'The entrance to the residence on North Mariposa Avenue, with terraced gardens', 'g-a' ),
	array( 'residence-great-room', 1200, 800, 'Open-plan living room and kitchen with warm wood and mustard armchairs', 'g-b' ),
	array( 'residence-hall', 667, 1000, 'An arched mirror and plants in a bright hallway', 'g-c' ),
	array( 'residence-lounge', 1200, 800, 'Lounge with floor-to-ceiling windows and a balcony', 'g-d' ),
	array( 'residence-dining', 1200, 800, 'Dining table under woven pendant lights', 'g-e' ),
);

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">

<section class="about-hero on-dark">
  <img class="about-hero-img" src="/assets/img/photos/los-angeles.webp" srcset="/assets/img/photos/los-angeles-900.webp 900w, /assets/img/photos/los-angeles.webp 1600w" sizes="100vw" width="1600" height="900" alt="" fetchpriority="high">
  <div class="wrap about-hero-inner">
    <h1>About Detox Locator</h1>
    <p class="lede">Detox Locator is operated by Arise Treatment Homes LLC, doing business as Invigorate Behavioral Health, a licensed addiction treatment provider in Los Angeles and part of Pinnacle Health Group.</p>
    <ul class="about-facts">
      <li><?= dl_icon( 'shield' ) ?>Joint Commission accredited</li>
      <li><?= dl_icon( 'check-o' ) ?>DHCS licensed, <?= e( DL_LICENSE['number'] ) ?></li>
      <li><?= dl_icon( 'clock' ) ?>Admissions answered 24/7</li>
    </ul>
  </div>
</section>

<section class="section split">
  <div class="wrap split-grid">
    <figure class="split-media frame-offset">
      <img src="/assets/img/photos/embrace.webp" width="600" height="400" alt="Two people embracing during a support group" loading="lazy">
    </figure>
    <div class="split-copy">
      <h2 class="h-section">Who we are</h2>
      <p class="lede">A community of compassionate, seasoned addiction professionals: licensed medical staff, therapists, counselors and case managers, several of them in recovery themselves.</p>
      <p>We’ve seen what addiction does to families and communities. We believe that every person, no matter how far they’ve fallen, deserves a future with meaning and purpose. So our mission is simple: evidence-based, individually tailored care for every client who walks through our doors.</p>
      <p>Whether you’re starting this for yourself or supporting someone else, our team is here for every step towards reclaiming a life of vitality and purpose.</p>
    </div>
  </div>
</section>

<section class="section tint">
  <div class="wrap">
    <div class="sec-head">
      <h2 class="h-section">How we work</h2>
      <p>For the best chance of lasting recovery, we combine traditional and holistic methods in one plan.</p>
    </div>
    <ul class="approach">
      <?php foreach ( $approach as $a ) : ?>
      <li>
        <span class="card-ic"><?= dl_icon( $a[0] ) ?></span>
        <h3><?= e( $a[1] ) ?></h3>
        <p><?= e( $a[2] ) ?></p>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section" aria-labelledby="team-title">
  <div class="wrap">
    <div class="sec-head">
      <h2 class="h-section" id="team-title">The people you’ll meet</h2>
      <p>Clinicians, counselors and case managers who know this work, some from both sides of it.</p>
    </div>
    <ul class="team">
      <?php foreach ( dl_team() as $t ) : ?>
      <li>
        <img src="/assets/img/team/<?= e( $t['photo'] ) ?>.webp" width="400" height="400" alt="" loading="lazy">
        <h3><?= e( $t['name'] ) ?></h3>
        <p class="team-role"><?= e( $t['role'] ) ?><?php if ( $t['creds'] ) : ?><span><?= e( $t['creds'] ) ?></span><?php endif; ?></p>
        <p><?= e( $t['line'] ) ?></p>
      </li>
      <?php endforeach; ?>
    </ul>

    <div class="answer">
      <ul class="answer-faces">
        <?php foreach ( dl_admissions_team() as $t ) : ?>
        <li><img src="/assets/img/team/<?= e( $t['photo'] ) ?>.webp" width="400" height="400" alt="<?= e( $t['name'] ) ?>" loading="lazy"></li>
        <?php endforeach; ?>
      </ul>
      <div>
        <h3 class="h-sub">Who answers when you call</h3>
        <p>Seth, Ben and Brian are our admissions counselors. The first call is a confidential conversation, not a commitment, and more than one of them remembers making that call themselves.</p>
      </div>
      <a class="btn btn-primary btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-about-team"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
    </div>
  </div>
</section>

<section class="section tint" aria-labelledby="residence-title">
  <div class="wrap">
    <div class="sec-head">
      <h2 class="h-section" id="residence-title">Inside the residence</h2>
      <p>Two modern townhome residences on North Mariposa Avenue in Los Angeles, with private and semi-private rooms, chef-prepared meals and outdoor patios.</p>
    </div>
    <div class="gallery">
      <?php foreach ( $gallery as $g ) : ?>
      <img class="<?= e( $g[4] ) ?>" src="/assets/img/photos/<?= e( $g[0] ) ?>.webp" srcset="/assets/img/photos/<?= e( $g[0] ) ?>-<?= 'residence-hall' === $g[0] ? 400 : 640 ?>.webp <?= 'residence-hall' === $g[0] ? 400 : 640 ?>w, /assets/img/photos/<?= e( $g[0] ) ?>.webp <?= (int) $g[1] ?>w" sizes="(min-width: 900px) 40vw, 100vw" width="<?= (int) $g[1] ?>" height="<?= (int) $g[2] ?>" alt="<?= e( $g[3] ) ?>" loading="lazy">
      <?php endforeach; ?>
    </div>
    <div class="residence-meta">
      <ul class="addr-list">
        <?php foreach ( DL_ADDRESSES as $a ) : ?>
        <li><?= dl_icon( 'pin' ) ?><span><a href="<?= e( $a['map'] ) ?>" target="_blank" rel="noopener"><?= e( $a['line1'] ) ?></a><br><?= e( $a['line2'] ) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <div>
        <h3 class="h-mini">Who we treat</h3>
        <ul class="pills">
          <li>Men</li><li>Women</li><li>Adults 18+</li><li>Professionals</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section">
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
        <?= dl_legitscript_seal() ?>
        <h3>LegitScript certified</h3>
        <p>Independently verified to meet advertising and operational standards for addiction treatment.</p>
      </li>
    </ul>
  </div>
</section>

<section class="section tint">
  <div class="wrap network">
    <div class="pinnacle-block">
      <img src="/assets/img/logo/pinnacle-mark.webp" width="70" height="69" alt="" loading="lazy">
      <div>
        <h2 class="h-sub">Part of Pinnacle Health Group</h2>
        <p>Pinnacle Health Group is a network of licensed addiction treatment providers. Being part of it means one admissions team can look across more than one program to find the right fit for you.</p>
        <a class="text-link" href="https://pinnaclehealthgroup.org/" target="_blank" rel="noopener">pinnaclehealthgroup.org<?= dl_icon( 'external' ) ?></a>
      </div>
    </div>
    <ul class="network-list">
      <li>
        <h3>Invigorate Behavioral Health</h3>
        <p>Detox and residential treatment<br>553 &amp; 555 N Mariposa Ave., Los Angeles</p>
      </li>
      <li>
        <h3>Invigorate Counseling Center</h3>
        <p>Melrose<br>5723 Melrose Ave., Los Angeles</p>
      </li>
    </ul>
  </div>
</section>

<?php require DL_ROOT . '/partials/site-cta.php'; ?>

</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
