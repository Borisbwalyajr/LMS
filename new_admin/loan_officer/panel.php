<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/panel.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/users.css">
    <link rel="stylesheet" href="./css/pending.css">
    <link rel="stylesheet" href="./css/overdue.css">
    <link rel="stylesheet" href="./css/transaction.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="container">
        <button class="hamburger" id="hamburger-btn">
            <i class="fas fa-bars"></i>
        </button>
        <aside class="sidebar" id="sidebar">
            <h2 class="title"><i class="fas fa-user-shield"></i> Admin Menu</h2>
            <ul>
                <hr>
                <li><button class="menu-btn" data-content="dashboard"><i class="fas fa-table"></i> Dashboard</button></li>
                <hr>
                <li><button class="menu-btn"  data-content="pending"><i class="fas fa-hourglass-half"></i> Pending Loans</button></li>
                <hr>
                <li><button class="menu-btn" data-content="approved"><i class="fas fa-check-square"></i> Approved Loans</button></li>
                <hr>
                <li><button class="menu-btn" data-content="overdue"><i class="fas fa-clock"></i> Overdue Loans</button></li>
                <hr>
                <li><button class="menu-btn" data-content="users"><i class="fas fa-users"></i> Registered Users</button></li>
                <hr>
                <li><button class="menu-btn" data-content="transactions"><i class="fas fa-cash-register"></i> Daily Transactions</button></li>
                <hr>
                <li><button class="menu-btn" data-content="referral"><i class="fas fa-key"></i> Referral Assignment</button></li>
                <hr>
                <li><button class="menu-btn" data-content="report"><i class="fas fa-file-archive"></i> Generate Reports</button></li>
                <hr>
                <li><button class="menu-btn" data-content="settings"><i class="fas fa-cogs"></i> Settings</button></li>
                <hr>
                <li><button class="menu-btn" data-content="logout"><i class="fas fa-sign-out-alt"></i> Logout</button></li>
                <hr>
            </ul>
        </aside>
        <main class="content">
            <h1>Loan Management System Admin Dashboard</h1>
            <div id="dynamic-content">Select a menu item to see content here.</div>
        </main>
    </div>
    <footer class="footer">
        <p>Copyright &copy; <span id="current-year"></span> Zambo Impact Technologies. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="./js/panel.js"></script>
</body>
</html>