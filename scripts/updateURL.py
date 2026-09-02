# newsletter.py

import os
import re
from datetime import datetime


def get_latest_newsletter():

    folder = "assets"
    date_regex = r"(\d{2})-(\d{2})-(\d{4})"

    newsletters = []

    for filename in os.listdir(folder):

        if not filename.lower().endswith(".pdf"):
            continue

        match = re.search(date_regex, filename)

        if not match:
            continue

        month = int(match.group(1))
        day = int(match.group(2))
        year = int(match.group(3))

        date_object = datetime(year, month, day)

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

