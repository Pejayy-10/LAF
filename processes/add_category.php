<?php
require_once '../classes/Database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['category_name'];

    try {
        $db = new Database();
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
        $query = $db->connect()->prepare($sql);
        $query->bindValue(1, $categoryName);
        $query->execute();

        echo 'success';
    } catch (PDOException $e) {
        echo 'error'; // Consider logging the error for debugging
    }
}