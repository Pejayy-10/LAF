<?php
session_start();
require_once '../classes/Item.class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access! Please login first.']);
    exit;
}

try {
    $item = new Item();
    $userId = $_SESSION['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $dateLost = $_POST['date_lost'];
    $image = $_FILES['image'];

    $result = $item->addLostItem($userId, $category, $description, $location, $dateLost, $image);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Lost item reported successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to report lost item.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
