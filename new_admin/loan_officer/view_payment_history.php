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

<?php
include 'connection.php';

if (isset($_GET['loan_id'])) {
    $loanId = $_GET['loan_id'];

    // Fetch payment history for the loan ID
    $sql = "SELECT * FROM payments WHERE loan_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$loanId]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color:rgb(116, 150, 223);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            background-color: #fefefe;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color:rgb(13, 11, 146);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9f5e9;
        }

        .no-data {
            text-align: center;
            font-size: 18px;
            color: #888;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color:rgb(28, 6, 105);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-button:hover {
            background-color:rgb(61, 87, 141);
        }
    </style>
</head>

<body>
    
    <div class="container">
        <?php
        if ($stmt->rowCount() > 0) {
            echo "<h1>Payment History for Loan ID: " . htmlspecialchars($loanId) . "</h1>";
            echo "<table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount (ZMW)</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['payment_date']) . "</td>
                        <td>" . htmlspecialchars(number_format($row['payment_amount'], 2)) . "</td>
                      </tr>";
            }

            echo "</tbody>
                </table>";
        } else {
            echo "<p class='no-data'>No payment history found for Loan ID: " . htmlspecialchars($loanId) . "</p>";
        }
        ?>
        <a href="loan_officer.php" class="back-button">Back to Dashboard</a>
    </div>
</body>

</html>
<?php
} else {
    echo "<p>Invalid Loan ID.</p>";
}
?>


        </div>
    </section>

    <script src="index.js"></script>
    
    <!-- Sources for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>