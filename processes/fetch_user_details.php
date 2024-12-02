<?php
require_once '../classes/Database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['id'];

    try {
        $db = new Database();
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $query = $db->connect()->prepare($sql);
        $query->bindValue(1, $userId);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        echo 'Error fetching user details: ' . $e->getMessage();
    }
}
?>