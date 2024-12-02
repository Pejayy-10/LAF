<?php
require_once 'Database.class.php';

class Chat {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getClaimDetails($claimId) {
        $sql = "SELECT c.claim_id, 
                       u.username AS partner_username
                FROM claims c
                JOIN users u ON u.user_id = (
                    CASE 
                        WHEN c.claimant_id = :user_id THEN (SELECT reporter_id FROM lost_items WHERE item_id = c.item_id)
                        ELSE c.claimant_id 
                    END
                )
                WHERE c.claim_id = :claim_id";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMessage($claimId, $senderId, $message) {
        $sql = "INSERT INTO chats (claim_id, sender_id, message) VALUES (:claim_id, :sender_id, :message)";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->bindParam(':sender_id', $senderId);
        $stmt->bindParam(':message', $message);
        return $stmt->execute();
    }

    public function fetchMessages($claimId) {
        $sql = "SELECT c.message, c.created_at, u.username AS sender
                FROM chats c
                JOIN users u ON c.sender_id = u.user_id
                WHERE c.claim_id = :claim_id
                ORDER BY c.created_at ASC";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':claim_id', $claimId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
