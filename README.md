# Detox Locator — standalone PHP app

The seven live pages as a plain PHP app, for a **Custom PHP** application on
Cloudways deployed from GitHub. No WordPress, no Elementor, no plugins.

These are **carbon copies**. The markup and stylesheets are not re-authored —
they are fetched from the live site and passed through, with only the changes
listed below. The one intentional design change is the homepage and policy
pages' palette, which you asked for.

---

## Pages

| URL | Source | Tracked number |
|---|---|---|
| `/` | live homepage | (888) 891-2240 |
| `/lpa-2/` | live | (888) 367-8846 |
| `/lpa-2-dkwi/` | live | (888) 367-8846 |
| `/lpa-2-dli/` | live | (888) 367-8846 |
| `/lpa-bing/` | live | (888) 701-3206 |
| `/privacy-policy/` | live | literal, not configurable — see below |
| `/hipaa-policy/` | live | literal, not configurable — see below |

---

## Layout

```
public_html/            <- this repo deploys here
├── index.php           front controller; the path picks the page
├── .htaccess           pretty URLs, security headers, denies /app/ and /pages/
├── pages/              the seven pages, mirrored. Generated — see below.
├── app/
│   ├── bootstrap.php   config loading, helpers, cache
│   ├── page.php        route table: which URL, which number
│   └── ctm.php         CallTrackingMetrics delivery
├── api/lead.php        the form's destination
├── assets/             330 mirrored files — css, js, images, fonts
├── config.sample.php   copy to private_html/config.php and fill in
└── router.dev.php      local `php -S` only; denied by .htaccess

private_html/           <- one level up; never served over HTTP
└── config.php          numbers, CTM key, IPInfo token
```

---

## Install on Cloudways

1. Create a **Custom PHP** application (not WordPress). PHP 8.0+.
2. Point it at this repo, webroot `public_html` (or `public_html/php-app` if
   you deploy the whole repo — both layouts are handled in `bootstrap.php`).
3. Move the config up out of the webroot:

   ```bash
   mv public_html/config.local.php ../private_html/config.php
   ```

   `config.local.php` is already filled in — numbers, FormReactor id and key,
   IPInfo token. It is gitignored and `.htaccess` returns 404 for it, but
   that is belt and braces: **it holds a live API key and belongs outside the
   webroot and outside the git tree**, or a redeploy that cleans the working
   tree deletes it. `bootstrap.php` looks in `private_html` first, so moving
   it changes nothing else.

   (`config.sample.php` is the committed, key-free template — leave it.)
4. Ensure `.htaccess` is honoured (Apache, or Nginx+Apache — the Cloudways
   default is fine).
5. Hit `/lpa-2/`. If the CSS is missing, the webroot is pointed one level off.

Local preview, if you have PHP:

```bash
cd php-app
php -S localhost:8000 router.dev.php
```

---

## What changed from the live pages

Per page this is about 1.2% of the bytes. Everything else is byte-identical.

**Removed** — WordPress head furniture that means nothing off WordPress: RSD
and Windows Live Writer discovery, REST API advertisements, oEmbed discovery,
RSS feed links, the shortlink, `<meta name="generator">` fingerprints (which
advertise your plugin versions to scanners), and the emoji detection polyfill.

**Changed** — three things only:

1. **The tracked number** reads from `config.php` instead of being hard-coded,
   so changing it in one place changes it in the header, hero, body and footer.
2. **The form posts to `/api/lead.php`** instead of back into WordPress.
3. **Gravity Forms' JavaScript is stripped.** It expects `admin-ajax.php` and
   the WordPress nonce lifecycle, neither of which exists here. The form's
   markup and stylesheet are untouched, so it looks exactly the same; it is a
   normal form POST now, validated server-side.

**Not changed** — every Elementor class name, every inline style block, the
Swiper carousels, the review slider, GTM, Clarity, CallTrackingMetrics, and
reCAPTCHA. Those are live services and still point at their own origins.

### The policy pages

Wording is **word-for-word identical** to the live pages — verified
mechanically, 1470 words on `/privacy-policy/` and 1600 on `/hipaa-policy/`,
zero differences.

Their phone numbers are deliberately **not** read from config. The number
appears inside the policy prose ("Calling us at (888) 891-2240 to update your
communication preferences"), and `/hipaa-policy/` carries a second, different
number. Making either configurable would mean a config edit silently rewriting
a published policy. They stay literal.

### The homepage and policy palette

The only intentional design change. The homepage and policy pages were on the
brand's teal/green variant while the landing pages are on indigo/lavender.
Mapped one onto the other:

| was | is now | where |
|---|---|---|
| `#107f81` teal | `#232452` indigo | headings, footer, dark bands |
| `#8ac20a` lime | `#2b238a` violet | buttons, check icons |
| `#faf9f4` cream | `#f4f2ff` lilac | tinted section backgrounds |
| `#fec42d` amber | `#f5c16d` amber | rating stars |
| Work Sans | Figtree | all type |

Both sides are the real values read out of the live Elementor stylesheets —
nothing is invented. Layout, spacing, copy and imagery are untouched; this is
a palette and typeface swap only.

The policy pages are included because they share the homepage's header and
footer. Recolouring one and not the others would leave the site visibly
half-rebranded. **Say if you want them left teal** — it is one line in
`tools/convert.py` (`RECOLOUR`).

Recoloured stylesheets are written as `.dl.css` siblings and only the
recoloured pages point at them, so the landing pages are provably unaffected.

---

## Making edits later

That was the point of this, so:

- **A phone number** — `private_html/config.php`. One line, no deploy.
- **A new landing variant** — add a row to `dl_routes()` in `app/page.php` and
  drop a template in `pages/`.
- **Page content** — edit `pages/<slug>.php` directly. It is ordinary HTML.
- **Re-pull from the live site** — `python tools/mirror.py && python
  tools/convert.py`. **This overwrites `pages/`**, so do your edits there only
  once you have stopped re-mirroring.

---

## Fonts — no action needed

An earlier draft of this file warned that the Adobe Fonts kit was
domain-locked. **That was wrong.** Tested directly:

```
use.typekit.net/jmp7hul.css   referer detox-locator.com -> 200, 4741 bytes
                              referer other domain      -> 200, 4741 bytes
                              no referer                -> 200, 4741 bytes
```

Byte-identical every time, and the `.woff2` files behind it serve the same way
(200, 30816 bytes, any referer). Domain enforcement on Adobe Fonts applies to
the **JavaScript** embed (`kit.js`), which checks the hosting page's domain.
This site uses the **CSS** embed, which is not gated. It will work on a
staging domain, a preview URL and production without anything being added to
the kit.

Worth knowing: that one kit serves **both** typefaces — `fields` (the display
face on the landing page headings) and `figtree` (body). So the landing pages
get Figtree from Typekit, and the recoloured homepage gets it from the
mirrored Google Fonts copy. Both resolve to the same family name, and CSS
family matching is case-insensitive, so they interoperate.

---

## Two things to set before launch

**1. The lead log.** `ctm_formreactor` and `ctm_key` are now filled in, so the
form delivers. Set `lead_log` to an absolute path inside `private_html` as
well — if CTM is ever down or misconfigured, `/api/lead.php` still shows the
visitor their confirmation, and the log is the only place that enquiry exists.

**2. Confirm the FormReactor's custom field names.** The payload uses the
field names from the POST example you supplied — `patient_name`, `insurance`,
`policy_id`, `date_of_birth`, `landing_page`, `looking_for`, `utm_source`. CTM
**silently drops** a custom field whose name is not configured on that
FormReactor, so submit one test lead and check it arrives complete before
spending on it. `utm_medium`, `utm_campaign`, `utm_content`, `utm_term` and
`consent` are also sent and will need adding in CTM if you want them kept.

### The IPInfo token

Left exactly as it is, by request. `/lpa-2-dli/` does its own lookup inline
with `511d4854a95242`, the same as the live page. The `ipinfo_token` entry in
config is unused and is only there if the lookup is ever moved server-side.

---

## A caveat on testing

There is no PHP runtime on this machine, so **the app has not been executed.**
What has been checked mechanically:

- All 14 PHP files balance braces, strings and `<?php` regions.
- **All 458 asset references across the seven pages resolve to files on disk** —
  zero missing.
- Policy page prose is word-for-word identical to the live pages.
- The recolour leaves no teal or green in any stylesheet those three pages
  load, and the four landing pages reference no recoloured file at all.

That is not a render. Deploy to a Cloudways **staging** app first. What I would
click through:

- Each of the seven URLs loads with styling intact.
- The review carousel and the insurance logo slider still animate (Swiper).
- The form submits and lands in CTM; `?submitted=1` shows the confirmation.
- `/lpa-2-dkwi/?area=Florida` still swaps the hero line.
- The homepage and policy pages are indigo, the landing pages unchanged.
