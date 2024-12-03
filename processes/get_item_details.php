<?php
require_once '../classes/Item.class.php';
session_start();

header('Content-Type: application/json');

if (!isset($_GET['item_id']) || !isset($_GET['item_type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$item = new Item();
$itemId = $_GET['item_id'];
$itemType = $_GET['item_type'];

try {
    $details = ($itemType === 'lost') 
        ? $item->fetchLostItemDetails($itemId)
        : $item->fetchFoundItemDetails($itemId);
    
    echo json_encode($details);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?> 