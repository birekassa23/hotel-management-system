<?php
//include database connection
include '../assets/conn.php';

// Initialize message variable
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_names = $_POST['item_name'];
    $categories = $_POST['category'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];
    $statuses = $_POST['status'];
    $created_at = date("Y-m-d H:i:s");

    foreach ($item_names as $index => $item_name) {
        $item_name_escaped = $conn->real_escape_string($item_name);
        $category_escaped = $conn->real_escape_string($categories[$index]);
        $quantity = intval($quantities[$index]);
        $price = floatval($prices[$index]);
        $status_escaped = $conn->real_escape_string($statuses[$index]);

        $sql = "INSERT INTO inventory (item_name, category, quantity, price, status, created_at) 
                VALUES ('$item_name_escaped', '$category_escaped', $quantity, $price, '$status_escaped', '$created_at')";

        if ($conn->query($sql) === TRUE) {
            $message = "Items added successfully!";
        } else {
            $message = "Error: " . $conn->error;
            break;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
</head>
<body>
    <!-- Your form goes here -->

    <?php if (!empty($message)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert("<?php echo $message; ?>");
                window.location.href = "http://localhost/New/Ehitimamachochi%20hotel%20information%20managmnet%20System/Manager/Add%20New%20Item.php"; // Replace with the URL of the previous page
            });
        </script>
    <?php endif; ?>
</body>
</html>
