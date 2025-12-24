/**
 * VahidRajabloo Theme - JavaScript
 * 
 * Navigation, scroll effects, and interactions
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        console.log('VahidRajabloo Theme JS Loaded');

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
            if (window.scrollY > 10) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        }

        if (header) {
            window.addEventListener('scroll', handleScroll);
            // Trigger once on load
            handleScroll();
        }

        // ==========================================================================
        // Dark Mode Toggle
        // ==========================================================================
        const themeToggles = document.querySelectorAll('.theme-toggle-btn');
        const html = document.documentElement;

        // Check for saved user preference, if any, on load
        const savedTheme = localStorage.getItem('theme');
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

        if (savedTheme === 'dark' || (!savedTheme && systemTheme === 'dark')) {
            html.setAttribute('data-theme', 'dark');
            themeToggles.forEach(btn => btn.setAttribute('aria-pressed', 'true'));
        }

        themeToggles.forEach(toggle => {
            toggle.addEventListener('click', function () {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);

                themeToggles.forEach(btn => btn.setAttribute('aria-pressed', newTheme === 'dark'));
            });
        });



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

            // Mobile submenu toggle
            const mobileSubmenus = mobileNav.querySelectorAll('.menu-item-has-children');
            mobileSubmenus.forEach(item => {
                const link = item.querySelector(':scope > a');
                const submenu = item.querySelector(':scope > .sub-menu');

                if (link && submenu) {
                    link.addEventListener('click', function (e) {
                        // Prevent navigation if has children
                        e.preventDefault();

                        // Toggle this submenu
                        item.classList.toggle('submenu-open');
                        submenu.classList.toggle('active');
                    });
                }
            });

            // Close menu on link click (but not parent items)
            mobileNav.querySelectorAll('.sub-menu a, a:not(.menu-item-has-children > a)').forEach(link => {
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

                const emailInput = this.querySelector('input[type="email"]');
                const email = emailInput.value;
                const button = this.querySelector('button');
                const originalText = button.textContent;

                // Simple validation
                if (!email || !validateEmail(email)) {
                    alert('Please enter a valid email address.');
                    return;
                }

                // Check if vrNewsletter is available
                if (typeof vrNewsletter === 'undefined') {
                    alert('Newsletter service not available.');
                    return;
                }

                // Show loading state
                button.textContent = 'Subscribing...';
                button.disabled = true;

                // Send AJAX request
                const formData = new FormData();
                formData.append('action', 'newsletter_signup');
                formData.append('email', email);
                formData.append('nonce', vrNewsletter.nonce);

                fetch(vrNewsletter.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.textContent = '✓ Subscribed!';
                            emailInput.value = '';
                            button.style.background = '#10B981';

                            setTimeout(() => {
                                button.textContent = originalText;
                                button.disabled = false;
                                button.style.background = '';
                            }, 3000);
                        } else {
                            alert(data.data?.message || 'Subscription failed. Please try again.');
                            button.textContent = originalText;
                            button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Newsletter error:', error);
                        alert('Connection error. Please try again.');
                        button.textContent = originalText;
                        button.disabled = false;
                    });
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
        
        /* Dark Mode Overrides - injected via JS to bypass CSS caching */
        [data-theme="dark"] .section--gray,
        [data-theme="dark"] #features,
        [data-theme="dark"] .features-section {
            background-color: #1e293b !important;
            background-image: none !important;
            color: #cbd5e1 !important;
        }
        
        [data-theme="dark"] .site-footer {
            background-color: #1e293b !important;
            color: #cbd5e1 !important;
        }
    `;
        document.head.appendChild(style);

    });
})();
