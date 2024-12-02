<?php
require_once 'Database.class.php';

class Claim {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function submitClaim($userId, $itemId, $itemType, $textProof, $imageProof) {
        try {
            // Check if the item is already claimed and approved
            $availabilityCheck = "SELECT status FROM claims WHERE item_id = :item_id AND item_type = :item_type AND status = 'Approved'";
            $stmt = $this->db->connect()->prepare($availabilityCheck);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':item_type', $itemType, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->fetch()) {
                throw new Exception("This item has already been claimed.");
            }
    
            // Handle image upload
            $imagePath = null;
            if ($imageProof && $imageProof['tmp_name']) {
                $targetDir = "../uploads/claims/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $imagePath = $targetDir . uniqid() . "-" . basename($imageProof['name']);
                move_uploaded_file($imageProof['tmp_name'], $imagePath);
            }
    
            // Insert into claims
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
    
    
    
    

    public function fetchAllClaims() {
        try {
            $sql = "SELECT c.*, u.username AS claimant 
                    FROM claims c
                    JOIN users u ON c.user_id = u.user_id
                    ORDER BY c.created_at DESC";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching claims: " . $e->getMessage());
        }
    }
    

    public function approveClaim($claimId) {
        try {
            $sql = "UPDATE claims SET status = 'Approved' WHERE claim_id = :claim_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':claim_id', $claimId);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error approving claim: " . $e->getMessage());
        }
    }
    
    public function rejectClaim($claimId, $reason) {
        try {
            $sql = "UPDATE claims SET status = 'Rejected', rejection_reason = :reason WHERE claim_id = :claim_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':claim_id', $claimId);
            $stmt->bindParam(':reason', $reason);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error rejecting claim: " . $e->getMessage());
        }
    }

    public function getClaimantId($claimId) {
        $sql = "SELECT claimant_id FROM claims WHERE claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['claimant_id'] ?? null;
    }
    
    public function getReporterId($claimId) {
        $sql = "SELECT user_id FROM lost_items li
                JOIN claims c ON li.item_id = c.item_id
                WHERE c.claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? null;
    }

    public function fetchClaimStatus($itemId, $itemType) {
        $sql = "SELECT status FROM claims WHERE item_id = :item_id AND item_type = :item_type LIMIT 1";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':item_type', $itemType, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchClaimDetails($claimId) {
        try {
            $sql = "
                SELECT 
                    c.claim_id, 
                    u.username AS claimant, 
                    r.username AS reporter, 
                    IF(c.item_type = 'lost', li.description, fi.description) AS item_description,
                    c.text_proof, 
                    c.image_proof
                FROM claims c
                LEFT JOIN users u ON c.user_id = u.user_id
                LEFT JOIN lost_items li ON c.item_id = li.item_id AND c.item_type = 'lost'
                LEFT JOIN found_items fi ON c.item_id = fi.item_id AND c.item_type = 'found'
                LEFT JOIN users r ON (li.user_id = r.user_id OR fi.user_id = r.user_id)
                WHERE c.claim_id = :claim_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':claim_id', $claimId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching claim details: " . $e->getMessage());
        }
    }

    Public function fetchClaimsExcludingReporter($userId) {
        try {
            $sql = "
                SELECT c.*, u.username AS claimant, i.user_id AS reporter_id, r.username AS reporter_name 
                FROM claims c
                LEFT JOIN users u ON c.user_id = u.user_id
                LEFT JOIN lost_items i ON c.item_id = i.item_id
                LEFT JOIN users r ON i.user_id = r.user_id
                WHERE c.user_id != i.user_id -- Exclude claims by the reporter
            ";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching claims: " . $e->getMessage());
        }
    }

    public function markItemAsUnavailable($itemId, $itemType) {
        try {
            // Determine the appropriate table based on the item type
            $tableName = ($itemType === 'lost') ? 'lost_items' : 'found_items';
    
            // Update the item's status to unavailable
            $sql = "UPDATE $tableName SET status = 'unavailable' WHERE item_id = :item_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() === 0) {
                throw new Exception("Failed to update item status for item ID $itemId.");
            }
        } catch (Exception $e) {
            throw new Exception("Error marking item as unavailable: " . $e->getMessage());
        }
    }
    
    
    public function getItemDetails($claimId) {
        try {
            // Fetch item details from the claims table
            $sql = "SELECT item_id, item_type FROM claims WHERE claim_id = :claim_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':claim_id', $claimId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$result) {
                throw new Exception("Item details not found for claim ID $claimId.");
            }
    
            return $result;
        } catch (Exception $e) {
            throw new Exception("Error fetching item details: " . $e->getMessage());
        }
    }

    
    
}
?>
