"""Turn the mirrored pages into the PHP app's templates.

Deliberately conservative. The markup is a carbon copy and stays that way —
this only removes what cannot work without WordPress, and parameterises the
two things that differ per page (the tracked number, the form's action).

Everything else, including every Elementor class name, every inline style
block and every script, is passed through untouched.

Run:  python tools/convert.py
"""
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
RAW = os.path.join(ROOT, "_source", "mirror-raw")
PAGES_OUT = os.path.join(ROOT, "php-app", "pages")

# slug -> (config key for the number, the literal number on the live page)
#
# Only the number listed here is ever swapped. That matters on
# /hipaa-policy/, which also prints (949) 678-6047 inside the policy text —
# matching on the literal rather than a phone-shaped regex is what keeps that
# one untouched.
PAGES = {
    "home":           ("phone_home", "(888) 891-2240", "8888912240"),
    "lpa-2":          ("phone",      "(888) 367-8846", "8883678846"),
    "lpa-2-dkwi":     ("phone",      "(888) 367-8846", "8883678846"),
    "lpa-2-dli":      ("phone",      "(888) 367-8846", "8883678846"),
    "lpa-bing":       ("phone_bing", "(888) 701-3206", "8887013206"),

    # The policy pages are deliberately NOT parameterised.
    #
    # Their number appears inside the policy text itself ("Calling us at
    # (888) 891-2240 to update your communication preferences"), and
    # /hipaa-policy/ prints a second, different number. Making either of those
    # read from config would mean a config edit silently rewrites a published
    # policy — so the wording stays literal, exactly as it is on the live
    # site. They are not ad destinations and do not need a swappable number.
    "privacy-policy": None,
    "hipaa-policy":   None,
}

# Pages that get the brand recolour below. The policy pages are included
# because they share the homepage's header and footer — recolouring one and
# not the others would leave the site visibly half-rebranded.
RECOLOUR = ("home", "privacy-policy", "hipaa-policy")

# ---------------------------------------------------------------------------
# Homepage recolour
#
# The homepage and policy pages are built on the brand's teal/green variant;
# the landing pages are on the indigo/lavender one. This maps the former onto
# the latter so the whole site reads as one brand.
#
# Colours on both sides are the real values, read out of the live Elementor
# stylesheets — nothing here is invented:
#
#   teal   #107f81  ->  indigo  #232452   (headings, footer, dark bands)
#   lime   #8ac20a  ->  violet  #2b238a   (buttons, check icons)
#   cream  #faf9f4  ->  lilac   #f4f2ff   (tinted section backgrounds)
#   amber  #fec42d  ->  amber   #f5c16d   (rating stars)
#
# Layout, spacing, copy and imagery are untouched. This is only a palette and
# typeface swap.
# ---------------------------------------------------------------------------
COLOUR_MAP = {
    "#107f81": "#232452",
    "#8ac20a": "#2b238a",
    "#8ac408": "#2b238a",   # the same lime, as spelled in the logo artwork
    "#faf9f4": "#f4f2ff",
    "#fec42d": "#f5c16d",
}

# Work Sans -> Figtree, which is what the landing pages already use.
FONT_MAP = {
    '"Work Sans"': '"Figtree"',
    "'Work Sans'": "'Figtree'",
    "Work Sans": "Figtree",
}

# Which stylesheets get repainted is decided by content, not by filename:
# any sheet the page loads that actually contains one of the mapped colours.
# A filename list silently misses the per-page sheets (post-3, post-155) and
# the shared widget stylesheet, which is exactly what happened first time.
#
# Recoloured sheets are written as `.dl.css` siblings and only the recoloured
# pages are pointed at them, so a sheet shared with the landing pages — which
# must stay as they are — is never affected.

CSS_DIR = os.path.join(
    ROOT, "php-app", "assets", "wp-content", "cache", "breeze-minification", "css"
)

# --- things WordPress emits that mean nothing off WordPress ---------------
STRIP_PATTERNS = [
    # RSD / Windows Live Writer discovery.
    (r'<link[^>]+rel=["\'](?:EditURI|wlwmanifest)["\'][^>]*>\s*', "rsd/wlw"),
    # REST API advertisements.
    (r'<link[^>]+rel=["\']https://api\.w\.org/["\'][^>]*>\s*', "rest root"),
    (r'<link[^>]+rel=["\']alternate["\'][^>]+wp-json[^>]*>\s*', "rest alternate"),
    # oEmbed discovery.
    (r'<link[^>]+rel=["\']alternate["\'][^>]+oembed[^>]*>\s*', "oembed"),
    # Feeds — there is no blog here.
    (r'<link[^>]+rel=["\']alternate["\'][^>]+type=["\']application/rss\+xml["\'][^>]*>\s*', "feeds"),
    # Shortlink (?p=208).
    (r'<link[^>]+rel=["\']shortlink["\'][^>]*>\s*', "shortlink"),
    # Generator fingerprints — these advertise plugin versions to scanners.
    (r'<meta[^>]+name=["\']generator["\'][^>]*>\s*', "generator"),
    # Emoji detection + polyfill. ~12KB of script for nothing.
    (r'<script[^>]*>\s*/\*\s*<!\[CDATA\[\s*\*/\s*window\._wpemojiSettings.*?</script>\s*', "emoji settings"),
    (r'<style[^>]*>\s*img\.wp-smiley.*?</style>\s*', "emoji styles"),
    # Gravity Forms' own JS: it expects admin-ajax and the WP nonce lifecycle.
    # The markup and stylesheet stay, so the form looks identical; submission
    # is handled by /api/lead.php instead.
    (r'<script[^>]+src=["\'][^"\']*(?:gravityforms|gravityformsrecaptcha)[^"\']*["\'][^>]*></script>\s*', "gravity js"),
    (r'<script[^>]*>\s*/\*\s*<!\[CDATA\[\s*\*/\s*(?:gform\.|window\.gform).*?</script>\s*', "gform inline"),
]


def strip_wp(html):
    """Remove WordPress-only head furniture. Returns (html, report)."""
    report = []
    for pattern, label in STRIP_PATTERNS:
        html, n = re.subn(pattern, "", html, flags=re.S | re.I)
        if n:
            report.append("%s x%d" % (label, n))
    return html, report


def rewrite_links(html):
    """Internal page links become root-relative; the PHP app serves them."""
    html = re.sub(r'https://detox-locator\.com/(?=["\'])', "/", html)
    html = re.sub(r'https://detox-locator\.com/(?=[a-z0-9-]+/)', "/", html)
    return html


def parameterise_phone(html, display, digits):
    """Swap the hard-coded number for the config value.

    Done with explicit literals rather than a loose phone regex so the
    Gravity Forms input mask placeholder — (999) 999-9999 — is not caught.
    """
    count = 0

    html, n = re.subn(re.escape("tel:" + digits), "<?= e( dl_phone_href() ) ?>", html)
    count += n
    html, n = re.subn(re.escape(display), "<?= e( dl_phone() ) ?>", html)
    count += n

    # Some links render the number with no punctuation in the href only.
    html, n = re.subn(r'tel:\+?1?' + digits, "<?= e( dl_phone_href() ) ?>", html)
    count += n

    return html, count


def rewrite_form(html):
    """Point the form at our endpoint and drop WordPress-only hidden fields.

    The visible fields, their names and every class name are left exactly as
    they are, so the rendered form is unchanged and /api/lead.php receives
    the same input_* keys Gravity used.
    """
    report = []

    html, n = re.subn(
        r"(<form[^>]*id='gform_1'[^>]*action=')[^']*(')",
        r"\g<1>/api/lead.php\g<2>",
        html,
    )
    report.append("form action x%d" % n)

    # Server-side state Gravity signs and verifies. Meaningless to us, and
    # the signed blob is large.
    for name in ("gform_submission_method", "gform_theme", "gform_style_settings",
                 "gform_currency", "gform_unique_id", "state_1",
                 "gform_target_page_number_1", "gform_source_page_number_1",
                 "gform_field_values", "is_submit_1", "gform_submit"):
        html, n = re.subn(
            r"<input[^>]+name=['\"]" + re.escape(name) + r"['\"][^>]*/?>\s*",
            "",
            html,
        )
        if n:
            report.append("%s x%d" % (name, n))

    # Gravity's submit button calls its own JS, which is now gone.
    html, n = re.subn(r"\s*onclick='gform\.submission\.handleButtonClick\(this\);'", "", html)
    if n:
        report.append("submit onclick x%d" % n)

    # Carry the landing page through so the lead is attributable.
    html = html.replace(
        "</form>",
        "<input type='hidden' name='dl_page' value='<?= e( dl_slug() ) ?>' />"
        "<input type='hidden' name='dl_hp' value='' autocomplete='off' tabindex='-1' "
        "aria-hidden='true' style='position:absolute;left:-9999px' /></form>",
        1,
    )
    report.append("added dl_page + honeypot")

    return html, report


def add_confirmation(html):
    """Show Gravity's confirmation markup after a successful post.

    Same class name Gravity uses, so the existing stylesheet already styles
    it and the page looks the way it does today after a submission.
    """
    marker = "<form method='post'"
    confirm = (
        "<?php if ( dl_submitted() ) : ?>\n"
        "<div class='gform_confirmation_wrapper gform_confirmation_message' id='gform_confirmation_message_1'>"
        "Thank you for contacting us. An admissions specialist will call you shortly."
        "</div>\n"
        "<?php else : ?>\n"
    )
    if marker in html:
        html = html.replace(marker, confirm + marker, 1)
        html = html.replace("</form>", "</form>\n<?php endif; ?>", 1)
    return html


def recolour(slug, html):
    """Repaint this page's stylesheets in the landing pages' palette.

    Writes a `.dl.css` sibling next to each affected stylesheet and points the
    page at it, rather than editing the mirrored file in place — so re-running
    mirror.py cannot silently undo the rebrand, and the original is always
    there to diff against.

    Returns (html, report).
    """
    report = []

    for link in sorted(set(re.findall(r'/assets/[^"\']+?\.css', html))):
        name = os.path.basename(link)
        if name.endswith(".dl.css"):
            continue

        # Never rewrite a webfont sheet. Renaming the family inside it would
        # declare @font-face{font-family:Figtree} over Work Sans' own files —
        # the text would still render in Work Sans, and the fake declaration
        # would shadow the real Figtree. The link swap below handles these.
        if "google-fonts-css-" in name:
            continue

        source = os.path.join(CSS_DIR, name)
        if not os.path.isfile(source):
            continue

        css = open(source, encoding="utf-8", errors="ignore").read()

        # Content decides, not the filename.
        if not any(colour in css.lower() for colour in COLOUR_MAP):
            if "Work Sans" not in css:
                continue

        hits = 0

        for old, new in COLOUR_MAP.items():
            # Case-insensitive: the minifier is not consistent about it.
            css, n = re.subn(re.escape(old), new, css, flags=re.I)
            hits += n

        for old, new in FONT_MAP.items():
            css, n = re.subn(re.escape(old), new, css)
            hits += n

        if not hits:
            continue

        out_name = name[:-4] + ".dl.css"
        with open(os.path.join(CSS_DIR, out_name), "w", encoding="utf-8", newline="\n") as fh:
            fh.write(css)

        html = html.replace(link, link[: -len(name)] + out_name)
        report.append("%s (%d swaps)" % (out_name.split("-css-")[-1], hits))

    # The font file itself: serve Figtree where Work Sans was loaded.
    #
    # Breeze prefixes every cached filename with the page it first minified
    # for, so Figtree only exists on disk under the landing pages' names.
    # Point at that actual file rather than deriving a name from the Work
    # Sans one — the derived name does not exist, which is how this was
    # caught.
    figtree = None
    for candidate in sorted(os.listdir(CSS_DIR)):
        if "google-fonts-css-figtree-" in candidate and not candidate.endswith(".dl.css"):
            figtree = candidate
            break

    if figtree:
        html, n = re.subn(
            r'/assets/(?P<dir>[^"\']*?/)[^"\'/]*google-fonts-css-worksans-[0-9a-f]+\.css',
            lambda m: "/assets/" + m.group("dir") + figtree,
            html,
        )
        if n:
            report.append("Work Sans -> %s x%d" % (figtree.split("-css-")[-1], n))

    return html, report


def convert(slug):
    src = os.path.join(RAW, slug + ".mirrored.html")
    html = open(src, encoding="utf-8", errors="ignore").read()
    before = len(html)

    html, stripped = strip_wp(html)
    html = rewrite_links(html)

    recolour_report = []
    if slug in RECOLOUR:
        html, recolour_report = recolour(slug, html)

    spec = PAGES[slug]
    if spec is None:
        phone_n = 0
    else:
        _key, display, digits = spec
        html, phone_n = parameterise_phone(html, display, digits)

    html, form_report = rewrite_form(html)
    html = add_confirmation(html)

    header = (
        "<?php\n"
        "/**\n"
        " * %s — carbon copy of https://detox-locator.com%s\n"
        " *\n"
        " * Mirrored from the live page, not re-authored. The only changes are\n"
        " * the ones listed in tools/convert.py: WordPress head furniture removed,\n"
        " * the tracked number read from config, and the form posting to\n"
        " * /api/lead.php instead of back into WordPress.\n"
        " *\n"
        " * Regenerate with:  python tools/mirror.py && python tools/convert.py\n"
        " * Edit the page here; it will be overwritten if you re-run the tools.\n"
        " */\n"
        "defined( 'DL_APP' ) || exit;\n"
        "?>\n"
    ) % (slug, "/" if slug == "home" else "/" + slug + "/")

    out = os.path.join(PAGES_OUT, slug + ".php")
    os.makedirs(PAGES_OUT, exist_ok=True)
    with open(out, "w", encoding="utf-8", newline="\n") as fh:
        fh.write(header + html)

    print("%-15s %7d -> %7d bytes | phone x%d | %s"
          % (slug, before, len(html), phone_n, ", ".join(stripped[:3])))
    for line in recolour_report:
        print("                recolour: %s" % line)
    for line in form_report:
        if "x0" not in line:
            print("                form: %s" % line)
    return True


def main():
    for slug in PAGES:
        convert(slug)
    print("\nwritten to php-app/pages/")
    return 0


if __name__ == "__main__":
    sys.exit(main())
