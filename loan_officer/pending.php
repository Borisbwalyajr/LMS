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

        th,
        td {
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

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e1e1;
        }

        .action-btn {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        .action-btn.approve {
            background-color: #28a745;
            color: white;
        }

        .action-btn.approve:hover {
            background-color: #218838;
        }

        .action-btn.dismiss {
            background-color: #007bff;
            color: white;
        }

        .action-btn.dismiss:hover {
            background-color: #0056b3;
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

        #payment-history-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #payment-history-modal h3 {
            margin-bottom: 10px;
        }

        #modal-close {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #modal-close:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <section class="dashboard">
        <div class="container">

            <?php
            include 'connection.php';

            $sql = "SELECT * FROM loan_applications WHERE status = 'credited'";
            $stmt = $pdo->query($sql);
            ?>

            <h2>Pending Loans</h2>
            <input type="text" id="search" placeholder="Search by Loan ID or NRC">

            <table id="loans-table">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>User NRC</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Weeks</th>
                        <th>Repayment</th>
                        <th>Loan Date</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr data-loan-id='" . htmlspecialchars($row['loan_id']) . "'>";
                            echo "<td>" . htmlspecialchars($row['loan_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nrc']) . "</td>";
                            echo "<td>ZMW" . htmlspecialchars($row['amount']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['weeks']) . "</td>";
                            echo "<td>ZMW" . htmlspecialchars($row['repayment']) . "/month</td>";
                            echo "<td>" . htmlspecialchars($row['loan_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                            echo "<td>
                                    <button class='action-btn approve'>Settled</button>
                                    <button class='action-btn dismiss'>Check</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No Pending loans found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal for Payment History -->
    <div id="payment-history-modal">
        <h3>Payment History</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="payment-history-content">
            </tbody>
        </table>
        <button id="modal-close">Close</button>
    </div>

    <script>
        // Mark loan as completed
        document.querySelectorAll('.action-btn.approve').forEach(button => {
            button.addEventListener('click', function () {
                const loanId = this.closest('tr').dataset.loanId;

                if (confirm('Mark this loan as completed?')) {
                    fetch('update_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ loan_id: loanId, status: 'completed' })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Loan marked as completed');
                                location.reload();
                            } else {
                                alert('Failed to update loan status');
                            }
                        });
                }
            });
        });

        // Show payment history
        document.querySelectorAll('.action-btn.dismiss').forEach(button => {
            button.addEventListener('click', function () {
                const loanId = this.closest('tr').dataset.loanId;

                fetch('fetch_payment_history.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ loan_id: loanId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = document.getElementById('payment-history-modal');
                            const content = document.getElementById('payment-history-content');
                            content.innerHTML = '';

                            data.payments.forEach(payment => {
                                const row = `<tr><td>${payment.date}</td><td>ZMW${payment.amount}</td></tr>`;
                                content.innerHTML += row;
                            });

                            modal.style.display = 'block';
                        } else {
                            alert('No payment history found');
                        }
                    });
            });
        });

        // Close modal
        document.getElementById('modal-close').addEventListener('click', function () {
            document.getElementById('payment-history-modal').style.display = 'none';
        });
    </script>
</body>

</html>
