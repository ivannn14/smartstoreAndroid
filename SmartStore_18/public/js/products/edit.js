document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const updateButton = document.querySelector('.update-button');

    form.addEventListener('submit', function() {
        updateButton.classList.add('loading');
        updateButton.innerHTML = '';
    });

    // Add floating labels
    const formControls = document.querySelectorAll('.form-control, .form-select');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.closest('.mb-4').classList.add('focused');
        });
        
        control.addEventListener('blur', function() {
            if (!this.value) {
                this.closest('.mb-4').classList.remove('focused');
            }
        });
        
        // Initialize on load
        if (control.value) {
            control.closest('.mb-4').classList.add('focused');
        }
    });
});