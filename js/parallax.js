// Efecto Parallax con Mouse - Interactividad en el fondo de cada sección
document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('.service-slide, .hero, .footer-section');

    sections.forEach(section => {
        // Crear elementos de fondo animados para cada sección
        const parallaxBg = document.createElement('div');
        parallaxBg.className = 'parallax-background';

        // Crear múltiples círculos flotantes
        for (let i = 0; i < 6; i++) {
            const circle = document.createElement('div');
            circle.className = 'parallax-circle';
            circle.style.left = `${Math.random() * 100}%`;
            circle.style.top = `${Math.random() * 100}%`;
            circle.style.width = `${150 + Math.random() * 250}px`; // Larger circles
            circle.style.height = circle.style.width;
            circle.style.animationDelay = `${Math.random() * 5}s`;
            circle.style.animationDuration = `${10 + Math.random() * 10}s`;
            parallaxBg.appendChild(circle);
        }

        section.insertBefore(parallaxBg, section.firstChild);

        // Efecto de movimiento con el mouse - Increased sensitivity
        section.addEventListener('mousemove', (e) => {
            const rect = section.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            const circles = section.querySelectorAll('.parallax-circle');
            circles.forEach((circle, index) => {
                const speed = (index + 2) * 0.8; // More speed
                const moveX = (x - 0.5) * 80 * speed;
                const moveY = (y - 0.5) * 80 * speed;

                circle.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
        });

        // Reset al salir del mouse
        section.addEventListener('mouseleave', () => {
            const circles = section.querySelectorAll('.parallax-circle');
            circles.forEach(circle => {
                circle.style.transform = 'translate(0, 0)';
            });
        });
    });
});
