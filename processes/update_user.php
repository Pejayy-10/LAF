<?php
require_once '../classes/Database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $db = new Database();

        // Checking sa duplication
        $checkSql = "SELECT user_id FROM users WHERE (username = ? OR email = ?) AND user_id != ?";
        $checkQuery = $db->connect()->prepare($checkSql);
        $checkQuery->execute([$username, $email, $userId]);

        if ($checkQuery->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username or email already exists.']);
            exit();
        }

        // Update the user
        $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?";
        $query = $db->connect()->prepare($sql);
        $query->execute([$username, $email, $role, $userId]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
