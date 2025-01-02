<?php
$conn = new mysqli("localhost", "root", "", "loan_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT sender, message FROM messages ORDER BY timestamp ASC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
