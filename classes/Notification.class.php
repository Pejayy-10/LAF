<?php
require_once 'Database.class.php';

class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addNotification($userId, $message, $type = 'info', $link = null) {
        try {
            $sql = "INSERT INTO notifications (user_id, message, type, link) VALUES (:user_id, :message, :type, :link)";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':link', $link, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error adding notification: " . $e->getMessage());
        }
    }

    public function fetchNotifications($userId) {
        try {
            $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching notifications: " . $e->getMessage());
        }
    }
}
