from pathlib import Path
import sys

from flask import Flask, request, redirect, render_template
import resend
import os

# Make imports and file locations work whether this is started from the
# repository root or with ``python server/server.py``.
PROJECT_ROOT = Path(__file__).resolve().parents[1]
if str(PROJECT_ROOT) not in sys.path:
    sys.path.insert(0, str(PROJECT_ROOT))

from scripts.updateURL import get_latest_newsletter

app = Flask(
    __name__,
    template_folder=str(PROJECT_ROOT / "pages"),
    static_folder=str(PROJECT_ROOT / "static"),
)

# Set your Resend API key (or use environment variable)
resend.api_key = os.getenv("RESEND_API_KEY", "YOUR_API_KEY_HERE")

@app.route('/')
def index():
    return render_template('contact.html')  # Your contact form HTML

@app.route('/contact', methods=['POST'])
def contact():
    name = request.form.get('name', 'No Name')
    email = request.form.get('email', 'No Email')
    message = request.form.get('message', 'No Message')

    # Compose HTML email
    html_content = f"""
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> {name}</p>
    <p><strong>Email:</strong> {email}</p>
    <p><strong>Message:</strong><br>{message}</p>
    """

    # Send email using Resend SDK
    r = resend.Emails.send({
        "from": "contact@toonhosting.net",  # You can keep this or change to another email
        "to": os.getenv("RECIPIENT", "michaelguillory3@gmail.com"),      # Recipient email
        "subject": f"New Contact Form Message from {name}",
        "html": html_content
    })

    print(r)  # optional: prints Resend response in console
    return redirect('/thank-you')

@app.route('/newsletter')
def newsletter():
    newsletter_filename = get_latest_newsletter()
    return render_template(
        'newsletter.html',
        newsletter_filename=newsletter_filename
    )

@app.route('/thank-you')
def thank_you():
    return "<h1>Thank you! Your message has been sent.</h1>"

if __name__ == "__main__":
    app.run(debug=True)
