<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include database connection
include '../../assets/conn.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Retrieve and sanitize form data
    $itemNames = $_POST['item_name']; // Array
    $categories = $_POST['category']; // Array
    $purchasePrices = $_POST['purchase_price']; // Array
    $quantities = $_POST['quantity']; // Array
    $prices = $_POST['price']; // Array
    $itemTypes = $_POST['item_type']; // Array

    // Start transaction
    $conn->begin_transaction();

    try {
        $updateOccurred = false;

        // Loop through the arrays and check if updates are necessary
        for ($i = 0; $i < count($itemNames); $i++) {
            $itemName = htmlspecialchars($itemNames[$i], ENT_QUOTES, 'UTF-8');
            $category = htmlspecialchars($categories[$i], ENT_QUOTES, 'UTF-8');
            $purchasePrice = htmlspecialchars($purchasePrices[$i], ENT_QUOTES, 'UTF-8');
            $quantity = htmlspecialchars($quantities[$i], ENT_QUOTES, 'UTF-8');
            $price = htmlspecialchars($prices[$i], ENT_QUOTES, 'UTF-8');
            $itemType = htmlspecialchars($itemTypes[$i], ENT_QUOTES, 'UTF-8');

            // Determine the table based on item type
            $table = '';
            if ($itemType === 'foods') {
                $table = 'foods'; // Replace with your actual food table name
            } elseif ($itemType === 'beverages') {
                $table = 'beverages'; // Replace with your actual beverage table name
            } else {
                throw new Exception('Invalid item type');
            }

            // Prepare the SQL query for checking existing values
            $checkSql = "SELECT purchase_price, quantity, price FROM $table WHERE item_name = ? AND category = ?";
            $checkStmt = $conn->prepare($checkSql);
            if ($checkStmt === false) {
                throw new Exception('Prepare failed: ' . $conn->error);
            }
            $checkStmt->bind_param('ss', $itemName, $category);
            $checkStmt->execute();
            $checkStmt->bind_result($currentPurchasePrice, $currentQuantity, $currentPrice);
            $checkStmt->fetch();
            $checkStmt->close(); // Close the check statement

            // Compare with new values and update if necessary
            if ($purchasePrice != $currentPurchasePrice || $quantity != $currentQuantity || $price != $currentPrice) {
                $updateOccurred = true;

                // Prepare the SQL update query
                $updateSql = "UPDATE $table SET purchase_price = ?, quantity = ?, price = ? WHERE item_name = ? AND category = ?";
                $updateStmt = $conn->prepare($updateSql);
                if ($updateStmt === false) {
                    throw new Exception('Prepare failed: ' . $conn->error);
                }
                $updateStmt->bind_param('ddsss', $purchasePrice, $quantity, $price, $itemName, $category);
                if (!$updateStmt->execute()) {
                    throw new Exception('Execute failed: ' . $updateStmt->error);
                }
                $updateStmt->close(); // Close the update statement
            }
        }

        // Commit transaction if updates occurred
        if ($updateOccurred) {
            $conn->commit();
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Inventory updated successfully.',
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
            // Rollback transaction if no updates occurred
            $conn->rollback();
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Changes!',
                        text: 'No changes were made to the inventory.',
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

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();

        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update inventory. Please try again. " . addslashes($e->getMessage()) . "',
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

    $conn->close();
} else {
    die('Invalid request method');
}
?>
