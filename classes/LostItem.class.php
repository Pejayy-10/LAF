<?php
require_once 'Database.class.php';

class LostItem {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function add($userId, $category, $description, $dateLost, $location, $image) {
        $sql = "INSERT INTO lost_items (user_id, category, description, date_lost, location, image, status) 
                VALUES (:user_id, :category, :description, :date_lost, :location, :image, 'unresolved')";
        $query = $this->db->prepare($sql);
        return $query->execute([
            'user_id' => $userId,
            'category' => $category,
            'description' => $description,
            'date_lost' => $dateLost,
            'location' => $location,
            'image' => $image
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM lost_items ORDER BY date_lost DESC";
        $query = $this->db->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($itemId) {
        $sql = "DELETE FROM lost_items WHERE item_id = :item_id";
        $query = $this->db->prepare($sql);
        return $query->execute(['item_id' => $itemId]);
    }
}
?>
