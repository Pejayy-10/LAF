<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Claims</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap Bundle (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-danger">Manage Claims</h2>
        <table class="table table-hover mt-3" id="claims-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Claimant</th>
                    <th>Item ID</th>
                    <th>Item Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Claims data will be dynamically loaded -->
            </tbody>
        </table>
    </div>

    <!-- Modal for Viewing Claim -->
    <div class="modal fade" id="viewClaimModal" tabindex="-1" aria-labelledby="viewClaimModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClaimModalLabel">Claim Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="claim-details">
                    <!-- Claim details will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            loadClaims();

            // Function to load claims dynamically
            function loadClaims() {
                $.ajax({
                    url: '../processes/fetch_claims.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        let rows = '';
                        data.forEach(claim => {
                            rows += `
                                <tr>
                                    <td>${claim.claim_id}</td>
                                    <td>${claim.claimant || 'Unknown'}</td>
                                    <td>${claim.item_id}</td>
                                    <td>${claim.item_type}</td>
                                    <td>${claim.status}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-claim" data-id="${claim.claim_id}">View</button>
                                        ${claim.status === 'Pending' ? `
                                            <button class="btn btn-success btn-sm accept-claim" data-id="${claim.claim_id}" data-status="approved">Accept</button>
                                            <button class="btn btn-danger btn-sm reject-claim" data-id="${claim.claim_id}" data-status="rejected">Reject</button>
                                        ` : 'Action Taken'}
                                    </td>
                                </tr>`;
                        });
                        $('#claims-table tbody').html(rows);
                        bindActions(); // Bind actions for buttons
                    },
                    error: function () {
                        console.error("Error loading claims.");
                    }
                });
            }

            // Function to bind actions to buttons
            function bindActions() {
                $('.view-claim').click(function () {
                    const claimId = $(this).data('id');
                    viewClaim(claimId);
                });

                $('.accept-claim').click(function () {
                    const claimId = $(this).data('id');
                    updateClaimStatus(claimId, 'approved');
                });

                // Handle Reject Claim
                $('.reject-claim').click(function () {
                    const claimId = $(this).data('id');
                    const reason = prompt("Enter the reason for rejection:");
                    if (reason) {
                        updateClaimStatus(claimId, 'rejected', reason);
                    }
                });
}

            // Function to view claim details
            function viewClaim(claimId) {
                $.ajax({
                    url: '../processes/view_claim.php',
                    type: 'POST',
                    data: { claim_id: claimId }, // Use POST instead of GET
                    success: function (data) {
                        console.log(data); // Debugging output
                        $('#claim-details').html(data);
                        $('#viewClaimModal').modal('show');
                    },
                    error: function () {
                        alert('Error loading claim details.');
                    }
                });
            }


            // Function to update claim status
            function updateClaimStatus(claimId, status, reason = null) {
                if (confirm(`Are you sure you want to ${status} this claim?`)) {
                    $.ajax({
                        url: '../processes/update_claim_status.php',
                        type: 'POST',
                        data: { 
                            claim_id: claimId, 
                            status: status, 
                            reason: reason 
                        },
                        dataType: 'json', // Ensure server returns JSON
                        success: function (response) {
                            if (response.success) {
                                alert(response.message);
                                loadClaims(); // Reload claims after update
                            } else {
                                alert(`Error: ${response.message}`);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('An error occurred while updating the claim status.');
                            console.error(xhr.responseText);
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>
