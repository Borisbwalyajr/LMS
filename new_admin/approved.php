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
    background-color:blue;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Header Styles */
th {
    background-color:rgb(55, 17, 161);
    color: white;
    font-weight: bold;
    font-size: 16px;
}

th:hover {
    background-color:rgb(44, 33, 207);
}

/* Row Styles */
tr:nth-child(even) {
    background-color:rgb(60, 15, 163);
}

tr:hover {
    background-color: #e1e1e1;
}

tr td {
    font-size: 14px;
    color: #333;
}

/* Action Buttons Styles */
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
    background-color: blue;
}

/* Search Input Field */
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
    border-color: blue;
    outline: none;
}

/* Responsive Styles */
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
</style>
</head>

<body>
    
   <?php
   include 'sidebar.php';
   ?>

    <section class="dashboard">
        <div class="container">
            
<?php
// Include the database connection
include 'connection.php';

// Fetch loans with status "approved"
$sql = "SELECT * FROM loan_applications WHERE status = 'approved'";
$stmt = $pdo->query($sql); // Use $pdo instead of $conn
?>

<h2>Approved Loans</h2>
<input type="text" id="search" placeholder="Search by Loan ID or NRC" style="width: 100%; padding: 10px; margin-bottom: 20px;">

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
                        <button class='action-btn approve'>Approve</button>
                        <button class='action-btn dismiss'>Dismiss</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No approved loans found</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    document.getElementById("search").addEventListener("input", function () {
        const filter = this.value.toUpperCase();
        const table = document.getElementById("loans-table");
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            const loanId = rows[i].getElementsByTagName("td")[0];
            const userNrc = rows[i].getElementsByTagName("td")[1];

            if (loanId || userNrc) {
                const loanIdText = loanId.textContent || loanId.innerText;
                const userNrcText = userNrc.textContent || userNrc.innerText;

                if (
                    loanIdText.toUpperCase().indexOf(filter) > -1 ||
                    userNrcText.toUpperCase().indexOf(filter) > -1
                ) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    });
</script>

        </div>
    </section>

    <script src="index.js"></script>
    
    <!-- Sources for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>