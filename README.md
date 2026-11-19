# Detox Locator — standalone PHP app

detox-locator.com as a plain PHP app for a **Custom PHP** application on
Cloudways, deployed from GitHub. No WordPress, no Elementor, no plugins — the
same setup as alcohol-rehab-locator.

## Pages

| URL | Template | Number | Notes |
|---|---|---|---|
| `/` | home | `phone` (888) 367-8846 | Menu, programs, residences, reviews, FAQs, form, licensing footer |
| `/programs/` `/about/` `/contact/` | programs, about, contact | `phone` | New. Menu + licensing footer |
| `/privacy-policy/` `/hipaa-policy/` | policy | `phone` | Wording verbatim from the live pages (see below) |
| `/lpa-2/` | lpa | `phone` (888) 367-8846 | The live design, rebuilt, with the new form |
| `/lpa-2-dkwi/` | lpa | `phone` | `?area=` line, server-rendered |
| `/lpa-2-dli/` | lpa | `phone` | Visitor's state, from ipinfo.io in the browser |
| `/lpa-bing/` | lpa | `phone_bing` (888) 701-3206 | Bing copy + LegitScript seal, as live |
| `/lpc/` | lpc | `phone`, plus `phone_lpc` (866) 913-7063 on the help line | Invigorate co-brand |
| `/lpd/` `/lpd-dkwi/` `/lpd-dli/` `/lpd-bing/` | lpd | as their A twins | Same copy as A, modern layout, form in the hero |

Landing pages are `noindex, follow`, have no menu and use the short footer.
They carry a fixed header (logo, call, verify) that always shows the page's
own number, so `/lpa-bing/` can never show the main line.
The microsite pages are indexable and carry the licensing footer
(#191329BP, expires 4/30/2027). The Cloudways staging hostname is always
`noindex`.

### The line under the headline

`Find Treatment Nationwide in Minutes` by default; Bing says `Find Treatment
in Minutes`.

- **`-dkwi` (dynamic keyword insertion):** `?area=San+Diego` →
  `Find Treatment in San Diego in Minutes`. Rendered in PHP, so no flash.
  Letters, spaces, `. , ' -` only, 2–40 characters; anything else falls back
  to the nationwide line rather than printing junk in the hero. (Live blanks
  the line when `area` is missing; this keeps the nationwide line instead.)
- **`-dli` (dynamic location insertion):** the page renders the nationwide
  line, then the browser asks ipinfo.io for the visitor's region and swaps
  it in, exactly as the live page's script did, with the same public token
  (`ipinfo_token`). It has to be the browser: behind Cloudways' Nginx proxy
  a server-side lookup only ever sees the proxy's address. A blocked or
  failed lookup leaves the nationwide line.

## The form

One partial (`partials/form.php`) for every page. Labels above fields, 48px
targets, phone and date-of-birth auto-formatting, carrier suggestions, inline
errors that say how to fix them, focus moved to the first problem, a
confirmation in place with a call button. Honeypot instead of reCAPTCHA.

It posts to `/api/lead.php`, which sends it to the CTM FormReactor using the
custom field names already configured there (`patient_name`,
`date_of_birth`, `insurance`, `policy_id`, `landing_page`, `utm_*`,
`consent`) and click IDs under `paid_attribution[]`. Ad parameters are
captured on landing and travel with the lead. GTM gets `generate_lead` on a
confirmed delivery and `lead_failed` otherwise — never any personal data.
Without JavaScript it is a normal POST that comes back with `?submitted=1`.

## Layout

```
php-app/                webroot
├── index.php           front controller
├── .htaccess           pretty URLs, trailing slash, www→apex, denies includes
├── app/                routes, CTM delivery, content, icons, policies (generated)
├── templates/          one file per page family
├── partials/           head, form, headers, footers
├── api/                lead.php
├── assets/             css (inlined per page), js/app.js, img, fonts
├── config.sample.php   copy to private_html/config.php
└── router.dev.php      local `php -S` only
brand-src/              original artwork the assets are built from
tools/
├── build_assets.py     brand-src → php-app/assets (resize, webp, white logo, favicons)
└── gen_policies.py     live policy pages → app/policies.php, word-checked
```

## Deploy on Cloudways

1. Custom PHP app, **Hybrid stack** (Lightning is Nginx-only and ignores
   `.htaccess`), PHP 8.1+.
2. Deployment via Git → this repo, deployment path empty.
3. Settings → Webroot = `public_html/php-app`.
4. Config, outside the git tree:
   ```bash
   cd applications/<app>/private_html
   cp ../public_html/php-app/config.sample.php config.php
   nano config.php      # FormReactor id + key, IPInfo token, lead_log
   chmod 600 config.php
   ```
   The values from the old app are in `php-app/config.local.php` (gitignored,
   never committed) — copy them across.
5. Turn Varnish off for the app, point the domain, issue the certificate.

## Local preview

```bash
cd php-app
cp config.sample.php config.local.php   # leave ctm_key empty to avoid real leads
php -S 127.0.0.1:8000 router.dev.php
```

## Editing

- **A number:** `private_html/config.php`.
- **Copy:** the templates are plain HTML; repeated copy (reviews, carriers,
  licence, addresses, the About page team) is in `app/content.php`.
- **A new landing variant:** one line in `DL_ROUTES` (`app/routes.php`).
- **Images:** add to `brand-src/`, run `python tools/build_assets.py`.
- **Policies:** never by hand. If the live wording changes, run
  `python tools/gen_policies.py`; it refuses to write if any word differs.
