<?php
header('Content-Type: application/json');

//include database connection
include '../assets/conn.php';

// Fetch meeting hall types and their counts where meeting halls are available (status = 'free')
$sql = "SELECT *, COUNT(*) AS count FROM table_meeting_halls WHERE status = 'free' GROUP BY type";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Query failed']);
    exit;
}

$meetingHallTypes = [];
while ($row = $result->fetch_assoc()) {
    $meetingHallTypes[] = $row;
}

echo json_encode($meetingHallTypes);

$conn->close();
?>
