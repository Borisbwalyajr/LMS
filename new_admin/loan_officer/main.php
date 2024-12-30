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
// Include the database connection
include 'connection.php';

// Fetch due loans from dueloans table
$sql = "SELECT * FROM dueloans";
$stmt = $pdo->query($sql); // Use $pdo instead of $conn

// Check if restore action is triggered
if (isset($_POST['restore_loan_id'])) {
    $loanId = $_POST['restore_loan_id'];

    // Get the loan details to insert back into loan_applications
    $stmtLoanDetails = $pdo->prepare("SELECT * FROM dueloans WHERE loan_id = ?");
    $stmtLoanDetails->execute([$loanId]);
    $loan = $stmtLoanDetails->fetch(PDO::FETCH_ASSOC);

    if ($loan) {
        // Insert loan data back into loan_applications table
        $stmtInsert = $pdo->prepare("INSERT INTO loan_applications (loan_id, nrc, amount, purpose, weeks, repayment, loan_date, due_date, status) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'approved')");
        $stmtInsert->execute([
            $loan['loan_id'],
            $loan['nrc'],
            $loan['amount'],
            $loan['purpose'],
            $loan['weeks'],
            $loan['repayment'],
            $loan['loan_date'],
            $loan['due_date']
        ]);

        // Delete loan from dueloans table
        $stmtDelete = $pdo->prepare("DELETE FROM dueloans WHERE loan_id = ?");
        $stmtDelete->execute([$loanId]);

        echo "<script>alert('Loan has been restored to loan applications.'); window.location.href = 'due_loans.php';</script>";
    }
}
?>

<h2>Due Loans</h2>
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
                        <form method='POST' style='display:inline;'>
                            <button type='submit' name='restore_loan_id' value='" . htmlspecialchars($row['loan_id']) . "' class='action-btn approve'>Restore</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No due loans found</td></tr>";
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