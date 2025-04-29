
<?php
//include database connection
include '../assets/conn.php';

// Handle request for hall IDs
if (isset($_GET['hall_type'])) {
    $hallType = $_GET['hall_type'];

    // Prepare SQL query to get available hall IDs based on type
    $sql = "SELECT id, capacity FROM table_meeting_halls WHERE type = ? AND status = 'free'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $hallType);
    $stmt->execute();
    $result = $stmt->get_result();

    $hallIds = [];
    while ($row = $result->fetch_assoc()) {
        $hallIds[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($hallIds);
    exit();
}

$conn->close();
?>
