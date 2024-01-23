<?php

require __DIR__ . '/../vendor/autoload.php';
session_start();

# recipes dataset: https://frosch.cosy.sbg.ac.at/datasets/json/recipes
# database plugin https://github.com/pgvector/pgvector

$messages = \App\History::get($_GET['session'] ?? session_id());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 h-screen flex items-center justify-center">
<div class="flex flex-col max-w-2xl w-full h-screen bg-white rounded p-4">
    <div id="chat-messages" class="flex-1 overflow-y-auto">
        <!-- Chat messages will be loaded here -->
    </div>
    <div class="border-t p-3">
        <!-- Message input -->
        <form id="chat-form">
            <input type="text" id="chat-input" name="message" placeholder="Type a message..."
                   class="w-full active:border-0 p-2 rounded">
        </form>
    </div>
</div>
<script>
    document.getElementById('chat-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const messageInput = document.getElementById('chat-input');
        const message = messageInput.value;

        // Display the user's message immediately
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.innerHTML += `<div class="p-2 rounded bg-white mb-2">
            <span class="bg-gray-200 px-2 py-1 rounded-full text-gray-800">User:</span>
            ${message}
        </div>`;
        chatMessages.innerHTML += `<div class="p-2 rounded mb-2 bg-blue-400 text-white">
            <span class="bg-gray-200  px-2 py-1 rounded-full text-gray-800">Assistant:</span>
            <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
    </svg>
    <span class="sr-only">Loading...</span>
        </div>`;

        // Clear the input field
        messageInput.value = '';

        // Send message to the server
        fetch('server.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'message=' + encodeURIComponent(message)
        }).then(function() {
            loadChatHistory();
        })
            .catch(error => console.error('Error:', error));
    });

    // Function to load chat history
    function loadChatHistory() {
        fetch('server.php')
            .then(response => response.json())
            .then(messages => {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML = '';
                messages.forEach(message => {
                    chatMessages.innerHTML += `<div class="p-2 rounded ${message.role === 'user' ? 'bg-white' : 'bg-blue-400 text-white'} mb-2">
                        <span class="bg-gray-200 px-2 py-1 rounded-full text-gray-800">${message.role}:</span>
                        <pre class="whitespace-pre-wrap py-2 my-2">${message.content}</pre>
                    </div>`;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Initial chat history load
    loadChatHistory();
</script>

</body>
</html>

