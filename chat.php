<?php
session_start();
require_once 'classes/Chat.class.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: account/login.php");
    exit;
}

$claimId = $_GET['claim_id'] ?? null;

if (!$claimId) {
    die("Invalid claim ID.");
}

$userId = $_SESSION['user_id'];
$chat = new Chat();
$claimDetails = $chat->getClaimDetails($claimId);

if (!$claimDetails) {
    die("Invalid claim or unauthorized access.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Chat with <?= htmlspecialchars($claimDetails['partner_username']) ?></h2>
    <div id="chat-box" class="border p-3 mb-3" style="height: 400px; overflow-y: scroll;">
        <!-- Messages will load here -->
    </div>
    <form id="chat-form">
        <input type="hidden" name="claim_id" value="<?= htmlspecialchars($claimId) ?>">
        <div class="input-group">
            <textarea id="message" name="message" class="form-control" placeholder="Type your message..." required></textarea>
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        loadChatMessages();

        // Submit new message
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();
            const message = $('#message').val();
            if (message.trim() === '') return;

            $.ajax({
                url: 'processes/send_message.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function () {
                    $('#message').val('');
                    loadChatMessages();
                },
                error: function () {
                    alert('Failed to send message.');
                }
            });
        });

        // Load chat messages every 2 seconds
        setInterval(loadChatMessages, 2000);
    });

    function loadChatMessages() {
        const claimId = <?= htmlspecialchars($claimId) ?>;
        $.ajax({
            url: `processes/fetch_messages.php?claim_id=${claimId}`,
            type: 'GET',
            success: function (data) {
                $('#chat-box').html(data);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            },
            error: function () {
                console.error('Failed to load messages.');
            }
        });
    }
</script>
</body>
</html>
