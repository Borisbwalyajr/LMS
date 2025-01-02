<?php
$conn = new mysqli("localhost", "root", "", "loan_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sender = $_POST['sender'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO messages (sender, message) VALUES (?, ?)");
$stmt->bind_param("ss", $sender, $message);
$stmt->execute();

echo "Message sent successfully.";
?>
