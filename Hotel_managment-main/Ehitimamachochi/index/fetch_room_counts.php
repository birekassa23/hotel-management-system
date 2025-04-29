<?php
// Include database connection
include '../assets/conn.php';

$room_types = ['standard', 'deluxe', 'suite', 'luxury'];
$counts = [];

foreach ($room_types as $type) {
    $sql = $conn->prepare("SELECT COUNT(*) AS count, MAX(r_price) AS price FROM table_rooms WHERE r_status='free' AND r_type=?");
    $sql->bind_param("s", $type);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();

    $counts[$type] = [
        'count' => (int)$row['count'], // Number of available rooms
        'price' => floatval($row['price']) // Price of the rooms
    ];
}

$conn->close();
echo json_encode($counts); // Return the counts and prices as a JSON object
?>
