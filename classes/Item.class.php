<?php
require_once 'Database.class.php';

class Item {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Fetch Categories
    public function fetchCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name ASC";
        $query = $this->db->connect()->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch Lost Items with filters and category names
    public function fetchLostItems($categories = []) {
        $sql = "SELECT l.*, u.username AS reporter_name, 
                (SELECT category_name FROM categories WHERE category_id = l.category) as category_name
                FROM lost_items l 
                JOIN users u ON l.user_id = u.user_id";
        
        if (!empty($categories)) {
            $placeholders = str_repeat('?,', count($categories) - 1) . '?';
            $sql .= " WHERE l.category IN ($placeholders)";
        }
        
        $sql .= " ORDER BY l.date_lost DESC";
        
        $query = $this->db->connect()->prepare($sql);
        
        if (!empty($categories)) {
            $query->execute($categories);
        } else {
            $query->execute();
        }
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch Found Items with filters and category names
    public function fetchFoundItems($categories = []) {
        $sql = "SELECT f.*, u.username AS reporter_name,
                (SELECT category_name FROM categories WHERE category_id = f.category) as category_name
                FROM found_items f 
                JOIN users u ON f.user_id = u.user_id";
        
        if (!empty($categories)) {
            $placeholders = str_repeat('?,', count($categories) - 1) . '?';
            $sql .= " WHERE f.category IN ($placeholders)";
        }
        
        $sql .= " ORDER BY f.date_found DESC";
        
        $query = $this->db->connect()->prepare($sql);
        
        if (!empty($categories)) {
            $query->execute($categories);
        } else {
            $query->execute();
        }
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLostItem($userId, $category, $description, $location, $dateLost, $image) {
        try {
            $imagePath = null;
            if ($image && $image['tmp_name']) {
                $targetDir = __DIR__ . '/../uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = uniqid() . '-' . basename($image['name']);
                $imagePath = 'uploads/' . $filename;
                move_uploaded_file($image['tmp_name'], $targetDir . $filename);
            }
    
            $sql = "INSERT INTO lost_items (user_id, category, description, location, date_lost, image) 
                    VALUES (:user_id, :category, :description, :location, :date_lost, :image)";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':date_lost', $dateLost);
            $stmt->bindParam(':image', $imagePath);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error adding lost item: " . $e->getMessage());
        }
    }
    
    public function addFoundItem($userId, $category, $description, $location, $dateFound, $image) {
        try {
            $imagePath = null;
            if ($image && $image['tmp_name']) {
                $targetDir = __DIR__ . '/../uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = uniqid() . '-' . basename($image['name']);
                $imagePath = 'uploads/' . $filename;
                move_uploaded_file($image['tmp_name'], $targetDir . $filename);
            }
    
            $sql = "INSERT INTO found_items (user_id, category, description, location, date_found, image) 
                    VALUES (:user_id, :category, :description, :location, :date_found, :image)";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':date_found', $dateFound);
            $stmt->bindParam(':image', $imagePath);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error adding found item: " . $e->getMessage());
        }
    }

    public function fetchClaimStatus($itemId, $itemType) {
        $sql = "SELECT status FROM claims WHERE item_id = :item_id AND item_type = :item_type";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->bindParam(':item_type', $itemType);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchLostItemDetails($itemId) {
        try {
            $sql = "SELECT l.*, 
                    (SELECT category_name FROM categories WHERE category_id = l.category) as category_name,
                    u.username as reporter_name
                    FROM lost_items l 
                    LEFT JOIN users u ON l.user_id = u.user_id
                    WHERE l.item_id = :item_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching lost item details: " . $e->getMessage());
        }
    }

    public function fetchFoundItemDetails($itemId) {
        try {
            $sql = "SELECT f.*, 
                    (SELECT category_name FROM categories WHERE category_id = f.category) as category_name,
                    u.username as reporter_name
                    FROM found_items f 
                    LEFT JOIN users u ON f.user_id = u.user_id
                    WHERE f.item_id = :item_id";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching found item details: " . $e->getMessage());
        }
    }

    public function fetchItemDetails($itemId) {
        $sql = "SELECT i.*, c.category_name, u.full_name as reporter_name
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.category_id
                LEFT JOIN users u ON i.reporter_id = u.user_id
                WHERE i.item_id = ?";
        
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->execute([$itemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
