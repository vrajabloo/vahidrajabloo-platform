/**
 * VahidRajabloo Theme - JavaScript
 * 
 * Navigation, scroll effects, and interactions
 */

(function () {
    'use strict';

    // ==========================================================================
    // DOM Elements
    // ==========================================================================
    const header = document.getElementById('site-header');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNav = document.querySelector('.mobile-nav');

    // ==========================================================================
    // Header Scroll Effect
    // ==========================================================================
    function handleScroll() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', handleScroll);

    // ==========================================================================
    // Mobile Menu Toggle
    // ==========================================================================
    if (mobileMenuToggle && mobileNav) {
        mobileMenuToggle.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

            this.setAttribute('aria-expanded', !isExpanded);
            this.classList.toggle('active');
            mobileNav.classList.toggle('active');

            // Prevent body scroll when menu is open
            document.body.style.overflow = !isExpanded ? 'hidden' : '';
        });

        // Close menu on link click
        mobileNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                mobileMenuToggle.classList.remove('active');
                mobileNav.classList.remove('active');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            });
        });
    }

    // ==========================================================================
    // Smooth Scroll for Anchor Links
    // ==========================================================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');

            if (targetId === '#') return;

            const target = document.querySelector(targetId);

            if (target) {
                e.preventDefault();

                const headerHeight = header.offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ==========================================================================
    // Newsletter Form
    // ==========================================================================
    const newsletterForms = document.querySelectorAll('.newsletter-form');

    newsletterForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button');
            const originalText = button.textContent;

            // Simple validation
            if (!email || !validateEmail(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            // Show loading state
            button.textContent = 'Subscribing...';
            button.disabled = true;

            // Simulate subscription (replace with actual API call)
            setTimeout(() => {
                button.textContent = 'Subscribed!';
                this.querySelector('input[type="email"]').value = '';

                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }, 1000);
        });
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // ==========================================================================
    // Intersection Observer for Animations
    // ==========================================================================
    const animatedElements = document.querySelectorAll('.card, .feature-card');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        animatedElements.forEach(el => {
            el.classList.add('animate-ready');
            observer.observe(el);
        });
    }

    // ==========================================================================
    // Add CSS for animations
    // ==========================================================================
    const style = document.createElement('style');
    style.textContent = `
        .animate-ready {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-in {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);

})();
