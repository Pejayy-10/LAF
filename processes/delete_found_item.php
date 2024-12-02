<?php
require_once '../classes/Database.class.php';

if (isset($_GET['id'])) {
    $itemId = intval($_GET['id']);

    try {
        $db = new Database();
        $sql = "DELETE FROM found_items WHERE item_id = :id";
        $query = $db->connect()->prepare($sql);
        $query->bindParam(':id', $itemId, PDO::PARAM_INT);
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
