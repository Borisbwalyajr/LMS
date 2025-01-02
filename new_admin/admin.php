<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>FV Loan Lenders</title>
    <style>
        .chat-box .message {
    margin: 10px 0;
    padding: 10px;
    border-radius: 10px;
    max-width: 75%;
    word-wrap: break-word;
}

.chat-box .message.user {
    background: #0078d7;
    color: #fff;
    margin-left: auto;
}

.chat-box .message.admin {
    background: #ddd;
    color: #333;
    margin-right: auto;
}

        </style>
</head>

<body>
    
   <?php
   include 'sidebar.php';
   ?>

    <section class="dashboard">
        <div class="container">
            
    <div class="chat-container">
        <div class="chat-header">
            <h2>Chat with Users</h2>
        </div>
        <div id="chat-box" class="chat-box">
            <!-- User and admin messages will be dynamically loaded here -->
        </div>
        <div class="chat-input">
            <input type="text" id="message" placeholder="Type your response..." />
            <button id="send-btn">Send</button>
        </div>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("message");
    const sendBtn = document.getElementById("send-btn");

    // Function to fetch all messages
    function fetchMessages() {
        fetch("fetch_messages.php")
            .then(response => response.json())
            .then(messages => {
                chatBox.innerHTML = ""; // Clear the chat box
                messages.forEach(msg => {
                    const messageDiv = document.createElement("div");
                    messageDiv.classList.add("message", msg.sender.toLowerCase());
                    messageDiv.innerHTML = `
                        <strong>${msg.sender}:</strong> ${msg.message}
                        <small>${new Date(msg.timestamp).toLocaleString()}</small>
                    `;
                    chatBox.appendChild(messageDiv);
                });
                chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
            });
    }

    // Send admin response
    sendBtn.addEventListener("click", () => {
        const message = messageInput.value.trim();
        if (message) {
            fetch("send_message.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `message=${message}`,
            })
            .then(() => {
                messageInput.value = ""; // Clear input
                fetchMessages(); // Refresh messages
            });
        }
    });

    // Fetch messages every 2 seconds
    setInterval(fetchMessages, 2000);
});

    </script>

        </div>
    </section>

    <script src="index.js"></script>
    
    <!-- Sources for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>