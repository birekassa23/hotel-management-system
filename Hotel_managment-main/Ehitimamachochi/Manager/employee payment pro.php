<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payment - Ehototmamachochi Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .navbar {
            margin-bottom: 0;
            background-color: #343a40;
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        .table-responsive {
            margin-top: 2rem;
        }

        .btn-pay {
            background-color: #28a745;
            color: white;
        }

        .btn-pay:disabled {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="Dashboard.php">
                            <i class="bi bi-house-door me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Overview.php">
                            <i class="bi bi-grid me-2"></i>Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="View Inventory.php">
                            <i class="bi bi-list-ul me-2"></i>View Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="View Staff.php">
                            <i class="bi bi-person me-2"></i>Staff Details
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="Account Settings.php">Account Settings</a></li>
                            <li><a class="dropdown-item" href="System Settings.php">System Settings</a></li>
                            <li><a class="dropdown-item" href="Log out.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="table-responsive mt-5">
            <h1 class="display-4 text-center font-weight-bold">Employee Payment</h1>

            <!-- Notification Section -->
            <?php
            //include database connection
            include '../assets/conn.php';

            // Fetch employees whose payment is due today
            $sql = "SELECT id, f_name, l_name 
                    FROM employees 
                    WHERE DATEDIFF(CURDATE(), reg_date) % 30 = 0 
                      AND payment_status = 'Not Paid'";
            $result = $conn->query($sql);

            // Generate notification
            if ($result->num_rows > 0) {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                echo 'Payments are due today for the following employees: ';
                $notifications = [];
                while ($row = $result->fetch_assoc()) {
                    $notifications[] = $row["f_name"] . " " . $row["l_name"];
                }
                echo implode(', ', $notifications);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-info" role="alert">No payments are due today.</div>';
            }

            // Fetch employee data
            $sql = "SELECT id, f_name, l_name, reg_date, DATEDIFF(CURDATE(), reg_date) AS days_registered, 
                    DATEDIFF(CURDATE(), reg_date) % 30 AS remaining_days, 
                    CASE WHEN DATEDIFF(CURDATE(), reg_date) % 30 = 0 THEN 'Paid' ELSE 'Not Paid' END AS payment_status 
                    FROM employees";
            $result = $conn->query($sql);

            // Generate table rows dynamically
            if ($result->num_rows > 0) {
                echo '<table class="table table-striped table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>First Name</th>';
                echo '<th>Last Name</th>';
                echo '<th>Registration Date</th>';
                echo '<th>Remaining Days</th>';
                echo '<th>Payment Status</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = $result->fetch_assoc()) {
                    $pay_button_disabled = ($row["remaining_days"] == 0) ? '' : 'disabled';
                    $pay_button_text = ($row["payment_status"] == 'Paid') ? 'Paid' : 'Pay';
                    echo '<tr>';
                    echo '<td>' . $row["id"] . '</td>';
                    echo '<td>' . $row["f_name"] . '</td>';
                    echo '<td>' . $row["l_name"] . '</td>';
                    echo '<td>' . $row["reg_date"] . '</td>';
                    echo '<td>' . $row["remaining_days"] . '</td>';
                    echo '<td>' . $row["payment_status"] . '</td>';
                    echo '<td>';
                    echo '<form action="pay_employee.php" method="post">';
                    echo '<input type="hidden" name="employee_id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="btn btn-pay btn-sm" ' . $pay_button_disabled . '>' . $pay_button_text . '</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p class="text-center">No employees found</p>';
            }

            // Close connection
            $conn->close();
            ?>

        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved. Powered by MTU Department of SE Group 1 Members</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
