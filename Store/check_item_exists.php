<?php
// Database connection (update with your credentials)
//include database connection
include '../assets/conn.php';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

$itemName = $data['item_name'] ?? '';
$itemType = $_POST['report_type'] ?? ''; // Ensure to pass the item type in the request

$response = [];

// Validate item type and name
if (!empty($itemName) && !empty($itemType)) {
    // Query based on item type
    if ($itemType === 'beverages') {
        $query = "SELECT measurement, quantity AS available_quantity, price AS single_price FROM table_beverages WHERE item_name = ?";
    } elseif ($itemType === 'other_expenditure') {
        $query = "SELECT measurement, quantity AS available_quantity, price AS single_price FROM table_expenditures WHERE item_name = ?";
    } else {
        $response['exists'] = false;
        echo json_encode($response);
        exit;
    }

    // Prepare statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $itemName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $response = [
            'exists' => true,
            'measurement' => $item['measurement'],
            'available_quantity' => $item['available_quantity'],
            'single_price' => $item['single_price']
        ];
    } else {
        $response['exists'] = false;
    }

    $stmt->close();
} else {
    $response['exists'] = false;
}

echo json_encode($response);
$conn->close();
?>
