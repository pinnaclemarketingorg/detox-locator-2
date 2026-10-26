"""Structural sanity check for the theme's PHP files.

There is no PHP binary on this machine, so this is not a parse and will not
catch a genuine syntax error. What it does catch is the class of breakage that
hand-editing templates actually produces: an unbalanced brace, paren or
bracket, an unterminated string or block comment, and a `<?php` region that is
never closed before a stretch of HTML.

It tokenises well enough to ignore braces that appear inside strings, `//`
and `#` line comments, and `/* */` blocks.
"""
import os
import sys

ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "php-app")

PAIRS = {"}": "{", ")": "(", "]": "["}
OPENERS = set(PAIRS.values())


def check(path):
    """Return a list of problem strings for one file."""
    src = open(path, encoding="utf-8").read()
    problems = []

    i, n = 0, len(src)
    in_php = False
    stack = []          # (char, line)
    line = 1

    while i < n:
        ch = src[i]

        if ch == "\n":
            line += 1
            i += 1
            continue

        if not in_php:
            if src.startswith("<?php", i) or src.startswith("<?=", i):
                in_php = True
                i += 5 if src.startswith("<?php", i) else 3
            else:
                i += 1
            continue

        # --- inside a PHP region ---
        if src.startswith("?>", i):
            in_php = False
            i += 2
            continue

        # comments
        if src.startswith("//", i) or ch == "#":
            j = src.find("\n", i)
            i = n if j == -1 else j
            continue
        if src.startswith("/*", i):
            j = src.find("*/", i + 2)
            if j == -1:
                problems.append("line %d: unterminated /* block comment" % line)
                break
            line += src.count("\n", i, j)
            i = j + 2
            continue

        # strings
        if ch in "'\"":
            quote = ch
            j = i + 1
            while j < n:
                if src[j] == "\\":
                    j += 2
                    continue
                if src[j] == quote:
                    break
                j += 1
            if j >= n:
                problems.append("line %d: unterminated %s string" % (line, quote))
                break
            line += src.count("\n", i, j)
            i = j + 1
            continue

        # heredoc / nowdoc — skip to the terminator
        if src.startswith("<<<", i):
            j = src.find("\n", i)
            if j == -1:
                break
            tag = src[i + 3:j].strip().strip("'\"")
            k = src.find("\n" + tag, j)
            if k == -1:
                problems.append("line %d: unterminated heredoc %s" % (line, tag))
                break
            line += src.count("\n", i, k)
            i = k + len(tag) + 1
            continue

        if ch in OPENERS:
            stack.append((ch, line))
        elif ch in PAIRS:
            if not stack:
                problems.append("line %d: stray closing %s" % (line, ch))
            elif stack[-1][0] != PAIRS[ch]:
                problems.append(
                    "line %d: closing %s does not match %s opened on line %d"
                    % (line, ch, stack[-1][0], stack[-1][1])
                )
                stack.pop()
            else:
                stack.pop()

        i += 1

    for ch, ln in stack:
        problems.append("unclosed %s opened on line %d" % (ch, ln))

    return problems


def main():
    files = []
    for dirpath, _dirs, names in os.walk(ROOT):
        for name in sorted(names):
            if name.endswith(".php"):
                files.append(os.path.join(dirpath, name))

    failed = 0
    for path in sorted(files):
        rel = os.path.relpath(path, ROOT)
        problems = check(path)
        if problems:
            failed += 1
            print("FAIL %s" % rel)
            for p in problems:
                print("       %s" % p)
        else:
            print("ok   %s" % rel)

    print("\n%d file(s) checked, %d with problems." % (len(files), failed))
    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
