<?php
// Include database connection
include '../assets/conn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $report_provider = $_POST['report_provider'] ?? '';
    $report_type = $_POST['report_type'] ?? '';
    $reported_date = $_POST['reported_date'] ?? '';
    $list = $_POST['list'] ?? [];
    $measurement = $_POST['measurement'] ?? [];
    $quantity = $_POST['quantity'] ?? [];
    $single_price = $_POST['single_price'] ?? [];
    $total_price = $_POST['total_price'] ?? [];

    // Validate required fields
    if (empty($report_provider) || empty($report_type) || empty($reported_date) || empty($list)) {
        echo "All fields are required!";
        exit;
    }

    // Initialize success flag
    $allInserted = true;

    // Loop through items and insert into the database
    for ($i = 0; $i < count($list); $i++) {
        $item_name = $list[$i];
        $item_measurement = $measurement[$i];
        $item_quantity = $quantity[$i];
        $item_single_price = $single_price[$i];
        $item_total_price = $total_price[$i];

        // Prepare SQL query
        $sql = "INSERT INTO instock_reports 
            (report_provider, report_type, reported_date, item_name, measurement, quantity, single_price, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssss",
            $report_provider,
            $report_type,
            $reported_date,
            $item_name,
            $item_measurement,
            $item_quantity,
            $item_single_price,
            $item_total_price
        );

        // Execute the query and check for errors
        if (!$stmt->execute()) {
            $allInserted = false;
            echo "Error inserting row: " . $conn->error;
        }

        $stmt->close();
    }

    // Close the database connection
    $conn->close();

    // Redirect or show success message
    if ($allInserted) {
        header("Location: success_page.php");
    } else {
        echo "Some items could not be added.";
    }
}
?>
