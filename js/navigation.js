/**
 * Navigation functionality
 */
(function() {
    'use strict';

const navToggle = document.querySelector('.menu-toggle');
const mobileNav = document.querySelector('.mobile-navigation');

if (navToggle && mobileNav) {
    navToggle.addEventListener('click', function() {
        const isHidden = mobileNav.classList.toggle('hidden');
        this.setAttribute('aria-expanded', (!isHidden).toString());
    });
}
})();

