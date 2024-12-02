<?php
require_once '../classes/Database.class.php';

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    try {
        $db = new Database();
        $sql = "DELETE FROM users WHERE user_id = :id";
        $query = $db->connect()->prepare($sql);
        $query->bindParam(':id', $userId, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'failure';
        }
    } catch (PDOException $e) {
        echo 'error';
    }
}
?>
