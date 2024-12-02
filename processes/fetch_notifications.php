<?php
session_start();
require_once '../classes/Notification.class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access!']);
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $notification = new Notification();
    $notifications = $notification->fetchNotifications($userId);

    echo json_encode(['success' => true, 'data' => $notifications]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
