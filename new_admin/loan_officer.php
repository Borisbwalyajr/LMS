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

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "loan_db";

    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Queries to get values
    // Total Income
    $totalIncomeQuery = "SELECT SUM(repayment) AS total_income FROM loan_applications";
    $totalIncomeResult = $conn->query($totalIncomeQuery);
    $totalIncome = $totalIncomeResult->fetch_assoc()['total_income'];

    // Active Loans
    $activeLoansQuery = "SELECT COUNT(*) AS active_loans FROM loan_applications WHERE status = 'approved'";
    $activeLoansResult = $conn->query($activeLoansQuery);
    $activeLoans = $activeLoansResult->fetch_assoc()['active_loans'];

    // Pending Loans
    $pendingLoansQuery = "SELECT COUNT(*) AS pending_loans FROM loan_applications WHERE status = 'credited'";
    $pendingLoansResult = $conn->query($pendingLoansQuery);
    $pendingLoans = $pendingLoansResult->fetch_assoc()['pending_loans'];

    // Overdue Notifications
    /*$overdueNotificationsQuery = "SELECT COUNT(*) AS overdue_notifications FROM notifications WHERE status = 'overdue'";
    $overdueNotificationsResult = $conn->query($overdueNotificationsQuery);
    $overdueNotifications = $overdueNotificationsResult->fetch_assoc()['overdue_notifications'];
*/
    // Close the database connection
    $conn->close();
    ?>

    <section class="dashboard">
        <div class="container">
            <div class="overview">
                <div class="title">
                    <ion-icon name="speedometer"></ion-icon>
                    <span class="text">Dashboard</span>
                </div>
                <div class="boxes">
                    <div class="box box1">
                        <ion-icon name="eye-outline"></ion-icon>
                        <span class="text">Income</span>
                        <span class="number"><?php echo number_format($totalIncome, 2); ?></span>
                    </div>
                    <div class="box box2">
                        <ion-icon name="people-outline"></ion-icon>
                        <span class="text">Active Loans</span>
                        <span class="number"><?php echo $activeLoans; ?></span>
                    </div>
                    <div class="box box3">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                        <span class="text">Pending Loans</span>
                        <span class="number"><?php echo $pendingLoans; ?></span>
                    </div>
                    <div class="box box4">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                        <span class="text">Overdue Notifications</span>
                        <span class="number">23</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="data-table activityTable">
                <div class="title">
                    <ion-icon name="time-outline"></ion-icon>
                    <span class="text">Recent Activities</span>
                </div>
                <div>
                    <!-- Enter any table or section here -->
                    <!DOCTYPE html>

    <style>
        
body{
    background-color:;
}
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
    background-color: blue;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

th:hover {
    background-color: blue;
}

/* Row Styles */
tr:nth-child(even) {
    background-color:blue;
}

tr:hover {
    background-color: blue;
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
    background-color:blue;
    color: white;
}

.action-btn.approve:hover {
    background-color: #218838;
}

.action-btn.dismiss {
    background-color: blue;
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


    
            
        <?php
// Include the database connection
include 'connection.php';

// Fetch loans with status "approved"
$sql = "SELECT * FROM loan_applications WHERE status = 'not approv'";
$stmt = $pdo->query($sql);

// Handle the approve action
if (isset($_POST['approve_loan_id'])) {
    $loanId = $_POST['approve_loan_id'];
    $updateStatus = $pdo->prepare("UPDATE loan_applications SET status = 'credited' WHERE loan_id = ?");
    $updateStatus->execute([$loanId]);
    echo "<script>alert('Loan status updated to credited.'); window.location.reload();</script>";
}

// Handle the edit action
if (isset($_POST['edit_loan_id']) && isset($_POST['new_amount'])) {
    $loanId = $_POST['edit_loan_id'];
    $newAmount = $_POST['new_amount'];
    $updateAmount = $pdo->prepare("UPDATE loan_applications SET amount = ? WHERE loan_id = ?");
    $updateAmount->execute([$newAmount, $loanId]);
    echo "<script>alert('Loan amount updated successfully.'); window.location.reload();</script>";
}
?>
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
                        <form method='POST' style='display:inline;'>
                            <button type='submit' name='approve_loan_id' value='" . htmlspecialchars($row['loan_id']) . "' class='action-btn approve'>Approve</button>
                        </form>
                        <button class='action-btn dismiss' onclick='showEditForm(\"" . htmlspecialchars($row['loan_id']) . "\", \"" . htmlspecialchars($row['amount']) . "\")'>Edit</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No pending loans found</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Edit Modal -->
<div id="edit-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background:white; border:1px solid #ddd; border-radius:5px;">
    <form method="POST">
        <h3>Edit Loan Amount</h3>
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

                </div>
            </div>

            <!-- Content -->
            <div style="display:none" class="data-table userDetailsTable">
                <div class="title">
                    <ion-icon name="folder-outline"></ion-icon>
                    <span class="text">Content</span>
                </div>
                <div>
                    <!-- Enter any table or section here -->
                </div>
            </div>

            <!-- Analytics -->
            <div style="display:none" class="data-table EditUserRole">
                <div class="title">
                    <ion-icon name="analytics-outline"></ion-icon>
                    <span class="text">Analytics</span>
                </div>
                <div>
                    <!-- Enter any table or section here -->
                </div>
            </div>

            <!-- Likes -->
            <div style="display:none" class="data-table VehicleDetails">
                <div class="title">
                    <ion-icon name="heart-outline"></ion-icon>
                    <span class="text">Vehicles</span>
                </div>
                <div>
                    <!-- Enter any table or section here -->
                </div>
            </div>

            <!-- Downloads section -->
            <div style="display:none" class="data-table downloads">
                <div class="title">
                    <ion-icon name="chatbubbles-outline"></ion-icon>
                    <span class="text">Comments</span>
                </div>
                <div>
                    <!-- Enter any table or section here -->
                </div>
            </div>
        </div>
    </section>

    <script src="index.js"></script>
    
    <!-- Sources for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>
