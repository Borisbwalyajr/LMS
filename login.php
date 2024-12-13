<?php
    include_once("./connection.php");
    // Check if the form is submitted
    if (isset($_POST['login'])) {
        try {
            // Get NRC and password from POST request
            $nrc = $_POST['nrc'] ?? '';
            $password = $_POST['password'] ?? '';

            // Hash the password using MD5
            $hashedPassword = md5($password);

            // Prepare and execute SQL query
            $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id_number = ? AND password = ?");
            $stmt->execute([$nrc, $hashedPassword]);

            // Check if a matching row is found
            if ($stmt->rowCount() > 0) {
                echo(
                    "
                    <script>
                        alert('You have Successfully Logged In');
                        window.location.href='./user-ui/index.html';
                    </script>
                    "
                );
            } else {
                echo(
                    "
                    <script>
                        alert('Invalid NRC or password.');
                        window.location.href='./index.html';
                    </script>
                    "
                );
            }
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }
    }
?>
