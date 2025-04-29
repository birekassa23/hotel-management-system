<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

//include database connection
include '../assets/conn.php';

// Check for the roomType parameter
if (!isset($_GET['roomType'])) {
    echo json_encode(['error' => 'roomType parameter missing']);
    exit();
}

$roomType = $conn->real_escape_string($_GET['roomType']);

// Execute the query
$query = "SELECT * FROM table_rooms WHERE r_type = '$roomType' AND r_status ='free'";
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

$roomIds = [];
while ($row = $result->fetch_assoc()) {
    $roomIds[] = $row;
}

// Return the results as JSON
echo json_encode($roomIds);
?>
