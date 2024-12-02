<?php
require_once '../classes/Database.class.php';

try {
    $db = new Database();
    $sql = "SELECT item_id, category, description, date_lost, location, status FROM lost_items ORDER BY date_lost DESC";
    $query = $db->connect()->prepare($sql);
    $query->execute();
    $lostItems = $query->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($lostItems);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
