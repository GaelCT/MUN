document.addEventListener('DOMContentLoaded', function() {
    const navbarHTML = `
        <nav class="navbar">
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="/pages/home.html" class="link">HOME</a></li>
                    <li><a href="/pages/about.html" class="link">MEET THE TEAM</a></li>
                    <li><a href="/pages/events.html" class="link">EVENTS</a></li>
                    
                    <li class="logo-item">
                        <div class="image">
                            <img src="MUN.png" alt="MUN Logo">
                        </div>
                    </li>

                    <li><a href="/pages/resources.html" class="link">RESOURCES</a></li>
                    <li><a href="/pages/faqs.html" class="link">FAQS</a></li>
                    <li><a href="/pages/contact.html" class="link">CONTACT</a></li>
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