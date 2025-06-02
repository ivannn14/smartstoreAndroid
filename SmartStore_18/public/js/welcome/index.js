document.addEventListener('DOMContentLoaded', function() {
    initializeScrollbar();
    initializeAnimations();
    updateCopyrightYear();
});

function initializeScrollbar() {
    var isWindows = navigator.platform.indexOf('Win') > -1;
    if (isWindows) {
        new PerfectScrollbar(document.querySelector('.main-content'));
    }
}

function initializeAnimations() {
    const cards = document.querySelectorAll('.feature-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fadeIn');
        }, index * 200);
    });
}

function updateCopyrightYear() {
    const yearElement = document.querySelector('.copyright-year');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
}