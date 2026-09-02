# MUN - Museum or University Network System

This repository contains a complete web application for managing and displaying newsletter PDFs, gallery images, and providing contact functionality. The system combines multiple technologies to create a cohesive web experience.

## Project Overview

This system serves as a content management platform with three main components:

1. **Contact Form & Email System** - For visitors to send messages
2. **Newsletter Display** - Shows the latest newsletter PDF to visitors  
3. **Gallery Management** - Admin panel for uploading and managing images/PDFs

## Directory Structure

```
MUN/
├── assets/ - Newsletter PDFs and other documents
├── images/ - Image files for the gallery
├── pages/ - HTML web pages
│   ├── index.html - Main landing page
│   ├── about.html - About page
│   ├── contact.html - Contact form page
│   ├── events.html - Events page
│   ├── faqs.html - FAQ page
│   ├── gallery.html - Gallery page (public view)
│   ├── resources.html - Resources page
│   ├── newsletter.html - Newsletter display page
│   └── admin.php - Admin upload and management panel
├── scripts/ - JavaScript and utility scripts
│   ├── updateURL.py - Finds latest newsletter PDF
│   ├── navbar.js - Navigation menu
│   └── script.js - Various utilities
├── server/ - Backend server applications
│   ├── server.py - Flask email server with newsletter route
│   └── test.py - Test email server
├── styles/ - CSS styling files
├── static/ - Static files for the PHP server
├── uploads/ - Uploaded gallery files
└── PLAN.md - Project documentation
```

## Key Technologies

### Backend Services

**1. Flask Server (server/server.py)**
- Handles contact form submissions and sends emails via Resend API
- Provides `/newsletter` endpoint to display the latest newsletter PDF
- Uses Flask templates to render newsletter.html with the newsletter filename

**2. PHP Server (in pages directory)**
- Serves static HTML pages and uploaded files
- Runs on port 8000
- Can be started with: `php -S localhost:8000 -t pages`

**3. Python Utility (scripts/updateURL.py)**
- Automatically finds the latest newsletter PDF from the assets folder
- Uses filename patterns like `SEPT NEWSLETTER.09-02-2006.pdf` to extract dates
- Returns the most recent PDF that has been released

### Frontend Pages

**HTML Pages in pages/**
- **index.html** - Main landing page
- **about.html** - Information about the organization
- **contact.html** - Contact form (for the Flask server)
- **events.html** - Events calendar or information
- **faqs.html** - Frequently asked questions
- **gallery.html** - Public gallery view of uploaded content
- **resources.html** - Additional resources page
- **newsletter.html** - Displays the latest newsletter PDF
- **admin.php** - Administrator panel for uploading content

**Admin Panel (admin.php)**
- Password-protected upload system
- Supports multiple file types (images and PDFs)
- Separate categories: Gallery images and Newsletter PDFs
- Gallery images go to `uploads/` folder
- Newsletter PDFs go to `assets/` folder
- Includes CSRF protection for security

### Static Assets

**CSS Files (styles/)** - Styling for all HTML pages
- style.css - Main styles
- main.css - Additional styles
- Various other page-specific styles

**Scripts (scripts/)** - Client-side utilities
- navbar.js - Navigation menu
- script.js - Various utilities
- updateURL.py - Backend utility (see above)

## How It All Works Together

### 1. Visitor Experience

1. **Visit the website** - Access through the main domain
2. **Browse pages** - Navigate through the different sections
3. **View newsletter** - Click on newsletter link to see the latest PDF
4. **Contact us** - Fill out the contact form to send a message
5. **Gallery** - Browse through uploaded photos

### 2. Newsletter Flow

1. **Admin uploads** - Upload a PDF in admin.php with "Newsletter" category
2. **File naming** - Files should have date patterns like `SEPT NEWSLETTER.09-02-2006.pdf`
3. **Auto-update** - The system automatically detects the latest PDF
4. **Display** - When visitors view the newsletter page, it shows the most recent PDF

### 3. Gallery Management

1. **Login** - Access admin.php with password
2. **Choose category** - Select between "Gallery" or "Newsletter"
3. **Upload** - Upload files (images for gallery, PDFs for newsletter)
4. **View** - Files appear immediately in the gallery
5. **Delete** - Admin can remove unwanted files


## Troubleshooting

### Common Issues

**1. "assests" vs "assets" typo**
- All instances of "assests" have been corrected to "assets"
- Directory renamed from assests to assets

**2. Newsletter not showing**
- Ensure PDFs are named with dates: `NAME MONTH DAY YEAR.pdf`
- Check that files are in the correct assets/ directory
- Verify the Flask server is running

**3. Admin panel access issues**
- Make sure you're using the correct password: `mun2025`
- Check that you have the right URL for the admin panel

**4. Static files not loading**
- Ensure the PHP server is running
- Check that file paths in HTML are correct

### Debugging

To see what's happening:
1. Check server logs when starting services
2. Verify file paths in HTML templates
3. Ensure directories exist (assets/, uploads/, static/)

## Future Enhancements

Potential improvements for future development:

1. **Database Integration**
   - Store upload metadata in a database
   - Track file versions and history
   - User authentication and roles

2. **Content Management**
   - Rich text editing for pages
   - Image carousel for newsletter
   - File compression for PDFs

3. **Advanced Features**
   - Email newsletter subscription
   - Social media integration
   - Analytics for page views
   - Mobile app companion

## License

This project is open source. Feel free to use, modify, and distribute it.

## Support

For questions or issues, please contact the administrator through the contact form.

---

*Last updated: September 2, 2026*

**Note**: This system is designed to work with both local development and deployment to web servers. The PHP and Flask servers should be run independently in separate terminal windows for proper functionality.
