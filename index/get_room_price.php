<?php
include '../assets/conn.php';//include database connection
$room_id = $_GET['room_id'] ?? '';
if ($room_id) {
    // Prepare the SQL statement to fetch the room price
    $stmt = $conn->prepare("SELECT r_price FROM table_rooms WHERE r_id = ?");
    if ($stmt) {
        // Bind the room ID parameter to the SQL query
        $stmt->bind_param("s", $room_id);
        $stmt->execute();// Execute the query
        $result = $stmt->get_result();// Get the result
        $room = [];// Initialize an array to store room data
        // Fetch the row as an associative array and add to room array
        if ($row = $result->fetch_assoc()) {
            $room[] = $row;
        }
        echo json_encode($room);// Output the room array as JSON
        $stmt->close();// Close the statement
    } else {
        echo json_encode(["error" => "Failed to prepare statement"]);// Handle error in preparing the statement
    }
}
$conn->close();// Close the connection
?>
