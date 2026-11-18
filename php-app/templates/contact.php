<?php
/**
 * /contact/ — the number, the form, and where the residences are.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'Contact Us | Detox Locator';
$description = 'Call admissions 24/7 or send your insurance details for a free, confidential benefits check. Residences on N Mariposa Ave, Los Angeles.';
$css         = array( 'base.css', 'site.css' );
$body_class  = 'site inner';

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">

<section class="page-hero on-dark">
  <div class="wrap">
    <h1>Contact us</h1>
    <p class="lede">Admissions is open 24/7. Call, or send your insurance details and a specialist will call you back.</p>
  </div>
</section>

<section class="section">
  <div class="wrap contact-grid">
    <div class="contact-info">
      <div class="contact-call">
        <h2 class="h-mini">Admissions, 24/7</h2>
        <a class="contact-number" href="<?= e( $page['tel'] ) ?>" data-cta="call-contact"><?= e( $page['phone'] ) ?></a>
        <p>Confidential, and talking to us commits you to nothing.</p>
      </div>

      <div>
        <h2 class="h-mini">Our residences</h2>
        <ul class="addr-list">
          <?php foreach ( DL_ADDRESSES as $a ) : ?>
          <li><?= dl_icon( 'pin' ) ?><span><?= e( $a['line1'] ) ?><br><?= e( $a['line2'] ) ?><br><a href="<?= e( $a['map'] ) ?>" target="_blank" rel="noopener">Open in Google Maps</a></span></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <p class="crisis">In an emergency, call 911. For 24/7 crisis support, call or text <a href="tel:988">988</a>.</p>
    </div>

    <div id="verify">
      <?php
      $form = array(
          'id'     => 'contact-form',
          'title'  => 'Verify your insurance',
          'sub'    => 'No-cost, no-obligation benefits check.',
          'button' => 'Check my benefits',
      );
      require DL_ROOT . '/partials/form.php';
      ?>
    </div>
  </div>
</section>

</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
