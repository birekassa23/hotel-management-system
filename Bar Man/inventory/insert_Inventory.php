<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include database connection
include '../../assets/conn.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $itemName = htmlspecialchars($_POST['item_name'], ENT_QUOTES, 'UTF-8');
    $itemType = htmlspecialchars($_POST['insert_item_type'], ENT_QUOTES, 'UTF-8');
    $category1 = isset($_POST['insert_food_category']) ? htmlspecialchars($_POST['insert_food_category'], ENT_QUOTES, 'UTF-8') : null;
    $category2 = isset($_POST['insert_beverage_category']) ? htmlspecialchars($_POST['insert_beverage_category'], ENT_QUOTES, 'UTF-8') : null;

    // Determine the category
    $category = null;
    if ($itemType === 'food' && $category1) {
        $category = $category1;
    } elseif ($itemType === 'beverage' && $category2) {
        $category = $category2;
    } else {
        echo "<script>
                alert('Category is not selected.');
                window.history.back();
                </script>";
        exit;
    }

    $purchasePrice = htmlspecialchars($_POST['insert_purchase_price'], ENT_QUOTES, 'UTF-8');
    $quantity = htmlspecialchars($_POST['item_quantity'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($_POST['item_price'], ENT_QUOTES, 'UTF-8');

    // Determine the table based on item type
    $table = '';
    if ($itemType === 'food') {
        $table = 'table_foods'; // Replace with your actual food table name
    } elseif ($itemType === 'beverage') {
        $table = 'table_beverages'; // Replace with your actual beverage table name
    } else {
        echo "<script>
                alert('Invalid item type.');
                window.history.back();
                </script>";
        exit;
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO $table (item_name, category, purchase_price, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdds', $itemName, $category, $purchasePrice, $quantity, $price);

    if ($stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Item registered successfully.',
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
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to register item. Please try again.',
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

    $stmt->close();
    $conn->close();
}
?>