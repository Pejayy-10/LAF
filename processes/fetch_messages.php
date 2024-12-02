<?php
session_start();
require_once '../classes/Chat.class.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized access.');
}

$chat = new Chat();
$claimId = $_GET['claim_id'];

$messages = $chat->fetchMessages($claimId);
foreach ($messages as $message) {
    echo "<div><strong>{$message['sender']}:</strong> {$message['message']} <small>({$message['created_at']})</small></div>";
}
?>
