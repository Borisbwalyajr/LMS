<?php
include "../connection.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You are currently not logged in!');
            window.location.href='../index.html';
          </script>";
    exit;
}

// Verify database connection
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch the user's active loan with "credited" status
try {
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
} catch (Exception $e) {
    die("Error fetching loan data: " . $e->getMessage());
}

// Handle repayment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentAmount = filter_input(INPUT_POST, 'payment_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if ($paymentAmount > 0 && $paymentAmount <= $activeLoan['repayment']) {
        try {
            $pdo->beginTransaction();

            $newBalance = $activeLoan['repayment'] - $paymentAmount;
            $status = $newBalance == 0 ? 'repaid' : 'credited';

            $updateStmt = $pdo->prepare("UPDATE loan_applications SET repayment = ?, status = ? WHERE loan_id = ?");
            $updateStmt->execute([$newBalance, $status, $activeLoan['loan_id']]);

            $paymentStmt = $pdo->prepare("INSERT INTO payments (loan_id, user_id, payment_amount, payment_date) VALUES (?, ?, ?, NOW())");
            $paymentStmt->execute([$activeLoan['loan_id'], $_SESSION['user_id'], $paymentAmount]);

            $pdo->commit();

            echo "<script>
                    alert('Payment successful! Outstanding balance: ZMW $newBalance');
                    window.location.href='index.php';
                  </script>";
        } catch (Exception $e) {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Repayment</title>
    <link rel="stylesheet" href="../path/to/bootstrap.min.css"> <!-- Update path -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .repayment-card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .outstanding-balance {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card repayment-card p-4">
                    <h3 class="text-center text-primary mb-4"><i class="fas fa-coins"></i> Loan Repayment</h3>
                    <p class="text-center">Your outstanding loan balance is:</p>
                    <p class="text-center outstanding-balance">ZMW <?= number_format($activeLoan['repayment'], 2); ?></p>
                    <form method="POST">
                        <div class="form-group mb-3">
                            <label for="paymentAmount" class="form-label">Enter Payment Amount (ZMW):</label>
                            <input type="number" class="form-control" id="paymentAmount" name="payment_amount" step="0.01" min="0" max="<?= $activeLoan['repayment']; ?>" required placeholder="Enter payment amount">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Make Payment</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
