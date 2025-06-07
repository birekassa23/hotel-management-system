<?php
include '../assets/conn.php'; // Include database connection


// Define an array to store the room report data
$room_report = [];
// Prepare and execute the SQL statement
$sql = "SELECT `room_type`, `no_of_reserved_room`, `reserved_date` FROM `rooms_reports`";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data and store it in the $room_report array
while ($row = $result->fetch_assoc()) {
    $room_report[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($room_report);
exit();

// $conn->close();// this is showing warnning on web pages when load 
?>
