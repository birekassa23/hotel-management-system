
<?php
//include database connection
include '../assets/conn.php';

// Get and sanitize input parameters
$item_category = filter_var($_GET['item_category'] ?? '', FILTER_SANITIZE_STRING);
$item_type = filter_var($_GET['item_type'] ?? '', FILTER_SANITIZE_STRING);
$item_name = filter_var($_GET['item_name'] ?? '', FILTER_SANITIZE_STRING);

// Validate input parameters
if (empty($item_category) || empty($item_type) || empty($item_name)) {
    echo json_encode(['error' => 'Invalid or missing parameters']);
    exit();
}

// Determine the table based on item_category
$table_name = ($item_category === 'foods') ? 'table_foods' : 'table_beverages';

// Prepare and execute SQL query
$sql = "SELECT quantity AS available_quantity, price AS item_price FROM $table_name WHERE category = ? AND item_name = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param('ss', $item_type, $item_name);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode($result->fetch_all(MYSQLI_ASSOC));

    $stmt->close();
} else {
    echo json_encode(['error' => 'Database query preparation failed']);
}

$mysqli->close();
?>
