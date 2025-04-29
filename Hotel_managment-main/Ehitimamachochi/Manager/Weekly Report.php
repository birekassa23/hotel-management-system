<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report - Ehototmamachochi Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

        .report-section {
            margin: 2rem 0;
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
                        <a class="nav-link" href="Daily Report.php">
                            <i class="bi bi-bar-chart-line me-2"></i>Daily Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Weekly Report.php">
                            <i class="bi bi-calendar-week me-2"></i>Weekly Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Staff Details.php">
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
                            <li><a class="dropdown-item" href="Log out">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="report-section mt-5">
            <h1 class="display-4 text-center font-weight-bold">Weekly Report</h1>
            <p class="text-center">Review the weekly sales and stock levels for the past week.</p>

            <!-- Sales Summary -->
            <div class="table-responsive">
                <h3>Sales Summary</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item Name</th>
                            <th>Quantity Sold</th>
                            <th>Unit Price</th>
                            <th>Total Sales</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>Red Wine</td>
                            <td>70</td>
                            <td>$15.00</td>
                            <td>$1050.00</td>
                            <td>2024-08-07</td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>White Wine</td>
                            <td>40</td>
                            <td>$12.00</td>
                            <td>$480.00</td>
                            <td>2024-08-08</td>
                        </tr>
                        <tr>
                            <td>103</td>
                            <td>Chicken Wings</td>
                            <td>100</td>
                            <td>$8.00</td>
                            <td>$800.00</td>
                            <td>2024-08-09</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>

            <!-- Stock Levels -->
            <div class="table-responsive mt-4">
                <h3>Stock Levels</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Quantity Available</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>Red Wine</td>
                            <td>Beverages</td>
                            <td>24</td>
                            <td><span class="badge bg-success">In Stock</span></td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>White Wine</td>
                            <td>Beverages</td>
                            <td>12</td>
                            <td><span class="badge bg-success">In Stock</span></td>
                        </tr>
                        <tr>
                            <td>103</td>
                            <td>Chicken Wings</td>
                            <td>Food</td>
                            <td>50</td>
                            <td><span class="badge bg-success">In Stock</span></td>
                        </tr>
                        <tr>
                            <td>104</td>
                            <td>Beer</td>
                            <td>Beverages</td>
                            <td>0</td>
                            <td><span class="badge bg-danger">Out of Stock</span></td>
                        </tr>
                        <tr>
                            <td>105</td>
                            <td>Chocolate Cake</td>
                            <td>Desserts</td>
                            <td>8</td>
                            <td><span class="badge bg-warning">Low Stock</span></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
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
