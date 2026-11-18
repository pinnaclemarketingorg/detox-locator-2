<?php
/**
 * /privacy-policy/ and /hipaa-policy/. Layout only — the wording comes from
 * app/policies.php, lifted verbatim from the published pages.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

require_once DL_ROOT . '/app/policies.php';

$doc         = dl_policies()[ $page['slug'] ];
$title       = $doc['title'] . ' | Detox Locator';
$description = 'privacy-policy' === $page['slug']
	? 'How detox-locator.com collects, uses, maintains and discloses information collected from users of the site.'
	: 'HIPAA Notice of Privacy Practices for Arise Treatment Homes, LLC.';
$css         = array( 'base.css', 'site.css' );
$body_class  = 'site inner';

// The HIPAA notice carries its own <h1>; the page title then steps aside.
$doc_has_h1 = str_contains( $doc['html'], '<h1>' );

require DL_ROOT . '/partials/head.php';
require DL_ROOT . '/partials/site-header.php';
?>
<main id="main">
  <section class="page-hero page-hero-sm on-dark">
    <div class="wrap">
      <?php if ( $doc_has_h1 ) : ?>
      <p class="page-hero-title"><?= e( $doc['title'] ) ?></p>
      <?php else : ?>
      <h1><?= e( $doc['title'] ) ?></h1>
      <?php endif; ?>
    </div>
  </section>

  <div class="wrap">
    <article class="prose">
      <?= $doc['html'] /* vetted legal copy; see app/policies.php */ ?>
    </article>
  </div>
</main>
<?php require DL_ROOT . '/partials/site-footer.php'; ?>
</body>
</html>
