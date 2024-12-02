$(document).ready(function () {
    // Load Report Found Modal
    $(document).on('click', '#report-found-btn', function () {
        $.ajax({
            url: 'modals/report_found_modal.php',
            type: 'GET',
            success: function (response) {
                $('#modal-container').html(response);
                $('#reportFoundModal').modal('show');
            },
            error: function () {
                alert('An error occurred. Please log in first.');
                window.location.href = "account/login.php";
            }
        });
    });

    // Load Report Lost Modal
    $(document).on('click', '#report-lost-btn', function () {
        $.ajax({
            url: 'modals/report_lost_modal.php',
            type: 'GET',
            success: function (response) {
                $('#modal-container').html(response);
                $('#reportLostModal').modal('show');
            },
            error: function () {
                alert('An error occurred. Please log in first.');
                window.location.href = "account/login.php";
            }
        });
    });

    // Handle Report Found Item Submission
    $(document).on('submit', '#report-found-form', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: 'processes/submit_found_item.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#reportFoundModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('An error occurred while reporting the found item.');
            }
        });
    });

    // Handle Report Lost Item Submission
    $(document).on('submit', '#report-lost-form', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: 'processes/submit_lost_item.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#reportLostModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('An error occurred while reporting the lost item.');
            }
        });
    });

    $(document).on('click', '.claim-btn', function () {
        const itemId = $(this).data('item-id');
        const itemType = $(this).data('item-type');
    
        $.ajax({
            url: 'modals/claim_item_modal.php',
            type: 'GET',
            data: { item_id: itemId, item_type: itemType },
            success: function (response) {
                $('#modal-container').html(response);
                $('#claimItemModal').modal('show');
            },
            error: function () {
                alert('An error occurred while loading the claim modal.');
            }
        });
    });
    
    $(document).on('submit', '#claim-item-form', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
    
        // Debugging: Log all form data
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
    
        $.ajax({
            url: 'processes/submit_claim.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response); // Debug response
                const jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    alert(jsonResponse.message);
                    $('#claimItemModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + jsonResponse.message);
                }
            },
            error: function () {
                alert('An error occurred while submitting the claim.');
            }
        });
    });
    
    

$(document).ready(function () {
    // Load notifications on page load
    loadNotifications();

    // Function to fetch notifications
    function loadNotifications() {
        $.ajax({
            url: 'processes/fetch_notifications.php', // Endpoint to fetch notifications
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                let notifications = '';
                let unreadCount = 0;

                if (data.length > 0) {
                    data.forEach(notification => {
                        notifications += `
                            <li class="dropdown-item ${notification.is_read ? '' : 'fw-bold'}">
                                <a href="${notification.link || '#'}">${notification.message}</a>
                            </li>`;
                        if (!notification.is_read) unreadCount++;
                    });
                } else {
                    notifications = '<li class="dropdown-item">No new notifications.</li>';
                }

                $('#notification-list').html(notifications);
                $('#notification-count').text(unreadCount > 0 ? unreadCount : '');
            },
            error: function () {
                console.error('Failed to load notifications.');
            }
        });
    }
});

})
