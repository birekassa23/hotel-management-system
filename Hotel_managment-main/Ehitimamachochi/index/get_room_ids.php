<?php
include '../assets/conn.php'; // Include database connection

$room_type = $_GET['room_type'] ?? ''; // Get the room type from the GET request

if ($room_type) {
    // Prepare the SQL statement to fetch room IDs
    $stmt = $conn->prepare("SELECT r_id, r_type FROM table_rooms WHERE r_status = 'free' AND r_type = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $room_type); // Bind the room type parameter to the SQL query
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result
        $rooms = []; // Initialize an array to store room data

        // Fetch all rows as associative arrays and add to rooms array
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        
        // Output the rooms array as JSON
        echo json_encode($rooms); 

        $stmt->close(); // Close the statement
    } else {
        // Handle error in preparing the statement
        echo json_encode(["error" => "Failed to prepare statement"]);
    }
} else {
    // If no room type is provided, return an error response
    echo json_encode(["error" => "Room type is required"]);
}

$conn->close(); // Close the connection
?>
