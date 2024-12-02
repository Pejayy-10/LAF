<?php
require_once '../classes/Database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'];

    try {
        $db = new Database();
        $sql = "DELETE FROM categories WHERE category_id = ?";
        $query = $db->connect()->prepare($sql);
        $query->bindValue(1, $categoryId);
        $query->execute();

        echo 'success';
    } catch (PDOException $e) {
        echo 'error';
    }
}