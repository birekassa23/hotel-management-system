<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store_man/instock_items Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap_links.php'; ?> <!-- Include Bootstrap CSS links -->
    <style>
        .nav-item {
            font-size: 16px;
        }

        .nav-item:hover {
            border-bottom: 1px solid blue;
        }

        /* Additional styling for the report section */
        .report-section {
            margin-top: 20px;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', Times, serif;">

    <div class="d-flex flex-column min-vh-100">
        <?php include 'asset/nav-bar.php'; ?> <!-- Include Navbar -->


        <!-- Main Container -->
        <div id="mainContainer" class="container mt-4">
            <div class="report-section">
                <h2> This is Instocked Beverages reports</h2>
                <h5>Search Reports by Reported Date</h5>
                <form id="searchForm" method="GET">
                    <input type="date" id="searchDate" name="date" class="form-control d-inline-block w-25" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <div id="reportsContainer" class="mt-4">
                    <?php
                    //include database connection
                    include '../assets/conn.php';

                    try {
                        // Create a new PDO instance
                        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Get the date from the query string
                        $date = $_GET['date'] ?? date('Y-m-d'); // Default to today's date if not provided
                    
                        // Prepare the SQL statement
                        $stmt = $pdo->prepare("SELECT report_provider, report_type, item_name, measurement, quantity, single_price, total_price, reported_date FROM wechi WHERE reported_date = :reported_date AND report_type = 'beverages'");
                        $stmt->bindParam(':reported_date', $date);

                        // Execute the statement
                        $stmt->execute();
                        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Check if reports are found
                        if ($reports) {
                            // Start table structure
                            echo '<table class="table table-striped">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Item Name</th>';
                            echo '<th>Report Provider</th>';
                            echo '<th>Type</th>';
                            echo '<th>Measurement</th>';
                            echo '<th>Quantity</th>';
                            echo '<th>Single Price</th>';
                            echo '<th>Total Price</th>';
                            echo '<th>Reported Date</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            foreach ($reports as $report) {
                                echo '<tr>';
                                echo "<td>{$report['item_name']}</td>";
                                echo "<td>{$report['report_provider']}</td>";
                                echo "<td>{$report['report_type']}</td>";
                                echo "<td>{$report['measurement']}</td>";
                                echo "<td>{$report['quantity']}</td>";
                                echo "<td>{$report['single_price']}</td>";
                                echo "<td>{$report['total_price']}</td>";
                                echo "<td>{$report['reported_date']}</td>";
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No reports found for this date.</p>';
                        }
                    } catch (Exception $e) {
                        echo '<p class="text-danger">Error fetching reports: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    } finally {
                        // Close the connection
                        $pdo = null;
                    }
                    ?>
                </div>
            </div>
            <div class="report-section">
                <h2> This is Instocked Other Expenditure reports</h2>
                <h5>Search Reports by Reported Date</h5>
                <form id="searchForm" method="GET">
                    <input type="date" id="searchDate" name="date" class="form-control d-inline-block w-25" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <div id="reportsContainer" class="mt-4">
                    <?php
                        //include database connection
                        include '../assets/conn.php';

                    try {
                        // Create a new PDO instance
                        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Get the date from the query string
                        $date = $_GET['date'] ?? date('Y-m-d'); // Default to today's date if not provided
                    
                        // Prepare the SQL statement
                        $stmt = $pdo->prepare("SELECT report_provider, report_type, item_name, measurement, quantity, single_price, total_price, reported_date FROM wechi WHERE reported_date = :reported_date AND report_type = 'other_expenditure'");
                        $stmt->bindParam(':reported_date', $date);

                        // Execute the statement
                        $stmt->execute();
                        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Check if reports are found
                        if ($reports) {
                            // Start table structure
                            echo '<table class="table table-striped">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Item Name</th>';
                            echo '<th>Report Provider</th>';
                            echo '<th>Type</th>';
                            echo '<th>Measurement</th>';
                            echo '<th>Quantity</th>';
                            echo '<th>Single Price</th>';
                            echo '<th>Total Price</th>';
                            echo '<th>Reported Date</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            foreach ($reports as $report) {
                                echo '<tr>';
                                echo "<td>{$report['item_name']}</td>";
                                echo "<td>{$report['report_provider']}</td>";
                                echo "<td>{$report['report_type']}</td>";
                                echo "<td>{$report['measurement']}</td>";
                                echo "<td>{$report['quantity']}</td>";
                                echo "<td>{$report['single_price']}</td>";
                                echo "<td>{$report['total_price']}</td>";
                                echo "<td>{$report['reported_date']}</td>";
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No reports found for this date.</p>';
                        }
                    } catch (Exception $e) {
                        echo '<p class="text-danger">Error fetching reports: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    } finally {
                        // Close the connection
                        $pdo = null;
                    }
                    ?>
                </div>
            </div>
        </div>

        <footer class="footer bg-dark text-white text-center py-4 mt-auto">
            <div class="container">
                <p style="margin: 0;">&copy; 2024 Ehototmamachochi Hotel. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>