<?php
require_once '../classes/Database.class.php';

try {
    $db = new Database();
    $sql = "SELECT * FROM categories";
    $query = $db->connect()->prepare($sql);
    $query->execute();
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>