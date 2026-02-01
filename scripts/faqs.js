// FAQ Accordion Functionality
document.addEventListener('DOMContentLoaded', function() {
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(accordion => {
        accordion.addEventListener('click', function() {
            // Toggle active class
            this.classList.toggle('active');
            
            // Get the panel (next sibling element)
            const panel = this.nextElementSibling;
            
            // Toggle panel visibility
            if (panel.style.maxHeight) {
                // Panel is open, close it
                panel.style.maxHeight = null;
            } else {
                // Panel is closed, open it
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    });
});