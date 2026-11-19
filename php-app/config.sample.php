<?php
/**
 * Copy this to  applications/<app>/private_html/config.php  on the server and
 * fill it in. That directory is a sibling of public_html, never served by the
 * web server, and outside the git working tree, so a redeploy cannot clean it
 * away. Never put it inside public_html.
 *
 * For local development, copy it to  config.local.php  in this directory
 * instead — that filename is gitignored and denied by .htaccess.
 *
 * Anything left out falls back to the default in app/bootstrap.php.
 *
 * @package dl-locator
 */

return array(

	/*
	 * Tracked numbers. Changing one here changes it everywhere on the pages
	 * that use it — header, buttons, body copy and footer.
	 *
	 *   phone       every page except the Bing ones: the homepage, /programs/,
	 *               /about/, /contact/, the policies, /lpa-2/, /lpa-2-dkwi/,
	 *               /lpa-2-dli/, /lpd/, /lpd-dkwi/, /lpd-dli/, and /lpc/'s
	 *               header and footer
	 *   phone_bing  /lpa-bing/, /lpd-bing/
	 *   phone_lpc   /lpc/'s Invigorate help line
	 *
	 * The policy pages' own wording contains numbers too; those are part of
	 * the legal copy and do NOT change from here.
	 */
	'phone'           => '(888) 367-8846',
	'phone_bing'      => '(888) 701-3206',
	'phone_lpc'       => '(866) 913-7063',

	/*
	 * CallTrackingMetrics FormReactor.
	 *
	 * WITHOUT BOTH OF THESE THE FORM SILENTLY LOSES LEADS: /api/lead.php logs
	 * `not_configured` and the visitor still sees their confirmation. From
	 * CTM -> the FormReactor's POST URL: the id is the path segment, the key
	 * is the key= parameter. The key is secret.
	 */
	'ctm_formreactor' => '',
	'ctm_key'         => '',

	/* CTM account for t.js (//<account>.tctm.co/t.js). Public. */
	'ctm_account'     => '374183',

	/* Google Tag Manager and Microsoft Clarity, as on the live site. Set
	   either to '' to leave it off. */
	'gtm_id'          => 'GTM-T6QJLCFS',
	'clarity_id'      => 'q0ni5lkall',

	/*
	 * IPInfo token for the -dli pages' "Find Treatment in <state> in Minutes".
	 * The visitor's browser calls ipinfo.io with it, as the live page did, so
	 * it is public. Defaults to the live page's token; '' turns the lookup
	 * off and those pages keep the nationwide line.
	 */
	'ipinfo_token'    => '511d4854a95242',

	/*
	 * Strongly recommended. Every submission is appended here as JSON lines,
	 * so an enquiry still exists if CTM is misconfigured or down. Must be an
	 * absolute path inside private_html — it holds names, phone numbers,
	 * dates of birth and policy numbers.
	 *   e.g. /home/master/applications/<app>/private_html/leads.jsonl
	 */
	'lead_log'        => '',
);
