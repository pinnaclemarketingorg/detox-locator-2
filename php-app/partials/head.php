<?php
/**
 * Everything from <!doctype> to the skip link. Shared by every template.
 *
 * Expects:
 *   $page         from dl_resolve_page()
 *   $title        <title>
 *   $description  meta description ('' to omit)
 *   $css          stylesheets in assets/css/ to inline, in order
 *   $preload      optional: an image path to preload as the LCP element,
 *                 or array{href,srcset,sizes}
 *   $theme        optional: theme-color
 *   $body_class   optional
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$gtm     = dl_cfg( 'gtm_id' );
$clarity = dl_cfg( 'clarity_id' );
$ctm     = dl_cfg( 'ctm_account' );
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e( $title ) ?></title>
<?php if ( '' !== ( $description ?? '' ) ) : ?>
<meta name="description" content="<?= e( $description ) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e( $page['canonical'] ) ?>">
<meta name="robots" content="<?= $page['index'] ? 'index, follow' : 'noindex, follow' ?>">
<?php // Matches the top of the page: the microsite's header is white, the landing heroes are not. ?>
<meta name="theme-color" content="<?= e( $theme ?? ( str_contains( $body_class ?? '', 'site' ) ? '#ffffff' : '#232452' ) ) ?>">
<meta name="format-detection" content="telephone=no">

<link rel="icon" href="/assets/img/favicon/favicon.ico" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32.png">
<link rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicon/favicon-192.png">
<link rel="apple-touch-icon" href="/assets/img/favicon/apple-touch-icon.png">

<?php if ( '' !== $ctm ) : ?>
<!-- CallTrackingMetrics, loaded directly and early: dynamic number insertion
     needs it before the numbers paint, and window.__ctm has to exist before
     the form can attach the visitor session. t.js checks readyState and
     self-starts, so async is safe. Do not route it through GTM. -->
<link rel="preconnect" href="https://<?= e( $ctm ) ?>.tctm.co">
<script async src="//<?= e( $ctm ) ?>.tctm.co/t.js"></script>
<?php endif; ?>

<!-- "fields", the display serif, is an Adobe Fonts kit. Its CSS embed is not
     domain-locked (only the JS embed is), so it works on staging too. Loaded
     without blocking render; headings paint in the fallback and swap. -->
<link rel="preconnect" href="https://use.typekit.net" crossorigin>
<link rel="preconnect" href="https://p.typekit.net" crossorigin>
<link rel="stylesheet" href="https://use.typekit.net/jmp7hul.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://use.typekit.net/jmp7hul.css"></noscript>

<!-- Figtree, self-hosted: body copy is the first text to paint. -->
<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/figtree-latin.woff2" crossorigin>
<?php if ( ! empty( $preload ) ) : ?>
<?php if ( is_array( $preload ) ) : ?>
<link rel="preload" as="image" href="<?= e( $preload['href'] ) ?>" imagesrcset="<?= e( $preload['srcset'] ) ?>" imagesizes="<?= e( $preload['sizes'] ) ?>" fetchpriority="high">
<?php else : ?>
<link rel="preload" as="image" href="<?= e( $preload ) ?>" fetchpriority="high">
<?php endif; ?>
<?php endif; ?>

<style><?= dl_inline_css( ...$css ) ?></style>

<?php if ( '' !== $gtm ) : ?>
<!-- Google Tag Manager, fetched on window load. dataLayer exists immediately,
     so anything pushed before GTM arrives (generate_lead included) queues and
     is processed when it does — only the download waits. -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
function go(){var f=d.getElementsByTagName(s)[0],j=d.createElement(s);j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i;f.parentNode.insertBefore(j,f);}
if(d.readyState==='complete'){go();}else{w.addEventListener('load',go);}})(window,document,'script','dataLayer',<?= json_encode( $gtm ) ?>);</script>
<?php endif; ?>
<?php if ( '' !== $clarity ) : ?>
<!-- Microsoft Clarity, also after load. -->
<script>(function(c,l,a,r,i){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
function go(){var t=l.createElement(r);t.async=1;t.src='https://www.clarity.ms/tag/'+i;var y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);}
if(l.readyState==='complete'){go();}else{c.addEventListener('load',go);}})(window,document,'clarity','script',<?= json_encode( $clarity ) ?>);</script>
<?php endif; ?>
<?php if ( ! empty( $page['geo'] ) && '' !== dl_cfg( 'ipinfo_token' ) ) : ?>
<link rel="preconnect" href="https://ipinfo.io" crossorigin>
<?php endif; ?>
<?php /* Entrance animations hide their elements only under html.js. If
         app.js has not run 3s in, everything is shown regardless. */ ?>
<script>document.documentElement.classList.add('js');setTimeout(function(){if(!window.dlReady)document.documentElement.classList.add('no-reveal');},3000);</script>
<script defer src="<?= e( dl_asset( 'js/app.js' ) ) ?>"></script>
</head>
<body class="<?= e( $body_class ?? '' ) ?>">
<?php if ( '' !== $gtm ) : ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= e( $gtm ) ?>" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
<?php endif; ?>
<a class="skip-link" href="#main">Skip to content</a>
