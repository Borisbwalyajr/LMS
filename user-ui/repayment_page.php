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
        // Update the outstanding balance
        $newBalance = $activeLoan['repayment'] - $paymentAmount;
        $status = $newBalance == 0 ? 'repaid' : 'credited';

        $updateStmt = $pdo->prepare("UPDATE loan_applications SET repayment = ?, status = ? WHERE loan_id = ?");
        if ($updateStmt->execute([$newBalance, $status, $activeLoan['loan_id']])) {
            echo "<script>
                    alert('Payment successful! Outstanding balance: ZMW $newBalance');
                    window.location.href='index.php';
                  </script>";
        } else {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
    body {
        background: linear-gradient(135deg, #f0f4f8, #d9e6f3);
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background: #ffffff;
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .card {
        border: none;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        padding: 20px;
        font-size: 1.5rem;
        border-bottom: none;
        text-transform: uppercase;
    }

    .card-body {
        padding: 30px;
    }

    .card-body h5 {
        font-size: 1.25rem;
        margin-bottom: 15px;
        color: #333;
        font-weight: bold;
    }

    .card-body p {
        font-size: 1rem;
        color: #666;
    }

    .list-group-item {
        font-size: 0.95rem;
        background-color: #f9f9f9;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        padding: 10px 15px;
    }

    .list-group-item strong {
        color: #007bff;
    }

    .form-label {
        font-weight: bold;
        color: #333;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        box-shadow: none;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        background: #007bff;
        border: none;
        padding: 10px;
        font-size: 1rem;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .card-footer {
        background-color: #f9f9f9;
        padding: 20px;
    }

    .card-footer p {
        margin: 0;
        font-size: 0.9rem;
        color: #999;
    }
</style>

</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h3>Loan Repayment</h3>
            </div>
            <div class="card-body">
                <h5>Hello, <?php echo htmlspecialchars($_SESSION['user_id']); ?></h5>
                <p>Below are the details of your outstanding loan:</p>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Loan ID:</strong> <?php echo $activeLoan['loan_id']; ?></li>
                    <li class="list-group-item"><strong>Amount Borrowed:</strong> ZMW <?php echo number_format($activeLoan['amount'], 2); ?></li>
                    <li class="list-group-item"><strong>Outstanding Balance:</strong> ZMW <?php echo number_format($activeLoan['repayment'], 2); ?></li>
                    <li class="list-group-item"><strong>Due Date:</strong> <?php echo $activeLoan['due_date']; ?></li>
                </ul>
                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Enter Payment Amount (ZMW):</label>
                        <input type="number" step="0.01" class="form-control" id="paymentAmount" name="payment_amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit Payment</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="text-muted">Thank you for staying on top of your payments!</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
