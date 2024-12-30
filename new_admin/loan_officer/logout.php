<?php
session_start();

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    // Log the user out by destroying the session
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Redirect to the login page with a success message
    header("Location: login.php?logout=success");
    exit();
} else {
    // If no session exists, redirect to the login page
    header("Location: index.html");
    exit();
}
?>
