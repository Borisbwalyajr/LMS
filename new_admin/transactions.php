<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
</head>

<body>
    
   <?php
   include 'sidebar.php';
   ?>

    <section class="dashboard">
        <div class="container">
            
        <?php
// payments.php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'loan_db'); // Update with your database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $phone_number = $_POST['phone_number'];
    $signature = $_POST['signature'];

    // Insert the payment into the database with 'pending' status
    $stmt = $conn->prepare("INSERT INTO transactions (amount, purpose, date, phone_number, signature, status) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 'pending';
    $stmt->bind_param('dsssss', $amount, $purpose, $date, $phone_number, $signature, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Payment submitted successfully and is pending approval.');</script>";
    } else {
        echo "<script>alert('Failed to submit payment: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-container input, .form-container textarea, .form-container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Record Payment</h2>
        <form method="POST" action="">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" required placeholder="Enter the amount">

            <label for="purpose">Purpose</label>
            <textarea name="purpose" id="purpose" rows="3" required placeholder="Enter the purpose"></textarea>

            <label for="date">Date</label>
            <input type="date" name="date" id="date" required>

            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" required placeholder="Enter phone number">

            <label for="signature">Signature</label>
            <input type="text" name="signature" id="signature" required placeholder="Enter your signature">

            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>



        </div>
    </section>

    <script src="index.js"></script>
    
    <!-- Sources for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>