<?php
/**
 * Not found. Served with a 404 status by index.php.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$title       = 'Page not found | Detox Locator';
$description = '';
$css         = array( 'base.css', 'site.css' );
$body_class  = 'site inner';

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">
  <section class="page-hero on-dark">
    <div class="wrap">
      <h1>We couldn’t find that page</h1>
      <p class="lede">The link may be old or mistyped. If you were looking for help, the fastest way is to call — admissions answers 24/7.</p>
      <div class="hero-ctas">
        <a class="btn btn-light btn-lg" href="<?= e( $page['tel'] ) ?>" data-cta="call-404"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
        <a class="btn btn-ghost-light btn-lg" href="/">Go to the homepage</a>
      </div>
    </div>
  </section>
</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
