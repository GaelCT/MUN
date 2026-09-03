# newsletter.py

import re
from datetime import datetime
from pathlib import Path


def get_latest_newsletter():
    """Return the filename of the newsletter currently available to Flask.

    ``static/assets`` is the single public source for newsletter PDFs.  Using
    an absolute path derived from this module keeps the result independent of
    the directory from which the Flask process was started.
    """
    folder = Path(__file__).resolve().parents[1] / "static" / "assets"
    date_regex = r"(\d{2})-(\d{2})-(\d{4})"

    # The admin panel writes the current issue under this stable name.
    current_newsletter = folder / "newsletter.pdf"
    if current_newsletter.is_file():
        return current_newsletter.name

    if not folder.is_dir():
        return None

    newsletters = []

    for path in folder.iterdir():
        filename = path.name

        if not path.is_file() or not filename.lower().endswith(".pdf"):
            continue

        match = re.search(date_regex, filename)

        if not match:
            continue

        month = int(match.group(1))
        day = int(match.group(2))
        year = int(match.group(3))

        try:
            date_object = datetime(year, month, day)
        except ValueError:
            # Ignore files whose date-like suffix is not a real calendar date.
            continue

        newsletters.append({
            "filename": filename,
            "date": date_object
        })

    today = datetime.now()

    available_newsletters = [
        newsletter
        for newsletter in newsletters
        if newsletter["date"] <= today
    ]

    if not available_newsletters:
        return None

    latest_newsletter = max(
        available_newsletters,
        key=lambda newsletter: newsletter["date"]
    )

    return latest_newsletter["filename"]

