
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FV Loan Lenders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.chat-container {
    width: 100%;
    max-width: 500px;
    margin: 50px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.chat-header {
    background: #0078d7;
    color: #fff;
    padding: 15px;
    text-align: center;
}

.chat-box {
    height: 400px;
    overflow-y: auto;
    padding: 10px;
    background: #f9f9f9;
    border-bottom: 1px solid #ddd;
}

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

.chat-input {
    display: flex;
    padding: 10px;
    background: #f1f1f1;
    border-top: 1px solid #ddd;
}

.chat-input input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.chat-input button {
    padding: 10px 15px;
    border: none;
    background: #0078d7;
    color: #fff;
    margin-left: 5px;
    border-radius: 5px;
    cursor: pointer;
}

.chat-input button:hover {
    background: #005bb5;
}

        </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>Chat with Admin</h2>
        </div>
        <div id="chat-box" class="chat-box">
            <!-- Messages will appear here -->
        </div>
        <div class="chat-input">
            <input type="text" id="message" placeholder="Type your message..." />
            <button id="send-btn">Send</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("message");
    const sendBtn = document.getElementById("send-btn");
    const sender = "User"; // Change to "Admin" for the admin panel

    // Function to fetch messages
    function fetchMessages() {
        fetch("fetch_messages.php")
            .then(response => response.json())
            .then(messages => {
                chatBox.innerHTML = ""; // Clear the chat box
                messages.forEach(msg => {
                    const messageDiv = document.createElement("div");
                    messageDiv.classList.add("message", msg.sender.toLowerCase());
                    messageDiv.textContent = `${msg.sender}: ${msg.message}`;
                    chatBox.appendChild(messageDiv);
                });
                chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
            });
    }

    // Send message
    sendBtn.addEventListener("click", () => {
        const message = messageInput.value.trim();
        if (message) {
            fetch("send_message.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `sender=${sender}&message=${message}`,
            }).then(() => {
                messageInput.value = ""; // Clear input
                fetchMessages(); // Refresh messages
            });
        }
    });

    // Fetch messages every 2 seconds
    setInterval(fetchMessages, 2000);
});

    </script>