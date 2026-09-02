document.addEventListener('DOMContentLoaded', function() {
    const navbarHTML = `
        <nav class="navbar">
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="../pages/index.html" class="link">HOME</a></li>
                    <li><a href="../pages/about.html" class="link">MEET THE TEAM</a></li>
                    <li><a href="../pages/events.html" class="link">EVENTS</a></li>
                    <li><a href="../pages/gallery.php" class="link">GALLERY</a></li>
                    
                    <li class="logo-item">
                        
                        <div class="image">
                            <a href="../pages/home.html">
                                <img src="../images/MUN.png" alt="MUN Logo">
                            </a>
                        </div>
                    </li>

                    <li><a href="../pages/resources.html" class="link">RESOURCES</a></li>
                    <li><a href="../pages/faqs.html" class="link">FAQS</a></li>
                    <li><a href="../pages/contact.html" class="link">CONTACT</a></li>
                    <li><a href="../pages/admin.php" class="link">ADMIN</a></li>
                </ul>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </nav>
    `;
    
    document.getElementById('navbar-placeholder').innerHTML = navbarHTML;
    
    // Highlight active page
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const links = document.querySelectorAll('.link');
    links.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
});