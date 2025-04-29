<?php
//include database connection
include '../assets/conn.php';

// Query to get distinct hall types
$sql = "SELECT DISTINCT type FROM table_meeting_halls WHERE status = 'free' ORDER BY type ASC";
$result = $conn->query($sql);

// Prepare the response
$hall_types = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hall_types[] = array('type' => $row['type']);
    }
}

// Close connection
$conn->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($hall_types);
?>
