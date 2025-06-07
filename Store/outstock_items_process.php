<?php
// Include database connection
include '../assets/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $reportProvider = $_POST['report_provider'];
    $reportType = $_POST['report_type'];
    $reportedDate = $_POST['reported_date'];
    $items = $_POST['list'];
    $measurements = $_POST['measurement'];
    $quantities = $_POST['quantity'];
    $singlePrices = $_POST['single_price'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        if ($reportType === "beverages") {
            // Prepare SQL statement for beverages
            $stmt = $conn->prepare("INSERT INTO beverage_in_bar_man (beverage_name, beverage_type, measurement, beverage_quantity, added_by, added_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $itemName, $beverageType, $measurement, $quantity, $reportProvider, $reportedDate);

            foreach ($items as $index => $itemName) {
                $beverageType = "Beverage"; // Example: Fixed type
                $measurement = $measurements[$index];
                $quantity = (int)$quantities[$index];

                // Execute insertion
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting into beverage_in_bar_man: " . $stmt->error);
                }
            }
        } elseif ($reportType === "other_expenditure") {
            // Prepare SQL statement for transferred items
            $stmt = $conn->prepare("INSERT INTO transferred_items (Report_Provider, To_Which, item_name, item_type, item_measurement, item_quantity, item_single_price, item_total_price, reported_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdids", $reportProvider, $toWhom, $itemName, $itemType, $measurement, $quantity, $singlePrice, $totalPrice, $reportedDate);

            foreach ($items as $index => $itemName) {
                $measurement = $measurements[$index];
                $quantity = (int)$quantities[$index];
                $singlePrice = (float)$singlePrices[$index];
                $totalPrice = $singlePrice * $quantity;
                $itemType = $reportType; // Use reportType as item type
                $toWhom = "Example Destination"; // Replace with actual value

                // Check quantity in `wechi`
                $checkQuantity = $conn->prepare("SELECT quantity FROM wechi WHERE item_name = ?");
                $checkQuantity->bind_param("s", $itemName);
                $checkQuantity->execute();
                $result = $checkQuantity->get_result();
                $itemData = $result->fetch_assoc();

                if ($itemData && $itemData['quantity'] >= $quantity) {
                    $newQuantity = $itemData['quantity'] - $quantity;

                    // Update quantity in `wechi`
                    $updateQuantity = $conn->prepare("UPDATE wechi SET quantity = ? WHERE item_name = ?");
                    $updateQuantity->bind_param("ds", $newQuantity, $itemName);
                    $updateQuantity->execute();
                    $updateQuantity->close();

                    // Insert into transferred_items
                    if (!$stmt->execute()) {
                        throw new Exception("Error inserting into transferred_items: " . $stmt->error);
                    }
                } else {
                    throw new Exception("Insufficient quantity for item: $itemName");
                }
            }
        }

        // Commit transaction
        $conn->commit();

        // Redirect on success
        header("Location: outstock_items.php?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    } finally {
        // Close statements and connection
        if (isset($stmt)) $stmt->close();
        if (isset($checkQuantity)) $checkQuantity->close();
        $conn->close();
    }
} else {
    echo "Invalid request method.";
}
?>
