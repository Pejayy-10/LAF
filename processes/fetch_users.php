<?php
require_once '../classes/Database.class.php';

try {
    $db = new Database();
    $sql = "SELECT user_id, username, email, role FROM users";
    $query = $db->connect()->prepare($sql);
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
