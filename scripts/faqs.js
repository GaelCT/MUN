// FAQ Accordion Functionality
document.addEventListener('DOMContentLoaded', function() {
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(accordion => {
        accordion.addEventListener('click', function() {
            this.classList.toggle('active');
            // Get the panel 
            const panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });
    });
});