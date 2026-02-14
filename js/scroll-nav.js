document.addEventListener('DOMContentLoaded', () => {
    const dots = document.querySelectorAll('.scroll-dot');
    const sections = Array.from(document.querySelectorAll('#hero, .service-slide'));

    // Some sections might have both IDs if not careful, let's ensure uniqueness by ID
    const uniqueSections = [];
    const seenIds = new Set();
    sections.forEach(sec => {
        if (!seenIds.has(sec.id)) {
            uniqueSections.push(sec);
            seenIds.add(sec.id);
        }
    });

    const observerOptions = {
        root: null,
        threshold: 0.5 // Trigger when 50% visible
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const dotIndex = uniqueSections.indexOf(entry.target);

                if (dotIndex !== -1 && dotIndex < dots.length) {
                    // Remove active from all dots
                    dots.forEach(d => d.classList.remove('active'));
                    // Add to current dot
                    dots[dotIndex].classList.add('active');

                    // Add active class to the section itself for animations
                    uniqueSections.forEach(s => s.classList.remove('active'));
                    entry.target.classList.add('active');
                }
            }
        });
    }, observerOptions);

    // Observe targets in sorted order
    uniqueSections.forEach(s => observer.observe(s));
});
