// ============================================
// ICONO VIRTUAL - MAIN JAVASCRIPT
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    // Initialize particles
    createParticles();

    // Form handling
    initializeForm();

    // Smooth scroll for any future anchor links
    initializeSmoothScroll();
});

// ============================================
// PARTICLE SYSTEM
// ============================================
function createParticles() {
    const particlesContainer = document.getElementById('particles');
    if (!particlesContainer) return;

    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';

        // Random position
        const x = Math.random() * 100;
        const y = Math.random() * 100;

        // Random size
        const size = Math.random() * 4 + 1;

        // Random animation duration
        const duration = Math.random() * 20 + 10;

        // Random delay
        const delay = Math.random() * 5;

        particle.style.cssText = `
            position: absolute;
            left: ${x}%;
            top: ${y}%;
            width: ${size}px;
            height: ${size}px;
            background: radial-gradient(circle, rgba(157, 78, 221, 0.8) 0%, rgba(157, 78, 221, 0) 70%);
            border-radius: 50%;
            animation: particleFloat ${duration}s ease-in-out ${delay}s infinite;
            pointer-events: none;
        `;

        particlesContainer.appendChild(particle);
    }

    // Add particle animation to stylesheet if not exists
    if (!document.querySelector('#particle-animation-style')) {
        const style = document.createElement('style');
        style.id = 'particle-animation-style';
        style.textContent = `
            @keyframes particleFloat {
                0%, 100% {
                    transform: translate(0, 0);
                    opacity: 0;
                }
                10% {
                    opacity: 1;
                }
                90% {
                    opacity: 1;
                }
                100% {
                    transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// ============================================
// FORM HANDLING
// ============================================
function initializeForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            service: formData.get('service')
        };

        // Validate
        if (!validateForm(data)) {
            return;
        }

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Enviando...';
        submitButton.disabled = true;

        // Submit form via AJAX
        fetch('process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('¡Gracias! Nos pondremos en contacto contigo pronto.', 'success');
                    form.reset();

                    // Track conversion if Meta Pixel is enabled
                    if (typeof fbq !== 'undefined') {
                        fbq('track', 'Lead');
                    }

                    // Track conversion if Google Analytics is enabled
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'generate_lead', {
                            'event_category': 'engagement',
                            'event_label': data.service
                        });
                    }
                } else {
                    showNotification('Hubo un error. Por favor, intenta nuevamente.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Hubo un error. Por favor, intenta nuevamente.', 'error');
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
    });
}

// ============================================
// FORM VALIDATION
// ============================================
function validateForm(data) {
    // Name validation
    if (!data.name || data.name.trim().length < 2) {
        showNotification('Por favor, ingresa un nombre válido.', 'error');
        return false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!data.email || !emailRegex.test(data.email)) {
        showNotification('Por favor, ingresa un email válido.', 'error');
        return false;
    }

    // Service validation
    if (!data.service) {
        showNotification('Por favor, selecciona un servicio.', 'error');
        return false;
    }

    return true;
}

// ============================================
// NOTIFICATION SYSTEM
// ============================================
function showNotification(message, type = 'info') {
    // Remove existing notification if any
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? 'rgba(57, 255, 20, 0.2)' : 'rgba(255, 57, 57, 0.2)'};
        border: 1px solid ${type === 'success' ? 'rgba(57, 255, 20, 0.5)' : 'rgba(255, 57, 57, 0.5)'};
        border-radius: 10px;
        color: white;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        z-index: 10000;
        backdrop-filter: blur(10px);
        animation: slideInRight 0.3s ease-out;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    `;

    // Add animation
    if (!document.querySelector('#notification-animation-style')) {
        const style = document.createElement('style');
        style.id = 'notification-animation-style';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// ============================================
// SMOOTH SCROLL
// ============================================
function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// ============================================
// INTERSECTION OBSERVER FOR ANIMATIONS
// ============================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.service-card, .form-card, .hero-content');
    cards.forEach(card => {
        observer.observe(card);
    });

    // Services Scroll Spy
    initScrollSpy();
});

// ============================================
// SCROLL SPY FOR SERVICES
// ============================================
function initScrollSpy() {
    const slides = document.querySelectorAll('.service-slide');
    const dots = document.querySelectorAll('.scroll-dot');

    if (!slides.length) return;

    const slideObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add active class to current slide
                slides.forEach(s => s.classList.remove('active'));
                entry.target.classList.add('active');

                // Update dots
                const id = entry.target.id;
                const index = Array.from(slides).findIndex(s => s.id === id);

                if (index !== -1 && dots[index]) {
                    dots.forEach(d => d.classList.remove('active'));
                    dots[index].classList.add('active');
                }
            }
        });
    }, {
        threshold: 0.5
    });

    slides.forEach(slide => {
        slideObserver.observe(slide);
    });
}
