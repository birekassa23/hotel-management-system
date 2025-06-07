<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory - Ehototmamachochi Hotel</title>
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

        .inventory-section {
            margin: 2rem 0;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .table-responsive {
            margin-top: 2rem;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .actions-bar .btn {
            margin-right: 10px;
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
                        <a class="nav-link active" href="View Inventory.php">
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
                        <a class="nav-link" href="Monthly Report.php">
                            <i class="bi bi-calendar-month me-2"></i>Monthly Report
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
                            <li><a class="dropdown-item" href="Log out.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="inventory-section mt-5">
            <h1 class="display-4 text-center font-weight-bold">View Inventory</h1>
            <p class="text-center">Here you can view and manage the current inventory of items.</p>

            <!-- Actions Bar -->
            <div class="actions-bar">
                <div>
                    <a href="add_new_item.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Add Item</a>
                    <a href="update_stock.php" class="btn btn-warning"><i class="bi bi-pencil me-2"></i>Update Item</a>
                    <a href="delete_item.php" class="btn btn-danger"><i class="bi bi-trash me-2"></i>Delete Item</a>
                </div>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i> Search</button>
                </form>
            </div>

            <!-- Inventory Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Quantity Available</th>
                            <th>Unit Price</th>
                            <th>Date Inserted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //include database connection
                        include '../../assets/conn.php';

                        // Fetch inventory data
                        $sql = "SELECT id, item_name, category, quantity, price, created_at FROM inventory";
                        $result = $conn->query($sql);

                        // Generate table rows dynamically
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["item_name"] . "</td>";
                                echo "<td>" . $row["category"] . "</td>";
                                echo "<td>" . $row["quantity"] . "</td>";
                                echo "<td>$" . number_format($row["price"], 2) . "</td>";
                                echo "<td>" . $row["created_at"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No items found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
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
