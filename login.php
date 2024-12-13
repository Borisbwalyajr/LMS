<?php
    include_once("./connection.php");
    // Check if the form is submitted
    if (isset($_POST['login'])) {
        // Retrieve NRC and password from the form
        $nrc = $_POST['nrc'];
        $password = $_POST['password'];

        // Hash the password using MD5
        $hashedPassword = md5($password);

        try {
            // Prepare the SQL query
            $stmt = $pdo->prepare("SELECT * FROM registration WHERE nrc = :nrc AND password = :password");

            // Bind parameters
            $stmt->bindParam(':nrc', $nrc);
            $stmt->bindParam(':password', $hashedPassword);

            // Execute the query
            $stmt->execute();

            // Check if a matching row is found
            if ($stmt->rowCount() > 0) {
                echo "Login successful!";
            } else {
                echo "Invalid NRC or password.";
            }
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }
    }
?>
