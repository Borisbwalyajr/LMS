<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles for the Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: 'Arial', sans-serif;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        th:hover {
            background-color: #45a049;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e1e1;
        }

        tr td {
            font-size: 14px;
            color: #333;
        }

        .action-btn {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .action-btn.approve {
            background-color: #28a745;
            color: white;
        }

        .action-btn.approve:hover {
            background-color: #218838;
        }

        .action-btn.dismiss {
            background-color: #dc3545;
            color: white;
        }

        .action-btn.dismiss:hover {
            background-color: #c82333;
        }

        .action-btn.delete {
            background-color: #ff6f61;
            color: white;
        }

        .action-btn.delete:hover {
            background-color: #ff3e30;
        }

        #search {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        #search:focus {
            border-color: #4CAF50;
            outline: none;
        }

        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 12px;
            }

            #search {
                width: 100%;
            }
        }

        /* Modal styles */
        #edit-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            z-index: 1000;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #edit-modal h3 {
            margin-bottom: 15px;
        }

        #edit-modal p {
            margin: 5px 0;
            font-size: 14px;
        }

        #edit-modal .action-btn {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <section class="dashboard">
        <div class="container">
            <?php
            include 'connection.php';

            $sql = "SELECT * FROM transactions WHERE status = 'paid'";
            $stmt = $pdo->query($sql);

            if (isset($_POST['delete_id'])) {
                $loanId = $_POST['delete_id'];
                $deleteTransaction = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
                $deleteTransaction->execute([$loanId]);
                echo "<script>alert('Transaction deleted successfully.'); window.location.reload();</script>";
            }
            ?>

            <h2>Transactions History</h2>
            <input type="text" id="search" placeholder="Search by ID or NRC">
            <table id="loans-table">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Phone Number</th>
                        <th>To</th>
                        <th>Paid Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>ZMK" . htmlspecialchars($row['amount']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['signature']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>
                                <button class='action-btn dismiss' onclick='showTransactionDetails(" . json_encode($row) . ")'>View</button>
                                <form method='POST' style='display:inline;'>
                                    <button type='submit' name='delete_id' value='" . htmlspecialchars($row['id']) . "' class='action-btn delete'>Delete</button>
                                </form>
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No Transactions Found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- View Modal -->
            <div id="edit-modal">
                <h3>Transaction Details</h3>
                <p><strong>Amount:</strong> <span id="view-amount"></span></p>
                <p><strong>Purpose:</strong> <span id="view-purpose"></span></p>
                <p><strong>Phone Number:</strong> <span id="view-phone-number"></span></p>
                <p><strong>To:</strong> <span id="view-signature"></span></p>
                <p><strong>Date:</strong> <span id="view-date"></span></p>
                <button class="action-btn approve" onclick="printTransaction()">Print</button>
                <button class="action-btn dismiss" onclick="hideTransactionDetails()">Close</button>
            </div>
        </div>
    </section>

    <script>
        function showTransactionDetails(transaction) {
            document.getElementById('view-amount').textContent = 'ZMK ' + transaction.amount;
            document.getElementById('view-purpose').textContent = transaction.purpose;
            document.getElementById('view-phone-number').textContent = transaction.phone_number;
            document.getElementById('view-signature').textContent = transaction.signature;
            document.getElementById('view-date').textContent = transaction.date;
            document.getElementById('edit-modal').style.display = 'block';
        }

        function hideTransactionDetails() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function printTransaction() {
            const modalContent = document.querySelector("#edit-modal").innerHTML;
            const printWindow = window.open("", "_blank");
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                <head><title>Print Transaction</title></head>
                <body>${modalContent}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>

</html>
