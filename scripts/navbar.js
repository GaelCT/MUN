document.addEventListener('DOMContentLoaded', function() {
    const navbarHTML = `
        <nav class="navbar">
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="../pages/index.html" class="link">HOME</a></li>
                    <li><a href="../pages/about.html" class="link">MEET THE TEAM</a></li>
                    <!-- <li><a href="../pages/events.html" class="link">EVENTS</a></li> -->
                    <li><a href="../pages/gallery.php" class="link">GALLERY</a></li>
                    
                    <li class="logo-item">
                        
                        <div class="image">
                            <a href="../pages/index.html">
                                <img src="../images/MUN.png" alt="MUN Logo">
                            </a>
                        </div>
                    </li>

                    <!-- <li><a href="../pages/resources.html" class="link">RESOURCES</a></li> -->
                    <li><a href="../pages/newsletter.html" class="link">NEWSLETTER</a></li>
                    <!-- <li><a href="../pages/faqs.html" class="link">FAQS</a></li> -->
                    <li><a href="../pages/contact.html" class="link">CONTACT</a></li>
                    <li><a href="../pages/admin.php" class="link">ADMIN</a></li>
                </ul>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-label="Toggle navigation menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </nav>
    `;
    
    document.getElementById('navbar-placeholder').innerHTML = navbarHTML;
    
    const menuButton = document.getElementById('mobileMenuBtn');
    const navLinksList = document.querySelector('.nav-links');

    menuButton.addEventListener('click', function() {
        const isOpen = navLinksList.classList.toggle('active');
        menuButton.classList.toggle('active', isOpen);
        menuButton.setAttribute('aria-expanded', String(isOpen));
    });

    // Highlight the current page and close the phone menu after navigation.
    const links = document.querySelectorAll('.link');
    links.forEach(link => {
        if (new URL(link.href).pathname === window.location.pathname) {
            link.classList.add('active');
        }

        link.addEventListener('click', function() {
            navLinksList.classList.remove('active');
            menuButton.classList.remove('active');
            menuButton.setAttribute('aria-expanded', 'false');
        });
    });
});
