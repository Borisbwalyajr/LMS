<?php
// Include the database connection
include 'connection.php';

// Fetch loans with status "approved"
$sql = "SELECT * FROM loan_applications WHERE status = 'not approv'";
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
