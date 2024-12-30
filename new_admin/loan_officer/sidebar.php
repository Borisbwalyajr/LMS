<head>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Ionicons (make sure it's added in your project) -->
    <link href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/css/ionicons.min.css" rel="stylesheet">

    <style>
        /* Basic styling for the sidebar */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color:blue;

        }

        nav {
            width: 250px;
            height: 100vh;
            background-color:blue;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            box-shadow: 2px 0px 8px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 150px;
            margin-left: 20px;
        }

        /* Styling the links */
        .navLinks {
            list-style-type: none;
            padding-left: 0;
        }

        .navList {
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .navList a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .navList a:hover, .navList.active a {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .navList a ion-icon {
            margin-right: 15px;
        }

        .dropdown-menu {
            background-color: #34495e;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            min-width: 200px;
        }

        .dropdown-item {
            color: #fff;
            font-size: 1rem;
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #1abc9c;
        }

        /* Styling the bottom links (Profile, Logout) */
        .bottom-link {
            margin-top: auto;
            list-style-type: none;
            padding: 0;
        }

        .bottom-link li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .bottom-link li a:hover {
            background-color:rgb(34, 132, 230);
        }

        .bottom-link li a ion-icon {
            margin-right: 15px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <div class="logo-image">
                <img src="img/logo.jpg" alt="Logo">
            </div>
        </div>
        <div class="menu-items">
            <ul class="navLinks">
                <li class="navList active">
                    <a href="loan_officer.php">
                        <ion-icon name="home-outline"></ion-icon>
                        <span class="links">Dashboard</span>
                    </a>
                </li>
                <li class="navList">
                    <a href="pending.php">
                        <ion-icon name="folder-outline"></ion-icon>
                        <span class="links">Pending Loans</span>
                    </a>
                </li>
                <li class="navList dropdown">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <ion-icon name="analytics-outline"></ion-icon>
                        <span class="links">Analytics</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="approved.php">
                            <ion-icon name="heart-outline"></ion-icon>
                            <span class="links">Approved Loans</span>
                        </a></li>
                        <li><a class="dropdown-item" href="due_loan.php">
                            <ion-icon name="heart-outline"></ion-icon>
                            <span class="links">Due Loans</span>
                        </a></li>
                        <li><a class="dropdown-item" href="seized_loans.php">
                            <ion-icon name="heart-outline"></ion-icon>
                            <span class="links">Seized Loans</span>
                        </a></li>
                        <li><a class="dropdown-item" href="settled_loans.php">
                            <ion-icon name="chatbubbles-outline"></ion-icon>
                            <span class="links">Settled Loans</span>
                        </a></li>
                        <li><a class="dropdown-item" href="referral.php">
                            <ion-icon name="chatbubbles-outline"></ion-icon>
                            <span class="links">Referral Assignment</span>
                        </a></li>
                    </ul>
                </li>
            </ul>

            <ul class="bottom-link">
            <li>
                    <a href="report.php">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span class="links">Report</span>
                    </a>
                </li>
                <li>
                    <a href="transactions.php">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span class="links">Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span class="links">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <ion-icon name="log-out-outline"></ion-icon>
                        <span class="links">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Include Bootstrap JS (required for dropdown functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
