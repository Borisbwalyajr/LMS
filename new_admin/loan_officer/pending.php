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

        .action-btn.view {
            background-color: #ffc107;
            color: black;
        }

        .action-btn.view:hover {
            background-color: #e0a800;
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
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <main class="dashboard">
        <div class="container">

            <?php
            include 'connection.php';

            $sql = "SELECT * FROM loan_applications WHERE status = 'credited'";
            $stmt = $pdo->query($sql);
            ?>

            <h2>Pending Loans</h2>
            <input type="text" id="search" placeholder="Search by Loan ID or NRC">

            <table id="loans-table" aria-label="Pending Loans Table">
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
                            echo "<tr>";
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
                                    <a href='view_payment_history.php?loan_id=" . htmlspecialchars($row['loan_id']) . "' class='action-btn view'>View</a>
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
    </main>
</body>

</html>
