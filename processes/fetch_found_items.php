<?php
require_once '../classes/Database.class.php';

try {
    $db = new Database();
    $sql = "SELECT item_id, category, description, date_found, location, status FROM found_items ORDER BY date_found DESC";
    $query = $db->connect()->prepare($sql);
    $query->execute();
    $foundItems = $query->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($foundItems);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
