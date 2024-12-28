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

/* Header Styles */
th {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

th:hover {
    background-color: #45a049;
}

/* Row Styles */
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
    background-color: #c82333;
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
    border-color: #4CAF50;
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
$sql = "SELECT * FROM transactions WHERE status = 'pending'";
$stmt = $pdo->query($sql);

// Handle the approve action
if (isset($_POST['id'])) {
    $loanId = $_POST['id'];
    $updateStatus = $pdo->prepare("UPDATE transactions SET status = 'paid' WHERE id = ?");
    $updateStatus->execute([$loanId]);
    echo "<script>alert('Status updated to credited.'); window.location.reload();</script>";
}

// Handle the edit action
if (isset($_POST['edit_loan_id']) && isset($_POST['new_amount'])) {
    $loanId = $_POST['edit_loan_id'];
    $newAmount = $_POST['new_amount'];
    $updateAmount = $pdo->prepare("UPDATE transactions SET amount = ? WHERE id = ?");
    $updateAmount->execute([$newAmount, $loanId]);
    echo "<script>alert('updated successfully.'); window.location.reload();</script>";
}
?>

<h2>Approved Loans</h2>
<input type="text" id="search" placeholder="Search by ID or NRC" style="width: 100%; padding: 10px; margin-bottom: 20px;">

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
                        <form method='POST' style='display:inline;'>
                            <button type='submit' name='id' value='" . htmlspecialchars($row['id']) . "' class='action-btn approve'>Approve</button>
                        </form>
                        <button class='action-btn dismiss' onclick='showEditForm(\"" . htmlspecialchars($row['id']) . "\", \"" . htmlspecialchars($row['amount']) . "\")'>Edit</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No Pending Transactions</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Edit Modal -->
<div id="edit-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background:white; border:1px solid #ddd; border-radius:5px;">
    <form method="POST">
        <h3>Edit The Transaction</h3>
        <input type="hidden" name="edit_loan_id" id="edit-loan-id">
        <label for="new-amount">New Amount:</label>
        <input type="number" name="new_amount" id="new-amount" required>
        <div style="margin-top: 10px;">
            <button type="submit" class="action-btn approve">Save</button>
            <button type="button" class="action-btn dismiss" onclick="hideEditForm()">Cancel</button>
        </div>
    </form>
</div>

<script>
    function showEditForm(loanId, currentAmount) {
        document.getElementById('edit-loan-id').value = loanId;
        document.getElementById('new-amount').value = currentAmount;
        document.getElementById('edit-modal').style.display = 'block';
    }

    function hideEditForm() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    // Search functionality
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