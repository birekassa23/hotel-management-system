<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Database connection
include '../assets/conn.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $reportProvider = htmlspecialchars($_POST['report_provider'], ENT_QUOTES, 'UTF-8');
    $reportedDate = htmlspecialchars($_POST['reported_date'], ENT_QUOTES, 'UTF-8');


    // Prepare SQL statement to insert report provider and date
    $sqlReport = "INSERT INTO report_entries (report_provider, reported_date) VALUES (?, ?)";
    $stmtReport = $conn->prepare($sqlReport);
    $stmtReport->bind_param('ss', $reportProvider, $reportedDate);

    if ($stmtReport->execute()) {
        $reportId = $stmtReport->insert_id; // Get the ID of the inserted report

        // Prepare SQL statement to insert report details
        $sqlDetails = "INSERT INTO report_details (report_id, list, measurement, quantity, single_price, total_price) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtDetails = $conn->prepare($sqlDetails);

        // Loop through each row in the table
        $lists = $_POST['list'];
        $measurements = $_POST['measurement'];
        $quantities = $_POST['quantity'];
        $singlePrices = $_POST['single_price'];
        $totalPrices = $_POST['total_price'];

        for ($i = 0; $i < count($lists); $i++) {
            // Sanitize input data
            $list = htmlspecialchars($lists[$i], ENT_QUOTES, 'UTF-8');
            $measurement = htmlspecialchars($measurements[$i], ENT_QUOTES, 'UTF-8');
            $quantity = (int) htmlspecialchars($quantities[$i], ENT_QUOTES, 'UTF-8'); // Ensure quantity is an integer
            $singlePrice = (float) htmlspecialchars($singlePrices[$i], ENT_QUOTES, 'UTF-8'); // Ensure single price is a float
            $totalPrice = (float) htmlspecialchars($totalPrices[$i], ENT_QUOTES, 'UTF-8'); // Ensure total price is a float

            // Insert each detail into the report_details table
            $stmtDetails->bind_param('issddd', $reportId, $list, $measurement, $quantity, $singlePrice, $totalPrice);
            $stmtDetails->execute();
        }

        // Success message
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Report submitted successfully.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            });
        </script>
        ";
    } else {
        // Error message
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to submit report. Please try again.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            });
        </script>
        ";
    }

    // Close the prepared statements and connection
    $stmtReport->close();
    $stmtDetails->close();
    $conn->close();
}
?>