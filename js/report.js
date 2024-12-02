$(document).ready(function () {
  // Load Report Modal with iframe structure
  $(document).on('click', '#report-btn', function () {
    $.ajax({
        url: 'modals/report_modal.php',
        type: 'GET',
        success: function (response) {
            $('#modal-container').html(response);
            $('#reportModal').modal('show');
            
            // Handle Found/Lost button clicks within the report modal
            $(document).on('click', '[data-toggle="modal"][data-target="#foundModal"]', function() {
                $('#reportModal').modal('hide');
                setTimeout(function() {
                    $('#foundModal').modal('show');
                }, 200);
            });
            
            $(document).on('click', '[data-toggle="modal"][data-target="#lostModal"]', function() {
                $('#reportModal').modal('hide');
                setTimeout(function() {
                    $('#lostModal').modal('show');
                }, 200);
            });
        },
        error: function () {
            alert('An error occurred. Please log in first.');
            window.location.href = "account/login.php";
        }
    });
  });
});
