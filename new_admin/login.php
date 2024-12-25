<?php
// Start the session
session_start();

// Database connection
$servername = "localhost"; // Replace with your database host
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "loan_db"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // SQL query to check user credentials
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pass); // "ss" for two string parameters
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Valid credentials, start a session
        $_SESSION['username'] = $user;
        header("Location: panel.php"); // Redirect to admin dashboard
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid Username or Password'); window.location.href='index.html';</script>";
    }
}
$conn->close();
?>
