<h2>Registered Users</h2>
<input type="text" id="search" placeholder="Search by NRC or Mobile" style="width: 100%; padding: 10px; margin-bottom: 20px;">
<table id="users-table">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>D.O.B</th>
            <th>Gender</th>
            <th>NRC</th>
            <th>Next of Kin Phone</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Include the database connection
        include 'connection.php';

        // Fetch user data from the registrations table
        $sql = "SELECT * FROM registrations";
        $stmt = $pdo->query($sql); // Use $pdo instead of $conn

        // Check if there are any records
        if ($stmt->rowCount() > 0) {
            // Loop through the results and display them in the table
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td><img src='https://via.placeholder.com/50' alt='User Photo'></td>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mobile_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['id_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['next_of_kin1_phone']) . "</td>";
                echo "<td>
                        <button class='action-btn suspend'>Suspend</button>
                        <button class='action-btn splt'>Uplift</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No registered users found</td></tr>";
        }
        ?>
    </tbody>
</table>
