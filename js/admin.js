$(document).ready(function () {
    // Load the default view (dashboard analytics)
    loadContent('view_analytics.php');

    // Sidebar link click handler
    $(".nav-link").on("click", function (e) {
        e.preventDefault(); // Prevent default link behavior

        $(".nav-link").removeClass("link-active"); // Remove active class from all links
        $(this).addClass("link-active"); // Add active class to clicked link

        const page = $(this).attr("href"); // Get the href attribute (page to load)
        loadContent(page); // Load the content dynamically
    });

    // Sidebar toggle functionality
    $("#toggleSidebar").on("click", function () {
        $("#sidebar").toggleClass("collapsed");
        $(".content-page").toggleClass("collapsed"); // Adjust the content margin dynamically
    });

    // Approve Claim
    $(document).on('click', '.approve-btn', function () {
        const claimId = $(this).data('id');
        $.ajax({
            url: 'process_claim.php',
            type: 'POST',
            data: { claim_id: claimId, action: 'approve' },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('An error occurred while processing the claim.');
            }
        });
    });

    // Reject Claim
    $(document).on('click', '.reject-btn', function () {
        const claimId = $(this).data('id');
        const reason = prompt("Enter the reason for rejection:");
        if (reason) {
            $.ajax({
                url: 'process_claim.php',
                type: 'POST',
                data: { claim_id: claimId, action: 'reject', reason: reason },
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('An error occurred while rejecting the claim.');
                }
            });
        }
    });
});

// Function to load content dynamically
function loadContent(page) {
    $.ajax({
        url: `../admin/${page}`,
        type: "GET",
        success: function (response) {
            $(".content-page").html(response); // Insert the content into the content page
        },
        error: function () {
            $(".content-page").html('<div class="text-danger">Error loading page. Please try again.</div>');
        },
    });
}