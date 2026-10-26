<?php
/**
 * Copy this to  applications/<app>/private_html/config.php  on the server and
 * fill it in. That directory is a sibling of public_html, never served by the
 * web server, and — importantly — outside the git working tree, so a redeploy
 * cannot clean it away.
 *
 * Do NOT put it inside public_html. If the webroot is pointed at a
 * subdirectory, public_html itself is a git working tree; anything untracked
 * in there is one `git clean` from gone, and losing this file means every
 * lead is silently dropped.
 *
 * For local development, copy it to  config.local.php  in this directory
 * instead — that filename is gitignored and denied by .htaccess.
 *
 * @package dl-locator
 */

return array(

	/*
	 * Tracked numbers.
	 *
	 * These are the numbers currently on the live pages. Keeping them apart
	 * is the whole point — one number across two ad platforms gives you one
	 * merged, useless attribution report.
	 *
	 *   phone       /lpa-2/, /lpa-2-dkwi/, /lpa-2-dli/
	 *   phone_home  the homepage and both policy pages
	 *   phone_bing  /lpa-bing/
	 *
	 * Changing a number here changes it everywhere on that page — header,
	 * hero button, body copy and footer all read from this.
	 */
	'phone'           => '(888) 367-8846',
	'phone_home'      => '(888) 891-2240',
	'phone_bing'      => '(888) 701-3206',

	/*
	 * CallTrackingMetrics FormReactor — where the insurance form goes now
	 * that Gravity Forms is gone.
	 *
	 * WITHOUT BOTH OF THESE THE FORM SILENTLY LOSES LEADS: /api/lead.php
	 * records `not_configured` and the visitor still sees their confirmation.
	 * Set `lead_log` below before go-live so nothing is lost either way.
	 *
	 * Get them from CTM -> the FormReactor's POST URL, where they appear as
	 * id= and key=. The id also appears in CTM's own JS embed so it is not
	 * secret; the key is, which is why this file lives outside the webroot.
	 */
	'ctm_formreactor' => '',
	'ctm_key'         => '',

	/*
	 * CTM account for the tracker script (the number in //<account>.tctm.co/t.js).
	 * Public by nature. The mirrored pages already carry 374183 inline; this
	 * is here for when the markup stops hard-coding it.
	 */
	'ctm_account'     => '374183',

	/*
	 * IPInfo token for the "Find Treatment in <state>" line on /lpa-2-dli/.
	 *
	 * The page does its own lookup inline, exactly as the live one does, so
	 * this is unused today. It is here so the lookup can be moved
	 * server-side later without a config change.
	 */
	'ipinfo_token'    => '',

	/*
	 * Strongly recommended. Appends every submission to a JSON-lines file, so
	 * an enquiry still exists somewhere if CTM is misconfigured or down.
	 *
	 * Must be a path inside private_html — it holds names, phone numbers,
	 * dates of birth and policy numbers, and would be downloadable anywhere
	 * under the webroot.
	 */
	'lead_log'        => '',
);
