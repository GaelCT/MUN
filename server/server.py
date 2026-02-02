from flask import Flask, request, redirect, render_template
from flask_mail import Mail, Message
from dotenv import load_dotenv
import os

# Load environment variables from .env
load_dotenv()

app = Flask(__name__)

# Flask-Mail configuration for Gmail
app.config['MAIL_SERVER'] = 'smtp.gmail.com'
app.config['MAIL_PORT'] = 587
app.config['MAIL_USE_TLS'] = True
app.config['MAIL_USERNAME'] = os.getenv('GMAIL_USER')
app.config['MAIL_PASSWORD'] = os.getenv('GMAIL_APP_PASSWORD')
app.config['MAIL_DEFAULT_SENDER'] = os.getenv('GMAIL_USER')

mail = Mail(app)

# Contact form route
@app.route('/contact', methods=['POST'])
def contact():
    # Get form data
    name = request.form['name']
    email = request.form['email']
    message = request.form['message']

    # Compose email
    msg = Message(
        subject=f"New Contact Form Message from {name}",
        recipients=['csubmun@outlook.com'],
        body=f"""
You have received a new message from your website contact form.

Name: {name}
Email: {email}

Message:
{message}
"""
    )

    # Set reply-to so you can reply directly to the sender
    msg.reply_to = email

    # Send email
    mail.send(msg)

    # Redirect to thank-you page or back to homepage
    return redirect('/thank-you')  # you can make a /thank-you route

# Optional: simple home page
@app.route('/')
def index():
    return render_template('contact.html')  # your HTML file in templates folder

# Optional: simple thank-you page
@app.route('/thank-you')
def thank_you():
    return "<h1>Thank you! Your message has been sent.</h1>"

if __name__ == '__main__':
    app.run(debug=True)