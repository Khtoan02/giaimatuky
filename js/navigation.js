/**
 * Navigation functionality
 */
(function() {
    'use strict';

    // Mobile menu toggle (if needed in the future)
    const navToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.main-navigation');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('is-open');
            this.setAttribute('aria-expanded', navMenu.classList.contains('is-open'));
        });
    }
})();

