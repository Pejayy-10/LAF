$(document).ready(function() {
    
    
    $(document).on('click', '#openProfileModal', function(e) {
        e.preventDefault();
        
        // Remove any existing profile modals first
        if ($('#profileModal').length) {
            $('#profileModal').modal('hide');
            $('#profileModal').remove();
        }
        
        $.ajax({
            url: 'modals/profile_view_modal.html',
            type: 'GET',
            cache: false,
            success: function(data) {
                // Insert into modal container
                $('#modal-container').html(data);
                
                // Initialize and show the modal after a short delay
                setTimeout(function() {
                    var profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
                    profileModal.show();
                }, 100);
            },
            error: function(xhr, status, error) {
                console.error('Modal load error:', error);
                alert('Failed to load profile modal. Please try again.');
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
