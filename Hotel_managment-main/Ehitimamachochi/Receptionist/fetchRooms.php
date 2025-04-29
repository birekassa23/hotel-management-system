<?php
header('Content-Type: application/json');
//include database connection
include '../assets/conn.php';

// Fetch room types and their counts where rooms are available (r_status = 'free')
$sql = "SELECT *, COUNT(*) AS count FROM table_rooms WHERE r_status = 'free' GROUP BY r_type";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Query failed']);
    exit;
}

$roomTypes = [];
while ($row = $result->fetch_assoc()) {
    $roomTypes[] = $row;
}

echo json_encode($roomTypes);
$conn->close();
?>
