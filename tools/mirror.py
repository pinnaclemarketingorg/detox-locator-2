"""Mirror the live detox-locator.com pages and every asset they reference.

The point is a *carbon copy*. Nothing here re-authors markup or re-derives a
stylesheet: it fetches what the server actually sends, walks the asset graph,
and rewrites absolute URLs to local paths. What the browser gets from the PHP
app should be byte-for-byte what it gets from WordPress today, minus the
WordPress.

Asset paths are preserved exactly (assets/wp-content/uploads/...), which is
what makes relative url() references inside the mirrored CSS keep resolving
without rewriting them.

Run:  python tools/mirror.py
"""
import os
import re
import sys
import time
import json
import gzip
import io
from urllib.parse import urljoin, urlsplit, unquote
from urllib.request import Request, urlopen
from urllib.error import URLError, HTTPError

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, "php-app")
ASSETS = os.path.join(OUT, "assets")
RAW = os.path.join(ROOT, "_source", "mirror-raw")

SITE = "https://detox-locator.com"
UA = ("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 "
      "(KHTML, like Gecko) Chrome/131.0 Safari/537.36")

# The five pages, and the local slug each becomes.
PAGES = {
    "/":              "home",
    "/lpa-2/":        "lpa-2",
    "/lpa-2-dkwi/":   "lpa-2-dkwi",
    "/lpa-2-dli/":    "lpa-2-dli",
    "/lpa-bing/":     "lpa-bing",
    "/privacy-policy/": "privacy-policy",
    "/hipaa-policy/":   "hipaa-policy",
}

# Hosts we mirror locally. Anything else (gtm, clarity, recaptcha) stays
# pointing at its origin, because those are live services, not assets.
MIRROR_HOSTS = {"detox-locator.com", "www.detox-locator.com"}

seen = set()
queue = []
failed = []


def fetch(url, tries=3):
    """GET a URL, following gzip. Returns (bytes, content_type) or (None, '')."""
    for attempt in range(tries):
        try:
            req = Request(url, headers={
                "User-Agent": UA,
                "Accept": "*/*",
                "Accept-Encoding": "gzip",
                "Referer": SITE + "/",
            })
            with urlopen(req, timeout=30) as r:
                data = r.read()
                if r.headers.get("Content-Encoding") == "gzip":
                    data = gzip.GzipFile(fileobj=io.BytesIO(data)).read()
                return data, (r.headers.get("Content-Type") or "")
        except (HTTPError, URLError, OSError) as exc:
            if attempt == tries - 1:
                failed.append((url, str(exc)))
                return None, ""
            time.sleep(1.5 * (attempt + 1))
    return None, ""


def local_path(url):
    """Where an asset URL lands on disk, preserving its original path."""
    parts = urlsplit(url)
    path = unquote(parts.path).lstrip("/")
    if not path:
        return None
    # Query strings are cache-busters (?ver=6.4); the file is the same.
    return os.path.join(ASSETS, *path.split("/"))


def local_url(url):
    """The path the mirrored page should reference instead of `url`."""
    parts = urlsplit(url)
    path = unquote(parts.path).lstrip("/")
    return "/assets/" + path


def should_mirror(url):
    parts = urlsplit(url)
    if parts.scheme not in ("http", "https"):
        return False
    return parts.hostname in MIRROR_HOSTS


def enqueue(url):
    url = url.split("#")[0]
    if not url or not should_mirror(url):
        return
    key = urlsplit(url)._replace(query="", fragment="").geturl()
    if key in seen:
        return
    seen.add(key)
    queue.append(url)


def save(path, data):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "wb") as fh:
        fh.write(data)


# --- CSS -------------------------------------------------------------------

CSS_URL = re.compile(rb"""url\(\s*['"]?([^'")]+)['"]?\s*\)""")
CSS_IMPORT = re.compile(rb"""@import\s+['"]([^'"]+)['"]""")


def process_css(data, base_url):
    """Queue everything a stylesheet references; leave relative refs alone.

    Relative url() is left untouched on purpose: because the mirror preserves
    the original directory structure, a relative reference that resolved on
    the live server resolves identically here.
    """
    def repl(match):
        raw = match.group(1).decode("utf-8", "replace").strip()
        if raw.startswith(("data:", "#")):
            return match.group(0)
        absolute = urljoin(base_url, raw)
        if should_mirror(absolute):
            enqueue(absolute)
            if raw.startswith(("http://", "https://", "//")):
                return b"url(" + local_url(absolute).encode() + b")"
        return match.group(0)

    data = CSS_URL.sub(repl, data)

    for match in CSS_IMPORT.finditer(data):
        raw = match.group(1).decode("utf-8", "replace").strip()
        enqueue(urljoin(base_url, raw))

    return data


# --- HTML ------------------------------------------------------------------

ATTR = re.compile(
    rb"""(?P<attr>\b(?:src|href|data-src|data-bg|content)\s*=\s*)(?P<q>["'])(?P<url>[^"']+)(?P=q)"""
)
SRCSET = re.compile(rb"""(\bsrcset\s*=\s*)(["'])([^"']+)(["'])""")


def process_html(data, base_url):
    """Queue and rewrite every asset the page references."""

    def repl(match):
        raw = match.group("url").decode("utf-8", "replace").strip()
        if raw.startswith(("data:", "mailto:", "tel:", "#", "javascript:")):
            return match.group(0)

        absolute = urljoin(base_url, raw)
        if not should_mirror(absolute):
            return match.group(0)

        path = urlsplit(absolute).path

        # Internal page links stay as pretty URLs — the PHP app serves them.
        if not re.search(r"\.[a-z0-9]{2,5}$", path, re.I):
            return match.group(0)
        # og:url / canonical are page URLs, not assets.
        if path.rstrip("/") in ("", "/"):
            return match.group(0)

        enqueue(absolute)
        return (match.group("attr") + match.group("q")
                + local_url(absolute).encode() + match.group("q"))

    data = ATTR.sub(repl, data)

    def repl_srcset(match):
        raw = match.group(3).decode("utf-8", "replace")
        out = []
        for candidate in raw.split(","):
            candidate = candidate.strip()
            if not candidate:
                continue
            bits = candidate.split()
            absolute = urljoin(base_url, bits[0])
            if should_mirror(absolute):
                enqueue(absolute)
                bits[0] = local_url(absolute)
            out.append(" ".join(bits))
        return match.group(1) + match.group(2) + ", ".join(out).encode() + match.group(4)

    data = SRCSET.sub(repl_srcset, data)
    return data


# --- main ------------------------------------------------------------------

def main():
    os.makedirs(RAW, exist_ok=True)
    os.makedirs(ASSETS, exist_ok=True)

    manifest = {}

    # 1. The five pages.
    for path, slug in PAGES.items():
        url = SITE + path
        print("page  %-14s %s" % (slug, url))
        data, _ctype = fetch(url)
        if data is None:
            print("      FAILED")
            continue

        save(os.path.join(RAW, slug + ".original.html"), data)
        rewritten = process_html(data, url)
        save(os.path.join(RAW, slug + ".mirrored.html"), rewritten)
        manifest[slug] = {"url": url, "bytes": len(rewritten)}

    # 2. Everything those pages referenced, transitively.
    done = 0
    while queue:
        url = queue.pop(0)
        path = local_path(url)
        if path is None:
            continue
        if os.path.exists(path):
            continue

        data, ctype = fetch(url)
        if data is None:
            continue

        if "css" in ctype or url.split("?")[0].endswith(".css"):
            data = process_css(data, url)

        save(path, data)
        done += 1
        if done % 25 == 0:
            print("asset %d saved, %d queued" % (done, len(queue)))

    print("\nassets saved: %d" % done)
    print("failed:       %d" % len(failed))
    for url, err in failed[:20]:
        print("   !", url, "--", err)

    with open(os.path.join(RAW, "manifest.json"), "w") as fh:
        json.dump({"pages": manifest, "failed": failed}, fh, indent=2)

    return 0


if __name__ == "__main__":
    sys.exit(main())
