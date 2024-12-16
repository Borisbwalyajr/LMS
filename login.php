<?php
include_once("./connection.php");

// Check if the form is submitted
if (isset($_POST['login'])) {
    try {
        // Validate form inputs
        $nrc = trim($_POST['nrc'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nrc) || empty($password)) {
            echo "<script>
                    alert('Please fill in all fields.');
                    window.location.href='./index.php';
                  </script>";
            exit;
        }

        // Prepare and execute SQL query
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id_number = ?");
        $stmt->execute([$nrc]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user and password
        if ($user && password_verify($password, $user['password'])) {
            echo "<script>
                    alert('You have Successfully Logged In');
                    window.location.href='./user-ui/index.php';
                  </script>";
                  session_start();
                  $_SESSION['user_id'] = $user['id_number'];
                  header('Location: ./user-ui/index.php');
                  exit;
        } else {
            echo "<script>
                    alert('Invalid NRC or password.');
                    window.location.href='./index.php';
                  </script>";
        }
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
    }
}
?>
