<?php
require_once '../classes/Claim.class.php';

if (!isset($_POST['claim_id'])) { // Use $_POST instead of $_GET
    echo "<p class='text-danger'>Error: Claim ID is required.</p>";
    exit;
}

$claimId = $_POST['claim_id']; // Fetch claim_id from POST
$claim = new Claim();

try {
    $claimDetails = $claim->fetchClaimDetails($claimId);

    if (!$claimDetails) {
        echo "<p class='text-danger'>Error: No claim details found for ID {$claimId}.</p>";
        exit;
    }

    // Pass the claim details to the modal
    include '../modals/view_claim_modal.php';
} catch (Exception $e) {
    echo "<p class='text-danger'>Error fetching claim details: " . $e->getMessage() . "</p>";
}
