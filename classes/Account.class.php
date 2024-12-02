<?php
require_once 'Database.class.php';

class Claim {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function fetchAllClaims() {
        $sql = "SELECT c.claim_id, c.proof_of_ownership, c.status, c.created_at, u.username AS claimant, i.item_id
                FROM claims c
                JOIN users u ON c.claimant_id = u.user_id
                JOIN lost_items i ON c.item_id = i.item_id
                ORDER BY c.created_at DESC";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveClaim($claimId) {
        $sql = "UPDATE claims SET status = 'approved' WHERE claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);

        // Add logic to notify claimant and reporter (example below).
        if ($stmt->execute()) {
            $this->notifyClaimParticipants($claimId, 'approved');
            return true;
        }
        return false;
    }

    public function rejectClaim($claimId, $reason) {
        $sql = "DELETE FROM claims WHERE claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);

        // Notify claimant about rejection
        if ($stmt->execute()) {
            $this->notifyClaimant($claimId, 'rejected', $reason);
            return true;
        }
        return false;
    }

    private function notifyClaimParticipants($claimId, $status) {
        // Fetch claim data
        $sql = "SELECT c.claimant_id, i.reporter_id 
                FROM claims c
                JOIN lost_items i ON c.item_id = i.item_id
                WHERE c.claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->execute();
        $claimData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($claimData) {
            $claimantId = $claimData['claimant_id'];
            $reporterId = $claimData['reporter_id'];

            // Notify claimant and reporter
            $this->sendNotification($claimantId, "Your claim has been approved. Start chatting with the reporter!");
            $this->sendNotification($reporterId, "A claim on your reported item has been approved. Start chatting with the claimant!");
        }
    }

    private function notifyClaimant($claimId, $status, $reason = null) {
        // Fetch claimant ID
        $sql = "SELECT claimant_id FROM claims WHERE claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->execute();
        $claimantId = $stmt->fetchColumn();

        if ($claimantId) {
            $message = $status === 'rejected'
                ? "Your claim was rejected. Reason: $reason"
                : "Your claim was approved.";
            $this->sendNotification($claimantId, $message);
        }
    }

    private function sendNotification($userId, $message) {
        $sql = "INSERT INTO notifications (user_id, message, created_at) VALUES (:user_id, :message, NOW())";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }

    public function submitClaim($userId, $itemId, $itemType, $textProof, $imageProof) {
        try {
            $imagePath = null;
            if ($imageProof && $imageProof['tmp_name']) {
                $targetDir = "../uploads/claims/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $imagePath = $targetDir . uniqid() . "-" . basename($imageProof['name']);
                move_uploaded_file($imageProof['tmp_name'], $imagePath);
            }

            $sql = "INSERT INTO claims (user_id, item_id, item_type, text_proof, image_proof, status, created_at) 
                    VALUES (:user_id, :item_id, :item_type, :text_proof, :image_proof, 'Pending', NOW())";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':item_id', $itemId);
            $stmt->bindParam(':item_type', $itemType);
            $stmt->bindParam(':text_proof', $textProof);
            $stmt->bindParam(':image_proof', $imagePath);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error submitting claim: " . $e->getMessage());
        }
    }
}
?>

