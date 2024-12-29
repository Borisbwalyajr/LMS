<?php
ob_start();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['referral_code'])) {
        die("Referral code is required.");
    }

    $referralCode = mysqli_real_escape_string($conn, $_POST['referral_code']);

    // Check if the referral code exists
    $sql = "SELECT * FROM referralcode WHERE LOWER(code) = LOWER('$referralCode')";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Code exists; redirect
        header("Location: user-ui/referaluser.php");
        exit();
    } else {
        echo "Invalid referral code. Please try again.";
    }
}

$conn->close();
ob_end_flush();
?>
