<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();

# Endpoint for handling chat messages and history
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # Send message logic
    if (isset($_POST['message'])) {
        \App\Chat::reply($_GET['session'] ?? session_id(), $_POST['message']);
    }
    return;
}
# Load chat history logic
$messages = \App\History::get($_GET['session'] ?? session_id());
echo json_encode($messages);

