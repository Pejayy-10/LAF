<?php
header('Content-Type: application/json');
session_start();

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Unauthorized access.");
    }

    // Debugging: Log received data
    file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);
    file_put_contents('debug_log.txt', print_r($_FILES, true), FILE_APPEND);

    $userId = $_SESSION['user_id'];
    $itemId = $_POST['item_id'] ?? null;
    $itemType = $_POST['item_type'] ?? null;
    $textProof = $_POST['text_proof'] ?? null;
    $imageProof = $_FILES['image_proof'] ?? null;

    if (!$itemId || !$itemType || !$textProof || !$imageProof) {
        throw new Exception("Missing required fields.");
    }

    echo json_encode(['success' => true, 'message' => 'Claim submitted successfully.']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
