AOS.init({
    duration: 800, // values from 0 to 3000, with step 50ms
    once: true, // whether animation should happen only once - while scrolling down
});

document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll for nav links
    document.querySelectorAll('.navbar .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Navbar active state on scroll
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('header[id], section[id]');

    function activateMenu() {
        let current = 'hero'; // Default to hero
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 75) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', activateMenu);
    activateMenu(); // Call on page load
});
