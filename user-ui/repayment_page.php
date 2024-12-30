<?php
include "../connection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You are currently not logged in!');
            window.location.href='../index.html';
          </script>";
    exit;
}

// Fetch the user's active loan with "credited" status
$stmt = $pdo->prepare("SELECT * FROM loan_applications WHERE nrc = ? AND status = 'credited'");
$stmt->execute([$_SESSION['user_id']]);
$activeLoan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activeLoan) {
    echo "<script>
            alert('You do not have an outstanding loan!');
            window.location.href='index.php';
          </script>";
    exit;
}

// Handle repayment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentAmount = filter_input(INPUT_POST, 'payment_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if ($paymentAmount > 0 && $paymentAmount <= $activeLoan['repayment']) {
        try {
            // Begin a transaction
            $pdo->beginTransaction();

            // Update the outstanding balance in loan_applications table
            $newBalance = $activeLoan['repayment'] - $paymentAmount;
            $status = $newBalance == 0 ? 'repaid' : 'credited';

            $updateStmt = $pdo->prepare("UPDATE loan_applications SET repayment = ?, status = ? WHERE loan_id = ?");
            $updateStmt->execute([$newBalance, $status, $activeLoan['loan_id']]);

            // Insert payment record into payment table
            $paymentStmt = $pdo->prepare("INSERT INTO payments (loan_id, user_id, payment_amount, payment_date) VALUES (?, ?, ?, NOW())");
            $paymentStmt->execute([$activeLoan['loan_id'], $_SESSION['user_id'], $paymentAmount]);

            // Commit the transaction
            $pdo->commit();

            echo "<script>
                    alert('Payment successful! Outstanding balance: ZMW $newBalance');
                    window.location.href='index.php';
                  </script>";
        } catch (Exception $e) {
            // Roll back the transaction in case of an error
            $pdo->rollBack();
            echo "<script>
                    alert('Failed to process your payment. Please try again.');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid payment amount. Please ensure it does not exceed your outstanding balance.');
              </script>";
    }
}
?>
