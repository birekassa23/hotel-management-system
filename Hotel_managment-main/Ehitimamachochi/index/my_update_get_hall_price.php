<?php
//include database connection
include '../assets/conn.php';

// Handle request for hall price
if (isset($_GET['Update_hall_id'])){
    $hallId = $_GET['Update_hall_id'];

    // Prepare SQL query to get hall price based on ID
    $sql = "SELECT price FROM table_meeting_halls WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hallId);
    $stmt->execute();
    $result = $stmt->get_result();

    $priceData = $result->fetch_assoc();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([$priceData]);
    exit();
}

$conn->close();
?>
