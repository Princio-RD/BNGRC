/**
 * ==================== ANIMATIONS & INTERACTIONS ====================
 * Modern animations and interactive effects for BNGRC application
 */

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
  initAnimations();
  initInteractiveEffects();
  initFormValidation();
  initTableAnimations();
  initMenuAnimations();
  initScrollAnimations();
  initParticleEffect();
});

// ==================== SMOOTH SCROLL ANIMATION ====================
function initAnimations() {
  // Animate stat cards on load
  const statCards = document.querySelectorAll('.stat-card');
  statCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
    
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'translateY(-8px) scale(1.05) rotateX(5deg)';
    });
    
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'translateY(0) scale(1) rotateX(0)';
    });
  });

  // Animate form cards
  const formCards = document.querySelectorAll('.form-card');
  formCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
  });

  // Animate alerts
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.animation = 'slideInDown 0.5s ease-out forwards';
    }, 100);
  });
}

// ==================== INTERACTIVE MOUSE EFFECTS ====================
function initInteractiveEffects() {
  const container = document.body;

  // Create ripple effect on click
  document.addEventListener('click', (e) => {
    const ripple = document.createElement('div');
    ripple.style.position = 'fixed';
    ripple.style.left = e.clientX + 'px';
    ripple.style.top = e.clientY + 'px';
    ripple.style.width = '10px';
    ripple.style.height = '10px';
    ripple.style.borderRadius = '50%';
    ripple.style.background = 'radial-gradient(circle, rgba(0, 82, 204, 0.5), transparent)';
    ripple.style.pointerEvents = 'none';
    ripple.style.transform = 'translate(-50%, -50%)';
    ripple.style.zIndex = '9999';

    document.body.appendChild(ripple);

    // Animate ripple
    let size = 10;
    const interval = setInterval(() => {
      size += 20;
      ripple.style.width = size + 'px';
      ripple.style.height = size + 'px';
      ripple.style.opacity = 1 - (size / 400);

      if (size > 400) {
        clearInterval(interval);
        ripple.remove();
      }
    }, 30);
  });

  // Mouse follow effect for stat cards
  const statCards = document.querySelectorAll('.stat-card');
  statCards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      // Create light effect
      const bgGradient = `
        radial-gradient(circle at ${x}px ${y}px, rgba(255,255,255,0.2) 0%, transparent 50%),
        linear-gradient(135deg, rgba(0, 102, 255, 0.8), rgba(0, 153, 255, 0.8))
      `;
      card.style.backgroundImage = bgGradient;
    });

    card.addEventListener('mouseleave', () => {
      card.style.backgroundImage = 'linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%)';
    });
  });
}

// ==================== FORM VALIDATION WITH ANIMATION ====================
function initFormValidation() {
  const inputs = document.querySelectorAll('.form-inline input, .form-inline select');

  inputs.forEach(input => {
    input.addEventListener('focus', function() {
      this.parentElement.style.animation = 'pulse 0.5s ease-out';
    });

    input.addEventListener('blur', function() {
      validateField(this);
    });

    input.addEventListener('input', function() {
      if (this.value.length > 0) {
        this.style.borderColor = 'var(--success)';
      }
    });
  });

  function validateField(field) {
    if (!field.value) {
      field.style.borderColor = 'var(--danger)';
      showValidationError(field, 'Ce champ est requis');
    } else {
      field.style.borderColor = 'var(--success)';
      removeValidationError(field);
    }
  }

  function showValidationError(field, message) {
    removeValidationError(field);
    const error = document.createElement('div');
    error.className = 'validation-error';
    error.textContent = message;
    error.style.color = 'var(--danger)';
    error.style.fontSize = '0.8em';
    error.style.marginTop = '5px';
    error.style.animation = 'fadeIn 0.3s ease-out';
    field.parentElement.appendChild(error);
  }

  function removeValidationError(field) {
    const error = field.parentElement.querySelector('.validation-error');
    if (error) error.remove();
  }
}

// ==================== TABLE ANIMATIONS ====================
function initTableAnimations() {
  const rows = document.querySelectorAll('.table tbody tr');

  rows.forEach((row, index) => {
    row.style.opacity = '0';
    row.style.animation = `fadeInUp 0.6s ease-out ${index * 0.05}s forwards`;

    row.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.02) translateX(5px)';
      this.style.boxShadow = 'inset 0 0 20px rgba(0, 82, 204, 0.1)';
    });

    row.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1) translateX(0)';
      this.style.boxShadow = 'none';
    });

    // Add click animation
    row.addEventListener('click', function() {
      this.style.backgroundColor = 'rgba(0, 82, 204, 0.05)';
      setTimeout(() => {
        this.style.backgroundColor = '';
      }, 300);
    });
  });
}

// ==================== MENU ANIMATIONS ====================
function initMenuAnimations() {
  const menuItems = document.querySelectorAll('.menu-item a');

  menuItems.forEach((item, index) => {
    item.style.animation = `slideInLeft 0.6s ease-out ${0.1 + index * 0.05}s forwards`;

    // Add active state animation
    if (window.location.href.includes(item.getAttribute('href'))) {
      item.style.color = '#0099FF';
      item.parentElement.style.backgroundColor = 'rgba(0, 153, 255, 0.15)';
    }
  });

  // Add click effect
  menuItems.forEach(item => {
    item.addEventListener('click', function() {
      menuItems.forEach(i => i.style.color = '#66B3FF');
      this.style.color = '#0099FF';
    });
  });
}

// ==================== SCROLL ANIMATIONS ====================
function initScrollAnimations() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.animation = 'slideInUp 0.6s ease-out forwards';
      }
    });
  }, { threshold: 0.1 });

  // Observe all animated elements
  document.querySelectorAll('.form-card, .alert, .card').forEach(el => {
    el.style.opacity = '0';
    observer.observe(el);
  });
}

// ==================== PARTICLE EFFECT ====================
function initParticleEffect() {
  // Create floating particles in background
  const header = document.querySelector('.header');
  if (!header) return;

  function createParticle() {
    const particle = document.createElement('div');
    particle.style.position = 'fixed';
    particle.style.width = Math.random() * 10 + 5 + 'px';
    particle.style.height = particle.style.width;
    particle.style.background = `rgba(255, 255, 255, ${Math.random() * 0.5})`;
    particle.style.borderRadius = '50%';
    particle.style.pointer = 'none';
    particle.style.zIndex = '0';
    particle.style.top = Math.random() * window.innerHeight + 'px';
    particle.style.left = Math.random() * window.innerWidth + 'px';

    document.body.appendChild(particle);

    // Animate particle
    let x = Math.random() * 2 - 1;
    let y = Math.random() * 2 - 1;
    let opacity = parseFloat(particle.style.background.match(/[\d.]+/g)[3]);

    const animate = setInterval(() => {
      let top = parseFloat(particle.style.top);
      let left = parseFloat(particle.style.left);

      particle.style.top = (top + y) + 'px';
      particle.style.left = (left + x) + 'px';
      opacity -= 0.005;

      particle.style.background = `rgba(255, 255, 255, ${opacity})`;

      if (opacity <= 0 || top < 0 || left < 0 || top > window.innerHeight || left > window.innerWidth) {
        clearInterval(animate);
        particle.remove();
      }
    }, 50);
  }

  // Create particles periodically
  setInterval(createParticle, 500);
}

// ==================== BUTTON RIPPLE EFFECT ====================
document.querySelectorAll('.btn').forEach(button => {
  button.addEventListener('click', function(e) {
    const rect = this.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    const ripple = document.createElement('span');
    ripple.style.position = 'absolute';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.style.width = '10px';
    ripple.style.height = '10px';
    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
    ripple.style.borderRadius = '50%';
    ripple.style.pointerEvents = 'none';
    ripple.style.transform = 'translate(-50%, -50%)';

    this.style.position = 'relative';
    this.style.overflow = 'hidden';
    this.appendChild(ripple);

    let size = 10;
    const interval = setInterval(() => {
      size += 15;
      ripple.style.width = size + 'px';
      ripple.style.height = size + 'px';
      ripple.style.opacity = 1 - (size / 200);

      if (size > 200) {
        clearInterval(interval);
        ripple.remove();
      }
    }, 30);
  });
});

// ==================== NUMBER COUNTER ANIMATION ====================
function animateCounters() {
  const counters = document.querySelectorAll('.stat-card h3');

  counters.forEach(counter => {
    const target = parseInt(counter.innerText) || 0;
    let count = 0;
    const increment = target / 50;

    const updateCount = setInterval(() => {
      count += increment;
      if (count >= target) {
        counter.innerText = target;
        clearInterval(updateCount);
      } else {
        counter.innerText = Math.ceil(count);
      }
    }, 30);
  });
}

// Run counter animation on load
window.addEventListener('load', animateCounters);

// ==================== SMOOTH PAGE TRANSITIONS ====================
window.addEventListener('beforeunload', () => {
  document.body.style.animation = 'fadeIn 0.3s ease-in reverse';
});

// ==================== KEYBOARD SHORTCUTS ====================
document.addEventListener('keydown', (e) => {
  // Alt + / pour focus sur le menu
  if (e.altKey && e.key === '/') {
    e.preventDefault();
    const firstMenuItem = document.querySelector('.menu-item a');
    if (firstMenuItem) firstMenuItem.focus();
  }

  // Escape pour fermer les modales ou désélectionner
  if (e.key === 'Escape') {
    document.querySelectorAll('input, select').forEach(el => el.blur());
  }
});

// ==================== TOOLTIP FUNCTIONALITY ====================
function initTooltips() {
  const elements = document.querySelectorAll('[data-tooltip]');

  elements.forEach(el => {
    el.addEventListener('mouseenter', function() {
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip';
      tooltip.textContent = this.getAttribute('data-tooltip');
      tooltip.style.position = 'fixed';
      tooltip.style.background = 'var(--primary)';
      tooltip.style.color = 'white';
      tooltip.style.padding = '8px 12px';
      tooltip.style.borderRadius = '6px';
      tooltip.style.fontSize = '0.85em';
      tooltip.style.pointerEvents = 'none';
      tooltip.style.zIndex = '10000';
      tooltip.style.animation = 'fadeIn 0.3s ease-out';

      document.body.appendChild(tooltip);

      const rect = this.getBoundingClientRect();
      tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
      tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';

      el._tooltipElement = tooltip;
    });

    el.addEventListener('mouseleave', function() {
      if (this._tooltipElement) {
        this._tooltipElement.remove();
        delete this._tooltipElement;
      }
    });
  });
}

initTooltips();

// ==================== DARK MODE TOGGLE (Optional) ====================
function toggleDarkMode() {
  document.body.classList.toggle('dark-mode');
  localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

// Check for saved dark mode preference
if (localStorage.getItem('darkMode') === 'true') {
  document.body.classList.add('dark-mode');
}

// ==================== PERFORMANCE OPTIMIZATION ====================
// Debounce function for scroll and resize events
function debounce(func, delay) {
  let timeoutId;
  return function(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func(...args), delay);
  };
}

// ==================== PAGE VISIBILITY API ====================
document.addEventListener('visibilitychange', () => {
  if (document.hidden) {
    console.log('Page is hidden');
  } else {
    console.log('Page is visible');
  }
});

console.log('✨ BNGRC Modern Animations Loaded');
