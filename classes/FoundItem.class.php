<?php
require_once 'Database.class.php';

class FoundItem {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function add($userId, $category, $description, $dateFound, $location, $image) {
        $sql = "INSERT INTO found_items (user_id, category, description, date_found, location, image, status) 
                VALUES (:user_id, :category, :description, :date_found, :location, :image, 'unclaimed')";
        $query = $this->db->prepare($sql);
        return $query->execute([
            'user_id' => $userId,
            'category' => $category,
            'description' => $description,
            'date_found' => $dateFound,
            'location' => $location,
            'image' => $image
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM found_items ORDER BY date_found DESC";
        $query = $this->db->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($itemId) {
        $sql = "DELETE FROM found_items WHERE item_id = :item_id";
        $query = $this->db->prepare($sql);
        return $query->execute(['item_id' => $itemId]);
    }
}
?>
