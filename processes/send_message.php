<?php
session_start();
require_once '../classes/Chat.class.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized access.');
}

$chat = new Chat();
$claimId = $_POST['claim_id'];
$senderId = $_SESSION['user_id'];
$message = trim($_POST['message']);

if ($chat->addMessage($claimId, $senderId, $message)) {
    echo 'Message sent.';
} else {
    http_response_code(500);
    echo 'Failed to send message.';
}
?>
