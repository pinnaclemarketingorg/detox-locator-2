<?php
/**
 * The benefits form. One partial for every page, so the fields, validation
 * and delivery cannot drift between families; each template styles the card
 * around it.
 *
 * Expects $page, $submitted, and $form:
 *   id       unique per page (two forms can share a page)
 *   title    card heading
 *   sub      one line under it ('' to omit)
 *   button   submit label
 *   heading  heading tag, default h2
 *   class    extra classes on the card
 *
 * Without JavaScript this is a normal POST to /api/lead.php, which redirects
 * back with ?submitted=1. With it, app.js validates inline, posts JSON, and
 * swaps in the confirmation without leaving the page.
 *
 * @package dl-locator
 */

defined( 'DL_ROOT' ) || exit;

$fid     = $form['id'];
$tag     = $form['heading'] ?? 'h2';
$done    = ! empty( $submitted );
$options = dl_insurance_options();

// No-JS attribution: copy the ad parameters off this request into the post.
// With JS, app.js sends its landing-time snapshot instead.
$ad_keys = array( 'gclid', 'gbraid', 'wbraid', 'msclkid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'campaignid', 'adgroupid', 'creative' );
?>
<div class="lead <?= e( $form['class'] ?? '' ) ?>" id="<?= e( $fid ) ?>-card">
  <div class="lead-head">
    <<?= $tag ?> class="lead-title" id="<?= e( $fid ) ?>-title"><?= e( $form['title'] ) ?></<?= $tag ?>>
    <?php if ( '' !== ( $form['sub'] ?? '' ) ) : ?><p class="lead-sub"><?= e( $form['sub'] ) ?></p><?php endif; ?>
  </div>

  <form class="lead-form" id="<?= e( $fid ) ?>" action="/api/lead.php" method="post" novalidate aria-labelledby="<?= e( $fid ) ?>-title" data-lead<?= $done ? ' hidden' : '' ?>>
    <input type="hidden" name="page" value="<?= e( $page['slug'] ) ?>">
    <?php foreach ( $ad_keys as $k ) : if ( isset( $_GET[ $k ] ) && is_string( $_GET[ $k ] ) ) : ?>
    <input type="hidden" name="<?= e( $k ) ?>" value="<?= e( dl_truncate( $_GET[ $k ], 300 ) ) ?>">
    <?php endif; endforeach; ?>

    <!-- Honeypot. Off-screen and out of the accessibility tree; people never
         fill it, naive bots do. -->
    <div class="hp" aria-hidden="true"><label for="<?= e( $fid ) ?>-company">Company</label><input id="<?= e( $fid ) ?>-company" name="company" tabindex="-1" autocomplete="off"></div>

    <div class="lead-grid">
      <div class="field">
        <label for="<?= e( $fid ) ?>-first">First name</label>
        <input id="<?= e( $fid ) ?>-first" name="first" autocomplete="given-name" required aria-describedby="<?= e( $fid ) ?>-first-err">
        <p class="field-err" id="<?= e( $fid ) ?>-first-err"></p>
      </div>
      <div class="field">
        <label for="<?= e( $fid ) ?>-last">Last name</label>
        <input id="<?= e( $fid ) ?>-last" name="last" autocomplete="family-name" required aria-describedby="<?= e( $fid ) ?>-last-err">
        <p class="field-err" id="<?= e( $fid ) ?>-last-err"></p>
      </div>
      <div class="field">
        <label for="<?= e( $fid ) ?>-phone">Phone</label>
        <input id="<?= e( $fid ) ?>-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel-national" placeholder="(555) 555-5555" required data-mask="phone" aria-describedby="<?= e( $fid ) ?>-phone-err">
        <p class="field-err" id="<?= e( $fid ) ?>-phone-err"></p>
      </div>
      <div class="field">
        <label for="<?= e( $fid ) ?>-dob">Date of birth</label>
        <input id="<?= e( $fid ) ?>-dob" name="dob" inputmode="numeric" autocomplete="bday" placeholder="MM/DD/YYYY" maxlength="10" required data-mask="date" aria-describedby="<?= e( $fid ) ?>-dob-err">
        <p class="field-err" id="<?= e( $fid ) ?>-dob-err"></p>
      </div>
      <div class="field field-wide">
        <label for="<?= e( $fid ) ?>-ins">Insurance provider</label>
        <input id="<?= e( $fid ) ?>-ins" name="insurance" list="<?= e( $fid ) ?>-carriers" autocomplete="off" placeholder="Start typing, e.g. Aetna…" required aria-describedby="<?= e( $fid ) ?>-ins-err">
        <datalist id="<?= e( $fid ) ?>-carriers">
          <?php foreach ( $options as $o ) : ?><option value="<?= e( $o ) ?>"></option><?php endforeach; ?>
        </datalist>
        <p class="field-err" id="<?= e( $fid ) ?>-ins-err"></p>
      </div>
      <div class="field field-wide">
        <label for="<?= e( $fid ) ?>-pol">Member ID / policy number</label>
        <input id="<?= e( $fid ) ?>-pol" name="policy" autocomplete="off" spellcheck="false" autocapitalize="characters" required aria-describedby="<?= e( $fid ) ?>-pol-hint <?= e( $fid ) ?>-pol-err">
        <p class="field-hint" id="<?= e( $fid ) ?>-pol-hint">It’s on the front of your insurance card.</p>
        <p class="field-err" id="<?= e( $fid ) ?>-pol-err"></p>
      </div>
    </div>

    <div class="consent">
      <input id="<?= e( $fid ) ?>-consent" name="consent" type="checkbox" value="1" required aria-describedby="<?= e( $fid ) ?>-consent-err">
      <label for="<?= e( $fid ) ?>-consent">I agree to the <a href="/privacy-policy/">privacy policy</a> and communications regarding treatment.</label>
      <p class="field-err" id="<?= e( $fid ) ?>-consent-err"></p>
    </div>

    <button class="btn btn-submit" type="submit">
      <span class="btn-label"><?= e( $form['button'] ) ?></span>
      <span class="spinner" aria-hidden="true"></span>
    </button>

    <ul class="lead-assure">
      <li><?= dl_icon( 'lock' ) ?>HIPAA-protected</li>
      <li><?= dl_icon( 'check-o' ) ?>No cost, no obligation</li>
    </ul>
    <p class="sr-only" role="status" aria-live="polite" data-status></p>
  </form>

  <div class="lead-done" data-done tabindex="-1"<?= $done ? '' : ' hidden' ?>>
    <span class="lead-done-ic"><?= dl_icon( 'check' ) ?></span>
    <p class="lead-done-title" data-done-title>Thank you — we’ve got your details.</p>
    <p>An admissions specialist will call you shortly to go over your benefits. Prefer to talk now?</p>
    <a class="btn btn-call" href="<?= e( $page['tel'] ) ?>" data-cta="call-after-form"><?= dl_icon( 'phone' ) ?>Call <?= e( $page['phone'] ) ?></a>
  </div>
</div>
