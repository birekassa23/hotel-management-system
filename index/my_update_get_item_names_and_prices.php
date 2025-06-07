
<?php
//include database connection
include '../assets/conn.php';
$mysqli=$conn;
// Get and sanitize input parameters
$item_category = filter_var($_GET['item_category'] ?? '', FILTER_SANITIZE_STRING);
$item_type = filter_var($_GET['item_type'] ?? '', FILTER_SANITIZE_STRING);

// Validate input parameters
if (empty($item_category) || empty($item_type)) {
    echo json_encode(['error' => 'Invalid or missing parameters']);
    exit();
}

// Determine the table based on item_category
$table_name = ($item_category === 'foods') ? 'table_foods' : 'table_beverages';

// Prepare and execute SQL query
$sql = "SELECT item_name, price FROM $table_name WHERE category = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param('s', $item_type);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode($result->fetch_all(MYSQLI_ASSOC));

    $stmt->close();
} else {
    echo json_encode(['error' => 'Database query preparation failed']);
}

$mysqli->close();
?>
