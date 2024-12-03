$(document).ready(function () {
    $('#openProfileModal').click(function () {
        // Load the modal HTML dynamically using AJAX
        $.ajax({
            url: 'profile-modal.html', // Path to the modal HTML
            success: function (data) {
                // Append the modal HTML to the body
                $('body').append(data);
                // Show the modal
                $('#profileModal').modal('show');

                // Remove the modal from the DOM after it is closed
                $('#profileModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            },
            error: function () {
                alert('Failed to load the modal. Please try again.');
            }
        });
    });
});

function toggleEditMode() {
    const editBtn = document.getElementById('mainEditBtn');
    const inputs = document.querySelectorAll('.custom-input');
    const profileImage = document.querySelector('.profile-image');

    if (editBtn.textContent === 'Edit') {
        // Switch to edit mode
        editBtn.textContent = 'Save';
        editBtn.classList.add('save-mode');
        profileImage.classList.add('edit-mode');
        inputs.forEach(input => {
            input.disabled = false;
        });
    } else {
        // Switch back to view mode
        editBtn.textContent = 'Edit';
        editBtn.classList.remove('save-mode');
        profileImage.classList.remove('edit-mode');
        inputs.forEach(input => {
            input.disabled = true;
        });

        // Here you would typically save the changes to a backend
        const formData = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            facebook: document.getElementById('facebook').value
        };
        console.log('Saved data:', formData);
    }
}

function visitFacebook(event) {
    const input = event.target;
    if (input.disabled && input.id === 'facebook') {
        event.preventDefault();
        const url = input.value.trim();
        if (url) {
            let fullUrl = url;
            if (!url.includes('facebook.com')) {
                fullUrl = `https://facebook.com/${url}`;
            } else if (!url.startsWith('http')) {
                fullUrl = `https://${url}`;
            }
            window.open(fullUrl, '_blank', 'noopener,noreferrer');
        }
    }
}
