<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loan_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    // SQL query to check user credentials and retrieve status
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $user_id = $row['id']; // Assuming the table has an 'id' column
        $status = $row['type']; // Assuming the table has a 'type' column

        // Store user info in the session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $user;
        $_SESSION['type'] = $status;

        // Redirect based on user type
        if ($status == 1) {
            header("Location: admin_dashboard.php");
        } elseif ($status == 2) {
            header("Location: loan_officer/staff_dashboard.php");
        } else {
            echo "<script>alert('User type not recognized'); window.location.href='index.html';</script>";
        }
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid Username or Password'); window.location.href='index.html';</script>";
    }
}

$conn->close();
?>
