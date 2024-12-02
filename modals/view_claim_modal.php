<?php
// Ensure session and database connectivity
session_start();
require_once '../classes/Claim.class.php';

if (!isset($_POST['claim_id'])) {
    echo "<p class='text-danger'>Error: Claim ID is required.</p>";
    exit;
}

$claimId = $_POST['claim_id'];
$claim = new Claim();

try {
    // Fetch claim details
    $claimDetails = $claim->fetchClaimDetails($claimId);

    if (!$claimDetails) {
        echo "<p class='text-danger'>Error: Claim details not found.</p>";
        exit;
    }

    // Extract details with error handling
    $claimant = $claimDetails['claimant'] ?? 'Unknown';
    $reporterName = $claimDetails['reporter_name'] ?? 'Unknown';
    $itemDescription = $claimDetails['item_description'] ?? 'No description provided';
    $proofText = $claimDetails['text_proof'] ?? 'No proof text provided';
    $proofImage = $claimDetails['image_proof'] ?? null;

} catch (Exception $e) {
    echo "<p class='text-danger'>Error fetching claim details: " . $e->getMessage() . "</p>";
    exit;
}
?>

<div class="modal-body">
    <h4 class="text-primary">Claim Details</h4>
    <p><strong>Claim ID:</strong> <?= htmlspecialchars($claimDetails['claim_id']) ?></p>
    <p><strong>Claimant:</strong> <?= htmlspecialchars($claimDetails['claimant']) ?></p>
    <p><strong>Reporter:</strong> <?= htmlspecialchars($claimDetails['reporter'] ?? 'Unknown') ?></p>
    <p><strong>Item Description:</strong> <?= htmlspecialchars($claimDetails['item_description'] ?? 'No description provided') ?></p>
    <p><strong>Proof Text:</strong> <?= htmlspecialchars($claimDetails['text_proof'] ?? 'No proof text provided') ?></p>
    <p><strong>Proof Image:</strong></p>
    <?php if (!empty($claimDetails['image_proof'])): ?>
        <img src="../<?= htmlspecialchars($claimDetails['image_proof']) ?>" alt="Proof Image" class="img-fluid">
    <?php else: ?>
        <p>No proof image provided.</p>
    <?php endif; ?>
</div>


