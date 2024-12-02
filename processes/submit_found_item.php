<?php
session_start();
require_once '../classes/Item.class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access! Please log in first.']);
    exit;
}

try {
    $item = new Item();
    $userId = $_SESSION['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $dateFound = $_POST['date_found'];
    $image = $_FILES['image'];

    $result = $item->addFoundItem($userId, $category, $description, $location, $dateFound, $image);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Found item reported successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to report the found item.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
