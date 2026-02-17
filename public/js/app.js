/**
 * BNGRC Modern Dashboard - JavaScript Animations & Charts
 * Theme: Black, Blue, White Modern UI
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modules
    initParticles();
    initCounterAnimations();
    initScrollAnimations();
    initHoverEffects();
    initCharts();
    initMenuActiveState();
    initTableRowAnimations();
    initGlowEffects();
});

/* ================= PARTICLES BACKGROUND ================= */
function initParticles() {
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'particles';
    document.body.appendChild(particlesContainer);

    const particleCount = 30;
    
    for (let i = 0; i < particleCount; i++) {
        createParticle(particlesContainer, i);
    }
}

function createParticle(container, index) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    
    // Random properties
    const size = Math.random() * 4 + 2;
    const left = Math.random() * 100;
    const delay = Math.random() * 15;
    const duration = Math.random() * 10 + 15;
    const opacity = Math.random() * 0.4 + 0.2;
    
    particle.style.cssText = `
        width: ${size}px;
        height: ${size}px;
        left: ${left}%;
        animation-delay: ${delay}s;
        animation-duration: ${duration}s;
        opacity: ${opacity};
        background: ${index % 2 === 0 ? '#0ea5e9' : '#3b82f6'};
        box-shadow: 0 0 ${size * 2}px ${index % 2 === 0 ? 'rgba(14, 165, 233, 0.5)' : 'rgba(59, 130, 246, 0.5)'};
    `;
    
    container.appendChild(particle);
}

/* ================= COUNTER ANIMATIONS ================= */
function initCounterAnimations() {
    const statCards = document.querySelectorAll('.stat-card h3');
    
    statCards.forEach(card => {
        const finalValue = parseInt(card.textContent.replace(/[^0-9]/g, '')) || 0;
        if (finalValue > 0) {
            animateCounter(card, finalValue);
        }
    });
}

function animateCounter(element, target) {
    const duration = 2000;
    const start = performance.now();
    const startValue = 0;
    
    function easeOutExpo(x) {
        return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
    }
    
    function update(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = easeOutExpo(progress);
        const currentValue = Math.floor(startValue + (target - startValue) * eased);
        
        element.textContent = currentValue.toLocaleString('fr-FR');
        
        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = target.toLocaleString('fr-FR');
        }
    }
    
    // Start animation when element is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                requestAnimationFrame(update);
                observer.unobserve(element);
            }
        });
    }, { threshold: 0.5 });
    
    observer.observe(element);
}

/* ================= SCROLL ANIMATIONS ================= */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.stat-card, .table, .chart-card, .city-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(el => observer.observe(el));
}

/* ================= HOVER EFFECTS ================= */
function initHoverEffects() {
    // Card tilt effect
    const cards = document.querySelectorAll('.stat-card, .city-card, .chart-card');
    
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });
    
    // Ripple effect on buttons
    const buttons = document.querySelectorAll('.btn, .btn-primary, .btn-save, .menu-item a');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                pointer-events: none;
                transform: scale(0);
                animation: ripple 0.6s linear;
                left: ${e.clientX - rect.left}px;
                top: ${e.clientY - rect.top}px;
                width: 100px;
                height: 100px;
                margin-left: -50px;
                margin-top: -50px;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
}

/* ================= CHARTS ================= */
function initCharts() {
    // Check if Chart.js is loaded and if we have chart containers
    if (typeof Chart === 'undefined') {
        console.log('Chart.js not loaded, skipping charts');
        return;
    }
    
    // Chart.js global configuration for light theme
    Chart.defaults.color = '#64748b';
    Chart.defaults.borderColor = 'rgba(226, 232, 240, 0.8)';
    Chart.defaults.font.family = "'Inter', sans-serif";
    
    initDistributionChart();
    initDonationsChart();
    initNeedsChart();
}

function initDistributionChart() {
    const ctx = document.getElementById('distributionChart');
    if (!ctx) return;
    
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.3)');
    gradient.addColorStop(1, 'rgba(14, 165, 233, 0.0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Distributions',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#0ea5e9',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0ea5e9',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#1e293b',
                    bodyColor: '#64748b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12,
                    boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    },
                    beginAtZero: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
}

function initDonationsChart() {
    const ctx = document.getElementById('donationsChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Produits', 'Argent', 'Matériel'],
            datasets: [{
                data: [45, 35, 20],
                backgroundColor: [
                    '#0ea5e9',
                    '#3b82f6',
                    '#8b5cf6'
                ],
                borderColor: '#ffffff',
                borderWidth: 4,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#1e293b',
                    bodyColor: '#64748b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12
                }
            },
            cutout: '70%',
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
}

function initNeedsChart() {
    const ctx = document.getElementById('needsChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Antananarivo', 'Toamasina', 'Antsirabe', 'Fianarantsoa', 'Mahajanga'],
            datasets: [{
                label: 'Besoins en cours',
                data: [65, 45, 38, 52, 28],
                backgroundColor: [
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                borderColor: [
                    '#0ea5e9',
                    '#3b82f6',
                    '#8b5cf6',
                    '#10b981',
                    '#f59e0b'
                ],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#1e293b',
                    bodyColor: '#64748b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    },
                    beginAtZero: true
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
}

/* ================= MENU ACTIVE STATE ================= */
function initMenuActiveState() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.menu-item a');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href === currentPath || (currentPath === '/' && href === '/')) {
            item.parentElement.classList.add('active');
        }
    });
}

/* ================= TABLE ROW ANIMATIONS ================= */
function initTableRowAnimations() {
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05 + 0.2}s`;
        });
    });
}

/* ================= GLOW EFFECTS ================= */
function initGlowEffects() {
    // Add glow effect to stat card numbers on hover
    const statNumbers = document.querySelectorAll('.stat-card h3');
    
    statNumbers.forEach(num => {
        num.addEventListener('mouseenter', function() {
            this.style.color = '#0284c7';
            this.style.textShadow = '0 0 20px rgba(14, 165, 233, 0.4)';
        });
        
        num.addEventListener('mouseleave', function() {
            this.style.color = '#0284c7';
            this.style.textShadow = 'none';
        });
    });
}

/* ================= RIPPLE ANIMATION KEYFRAMES ================= */
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .animate-in {
        animation: animateIn 0.6s ease forwards;
    }
    
    @keyframes animateIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Smooth number transition */
    .stat-card h3 {
        transition: text-shadow 0.3s ease;
    }
`;
document.head.appendChild(style);

/* ================= UTILITY FUNCTIONS ================= */

// Smooth scroll to element
function smoothScrollTo(element) {
    element.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Export for use in other scripts
window.BNGRC = {
    smoothScrollTo,
    debounce,
    throttle,
    initCharts
};
