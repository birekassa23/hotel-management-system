<?php
// Include database connection
include '../assets/conn.php';

// Check if form data is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $reportProvider = $_POST['report_provider'];
    $reportType = $_POST['report_type'];
    $reportedDate = $_POST['reported_date'];
    $items = $_POST['list'];
    $measurements = $_POST['measurement'];
    $quantities = $_POST['quantity'];
    $singlePrices = $_POST['single_price'];

    // Begin database transaction
    $conn->begin_transaction();

    try {
        if ($reportType == "beverages") {
            // Prepare SQL statement for beverages
            $stmt = $conn->prepare("INSERT INTO table_beverages (item_name, category, quantity, purchase_price, price, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssidds", $itemName, $category, $quantity, $purchasePrice, $sellingPrice, $createdAt);

            foreach ($items as $index => $itemName) {
                $category = "beverages";  // Fixed category for beverages
                $quantity = (int) $quantities[$index];
                $purchasePrice = (float) $singlePrices[$index];
                $sellingPrice = $purchasePrice * 1.2; // Example: 20% markup
                $createdAt = $reportedDate;

                // Execute prepared statement
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting record: " . $stmt->error);
                }
            }
        } elseif ($reportType == "other_expenditure") {
            // Prepare SQL statement for other expenditure
            $stmt = $conn->prepare("INSERT INTO wechi (report_provider, report_type, reported_date, item_name, measurement, quantity, single_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdid", $reportProvider, $reportType, $reportedDate, $itemName, $measurement, $quantity, $singlePrice, $totalPrice);

            foreach ($items as $index => $itemName) {
                $measurement = $measurements[$index];
                $quantity = (int) $quantities[$index];
                $singlePrice = (float) $singlePrices[$index];
                $totalPrice = $singlePrice * $quantity;

                // Execute prepared statement
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting record: " . $stmt->error);
                }
            }
        }

        // Commit transaction
        $conn->commit();

        // Redirect with success message
        header("Location: instock_items.php?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
} else {
    echo "Invalid request method.";
}
?>
