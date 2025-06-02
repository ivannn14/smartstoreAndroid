document.addEventListener('DOMContentLoaded', function() {
    initializeAnimations();
    initializeProfileEdit();
    initializeDeleteAccount();
    initializeNameEdit();
});

function initializeAnimations() {
    const cards = document.querySelectorAll(".profile-card");
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add("animate__fadeInUp");
        }, index * 100);
    });
}

function initializeProfileEdit() {
    const editProfileBtn = document.getElementById("editProfileBtn");
    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", function () {
            document.querySelector(".profile-card .card-body").classList.toggle("d-none");
        });
    }
}

function initializeDeleteAccount() {
    const deleteForm = document.querySelector('form');
    const passwordInput = document.querySelector('input[name="password"]');
    const deleteModal = document.getElementById('deleteAccountModal');

    if (deleteForm && passwordInput) {
        deleteForm.addEventListener('submit', function(e) {
            if (!passwordInput.value) {
                e.preventDefault();
                passwordInput.classList.add('is-invalid');
            }
        });

        passwordInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function() {
            this.querySelector('.warning-icon-circle').style.animation = 'pulse 2s infinite';
        });
    }
}

function initializeNameEdit() {
    const editNameBtn = document.getElementById('editNameBtn');
    const editNameForm = document.getElementById('editNameForm');
    const nameDisplay = document.getElementById('nameDisplay');
    const cancelNameEdit = document.getElementById('cancelNameEdit');
    const nameInput = editNameForm?.querySelector('input[name="name"]');

    if (!editNameBtn || !editNameForm || !nameDisplay || !cancelNameEdit || !nameInput) return;

    editNameBtn.addEventListener('click', showEditForm);
    cancelNameEdit.addEventListener('click', hideEditForm);
    document.addEventListener('keydown', handleEscapeKey);
    editNameForm.addEventListener('submit', handleFormSubmit);
}

function showEditForm() {
    const nameDisplay = document.getElementById('nameDisplay');
    const editNameBtn = document.getElementById('editNameBtn');
    const editNameForm = document.getElementById('editNameForm');
    const nameInput = editNameForm.querySelector('input[name="name"]');

    nameDisplay.classList.add('d-none');
    editNameBtn.classList.add('d-none');
    editNameForm.classList.remove('d-none');
    editNameBtn.setAttribute('aria-expanded', 'true');
    nameInput.focus();
    nameInput.select();

    announceToScreenReader('Editing name. Use tab to navigate between input field and buttons.');
}

function hideEditForm() {
    const nameDisplay = document.getElementById('nameDisplay');
    const editNameBtn = document.getElementById('editNameBtn');
    const editNameForm = document.getElementById('editNameForm');
    const nameInput = editNameForm.querySelector('input[name="name"]');

    nameDisplay.classList.remove('d-none');
    editNameBtn.classList.remove('d-none');
    editNameForm.classList.add('d-none');
    editNameBtn.setAttribute('aria-expanded', 'false');
    editNameBtn.focus();
    nameInput.value = nameDisplay.querySelector('h6').textContent.trim();

    announceToScreenReader('Name edit cancelled');
}

function handleEscapeKey(e) {
    if (e.key === 'Escape' && !document.getElementById('editNameForm').classList.contains('d-none')) {
        hideEditForm();
    }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i><span class="visually-hidden">Saving...</span>';
        
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            const newName = form.querySelector('input[name="name"]').value;
            updateDisplayedName(newName);
            showAccessibleToast('Name updated successfully!', 'success');
            hideEditForm();
        } else {
            showAccessibleToast('Failed to update name. Please try again.', 'error');
        }
    } catch (error) {
        showAccessibleToast('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i><span class="visually-hidden">Save</span>';
    }
}

function updateDisplayedName(newName) {
    document.querySelector('#nameDisplay h6').textContent = newName;
    document.querySelector('.profile-header h5').textContent = newName;
}

function announceToScreenReader(message) {
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.classList.add('visually-hidden');
    announcement.textContent = message;
    document.body.appendChild(announcement);
    setTimeout(() => announcement.remove(), 3000);
}

function showAccessibleToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.style.zIndex = '11';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'polite');
    
    toast.innerHTML = `
        <div class="toast show align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2" aria-hidden="true"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close notification"></button>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}