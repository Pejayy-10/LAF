<?php
session_start();
require_once '../classes/Claim.class.php';
require_once '../classes/Notification.class.php';

header('Content-Type: application/json');

// Check for admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access!']);
    exit;
}

// Retrieve POST data
$claimId = $_POST['claim_id'] ?? null;
$status = $_POST['status'] ?? null;
$reason = $_POST['reason'] ?? null;

// Validate input
if (!$claimId || !$status) {
    echo json_encode(['success' => false, 'message' => 'Invalid input! Claim ID and status are required.']);
    exit;
}

try {
    $claim = new Claim();
    $notification = new Notification();

    // Process based on claim status
    if ($status === 'approved') {
        $result = $claim->approveClaim($claimId);

        if ($result) {
            // Notify both claimant and reporter
            $claimantId = $claim->getClaimantId($claimId);
            $reporterId = $claim->getReporterId($claimId);

            $notification->addNotification(
                $claimantId,
                "Your claim has been approved! You can now chat with the reporter.",
                'claim',
                'chat.php?claim_id=' . $claimId
            );
            $notification->addNotification(
                $reporterId,
                "Your reported item has a successful claim! You can now chat with the claimant.",
                'claim',
                'chat.php?claim_id=' . $claimId
            );

            echo json_encode(['success' => true, 'message' => 'Claim approved successfully.']);
        } else {
            throw new Exception('Failed to approve the claim.');
        }
    } elseif ($status === 'rejected') {
        if (!$reason) {
            echo json_encode(['success' => false, 'message' => 'Rejection reason is required.']);
            exit;
        }

        $result = $claim->rejectClaim($claimId, $reason);

        if ($result) {
            // Notify the claimant
            $claimantId = $claim->getClaimantId($claimId);
            $notification->addNotification(
                $claimantId,
                "Your claim has been rejected. Reason: $reason",
                'claim',
                null
            );

            echo json_encode(['success' => true, 'message' => 'Claim rejected successfully.']);
        } else {
            throw new Exception('Failed to reject the claim.');
        }
    } else {
        throw new Exception('Invalid status value.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
