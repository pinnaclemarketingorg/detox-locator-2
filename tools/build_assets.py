"""
Build php-app/assets/ from brand-src/.

Everything the site serves is derived here, so an image can be re-cropped or
re-compressed without hand-editing the output. Re-run after changing anything
in brand-src/:

    python tools/build_assets.py

Prints the pixel size of every output, which is what the width/height
attributes in the templates are taken from.
"""

from __future__ import annotations

import shutil
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parent.parent
SRC = ROOT / "brand-src"
OUT = ROOT / "php-app" / "assets"
IMG = OUT / "img"


def save_webp(im: Image.Image, dest: Path, quality: int = 80) -> None:
    dest.parent.mkdir(parents=True, exist_ok=True)
    im.save(dest, "WEBP", quality=quality, method=6)
    print(f"{dest.relative_to(OUT)}  {im.size[0]}x{im.size[1]}")


def fit_width(im: Image.Image, width: int) -> Image.Image:
    if im.width <= width:
        return im.copy()
    height = round(im.height * width / im.width)
    return im.resize((width, height), Image.LANCZOS)


def trim(im: Image.Image) -> Image.Image:
    """Crop to the ink, so sizing in CSS describes the artwork, not padding."""
    im = im.convert("RGBA")
    box = im.getchannel("A").point(lambda a: 255 if a > 8 else 0).getbbox()
    return im.crop(box) if box else im


def white_logo(im: Image.Image) -> Image.Image:
    """
    The purple lockup for dark backgrounds: the indigo figure and wordmark go
    white, the lavender leaves stay lavender. Indigo is the only dark colour
    in the artwork, so luminance alone separates the two.
    """
    im = im.convert("RGBA")
    px = im.load()
    for y in range(im.height):
        for x in range(im.width):
            r, g, b, a = px[x, y]
            if a and (0.299 * r + 0.587 * g + 0.114 * b) < 110:
                px[x, y] = (255, 255, 255, a)
    return im


def photos() -> None:
    plan = {
        # name: (source, [widths])
        "counseling-session": ("counseling-session.webp", [1200, 720]),
        "woman-on-phone": ("woman-on-phone.jpg", [900, 560]),
        "group-support": ("group-support.webp", [1600, 900]),
        "embrace": ("embrace.webp", [600]),
        "facility-exterior": ("IMG_0965.jpg", [1400, 760]),
        "facility-living": ("IMG_0966-1.jpg", [1200, 640]),
        "facility-lounge": ("IMG_0969-1.jpg", [1200, 640]),
        "facility-kitchen": ("IMG_0971-1.jpg", [1200, 640]),
    }
    for name, (src, widths) in plan.items():
        im = Image.open(SRC / "photos" / src).convert("RGB")
        for i, w in enumerate(widths):
            suffix = "" if i == 0 else f"-{w}"
            save_webp(fit_width(im, w), IMG / "photos" / f"{name}{suffix}.webp", 78)


def residence() -> None:
    """
    The Mariposa residence, from invigoratebh.com/our-facility/. `crop` is a
    (width/height ratio, horizontal position 0-1) pair for the portrait cuts.
    """
    plan = {
        # name: (source, [widths], crop)
        "residence-view": ("Mariposa014.jpg", [412, 687], (412 / 600, 0.85)),
        "residence-entry": ("Mariposa001.jpg", [1200, 640], None),
        "residence-great-room": ("Mariposa004.jpg", [1200, 640], None),
        "residence-dining": ("Mariposa007.jpg", [1200, 640], None),
        "residence-lounge": ("Mariposa013.jpg", [1200, 640], None),
        "residence-kitchen": ("Mariposa018.jpg", [1200, 640], None),
        "residence-hall": ("Mariposa024.jpg", [667, 400], None),
        "los-angeles": ("los-angeles.jpg", [1600, 900], None),
    }
    for name, (src, widths, crop) in plan.items():
        im = Image.open(SRC / "residence" / src).convert("RGB")
        if crop:
            ratio, x = crop
            w = min(im.width, round(im.height * ratio))
            left = round((im.width - w) * x)
            im = im.crop((left, 0, left + w, im.height))
        for i, w in enumerate(widths):
            suffix = "" if i == 0 else f"-{w}"
            save_webp(fit_width(im, w), IMG / "photos" / f"{name}{suffix}.webp", 78)


def team() -> None:
    """Headshots from invigoratebh.com/our-team/, square, shown at up to 200px."""
    for f in sorted((SRC / "team").glob("*.jpg")):
        im = Image.open(f).convert("RGB")
        save_webp(fit_width(im, 400), IMG / "team" / f"{f.stem}.webp", 80)


def logos() -> None:
    lockup = trim(Image.open(SRC / "logo" / "detox-locator-logo2.png"))
    save_webp(fit_width(lockup, 480), IMG / "logo" / "logo.webp", 90)
    save_webp(fit_width(white_logo(lockup), 480), IMG / "logo" / "logo-white.webp", 90)

    mark = trim(Image.open(SRC / "logo" / "cropped-detox-locator-logo2-270x270.png"))
    save_webp(fit_width(mark, 200), IMG / "logo" / "mark.webp", 90)
    save_webp(fit_width(white_logo(mark), 200), IMG / "logo" / "mark-white.webp", 90)

    pinnacle = Image.open(SRC / "logo" / "pinnacle-mark.png").convert("RGBA")
    save_webp(pinnacle, IMG / "logo" / "pinnacle-mark.webp", 92)

    # Favicons from the square mark, centred on a transparent canvas.
    square = Image.new("RGBA", (max(mark.size),) * 2, (0, 0, 0, 0))
    square.paste(mark, ((square.width - mark.width) // 2, (square.height - mark.height) // 2))
    fav = IMG / "favicon"
    fav.mkdir(parents=True, exist_ok=True)
    for size in (32, 192):
        square.resize((size, size), Image.LANCZOS).save(fav / f"favicon-{size}.png")
    # Apple touch icons are composited onto an opaque background by iOS
    # anyway; doing it here keeps the corners from going black.
    touch = Image.new("RGBA", (180, 180), (255, 255, 255, 255))
    inner = square.resize((148, 148), Image.LANCZOS)
    touch.alpha_composite(inner, (16, 16))
    touch.convert("RGB").save(fav / "apple-touch-icon.png")
    square.resize((48, 48), Image.LANCZOS).save(fav / "favicon.ico", sizes=[(16, 16), (32, 32), (48, 48)])
    print("img/favicon/*")


def carriers() -> None:
    # Tinted tiles, as the live landing pages show them.
    for f in sorted((SRC / "carriers-tile").glob("*.png")):
        im = Image.open(f).convert("RGBA")
        save_webp(fit_width(im, 300), IMG / "carriers-tile" / f"{f.stem.replace('01', '').replace('-1', '')}.webp", 82)

    # Transparent marks, trimmed to the ink.
    for f in sorted((SRC / "carriers").glob("*.png")):
        im = trim(Image.open(f))
        # Normalise to a 96px-tall master; CSS sets the display height.
        h = 96
        w = round(im.width * h / im.height)
        if im.height > h:
            im = im.resize((w, h), Image.LANCZOS)
        save_webp(im, IMG / "carriers" / f"{f.stem}.webp", 90)


def accreditation() -> None:
    save_webp(Image.open(SRC / "accreditation" / "joint-commission.webp").convert("RGBA"), IMG / "accreditation" / "joint-commission.webp", 88)
    save_webp(fit_width(Image.open(SRC / "accreditation" / "dhcs.webp").convert("RGBA"), 480), IMG / "accreditation" / "dhcs.webp", 88)
    shutil.copy(SRC / "accreditation" / "dhcs-logo.svg", IMG / "accreditation" / "dhcs-logo.svg")
    # Stand-in for LegitScript's hosted seal when a blocker stops it loading.
    save_webp(fit_width(Image.open(SRC / "accreditation" / "legitscript.png").convert("RGBA"), 146), IMG / "accreditation" / "legitscript.webp", 90)


def icons() -> None:
    for f in sorted((SRC / "icons").glob("*.png")):
        im = Image.open(f).convert("RGBA")
        save_webp(fit_width(im, 160), IMG / "icons" / f"{f.stem}.webp", 88)
    # The white "Google Reviews" lockup, at full size for 2x screens.
    save_webp(Image.open(SRC / "icons" / "image-346.png").convert("RGBA"), IMG / "icons" / "google-reviews.webp", 90)
    # The A pages' line icons and logo mark, as the live page's own SVGs.
    for f in sorted((SRC / "program-icons").glob("*.svg")):
        shutil.copy(f, IMG / "icons" / f"program-{f.stem}.svg")
        print(f"img/icons/program-{f.stem}.svg")


def avatars() -> None:
    for f in sorted((SRC / "avatars").glob("*.*")):
        if f.suffix.lower() not in (".png", ".jpg", ".jpeg"):
            continue
        im = Image.open(f).convert("RGB")
        side = min(im.size)
        im = im.crop(((im.width - side) // 2, (im.height - side) // 2, (im.width + side) // 2, (im.height + side) // 2))
        save_webp(im.resize((96, 96), Image.LANCZOS) if side > 96 else im, IMG / "avatars" / f"{f.stem}.webp", 82)


def fonts() -> None:
    dest = OUT / "fonts"
    dest.mkdir(parents=True, exist_ok=True)
    for f in (SRC / "fonts").glob("*.woff2"):
        shutil.copy(f, dest / f.name)
        print(f"fonts/{f.name}")


if __name__ == "__main__":
    photos()
    residence()
    team()
    logos()
    carriers()
    accreditation()
    icons()
    avatars()
    fonts()
