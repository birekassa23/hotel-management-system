<?php
//include database connection
include '../assets/conn.php';

// Collect form data
$guestFNameRoom = isset($_POST['guestFNameRoom']) ? trim($_POST['guestFNameRoom']) : '';
$guestLNameRoom = isset($_POST['guestLNameRoom']) ? trim($_POST['guestLNameRoom']) : '';
$roomType = isset($_POST['RoomType']) ? trim($_POST['RoomType']) : '';
$roomPrice = isset($_POST['RoomPrice']) ? trim($_POST['RoomPrice']) : '';
$roomId = isset($_POST['RoomId']) ? trim($_POST['RoomId']) : '';
$checkInDateRoom = isset($_POST['checkInDateRoom']) ? trim($_POST['checkInDateRoom']) : '';
$checkOutDateRoom = isset($_POST['checkOutDateRoom']) ? trim($_POST['checkOutDateRoom']) : '';
$assignedBy = 'default_user'; // Replace with actual logic to get the assigned user

// Handle file upload for Kebele ID
$uploadFile = '';
if (isset($_FILES['kebele_id']) && $_FILES['kebele_id']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['kebele_id']['name']);

    // Check if the upload directory exists, create it if not
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move uploaded file to the designated directory
    if (!move_uploaded_file($_FILES['kebele_id']['tmp_name'], $uploadFile)) {
        echo "Error moving uploaded file.";
        exit;
    }
}

// Begin transaction
$conn->begin_transaction();

try {
    // Prepare and bind the SQL statement
    $sql = "INSERT INTO reserved_rooms (first_name, last_name, room_type, room_id, room_price, checkin_date, checkout_date, kebele_id, assigned_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssss", $guestFNameRoom, $guestLNameRoom, $roomType, $roomId, $roomPrice, $checkInDateRoom, $checkOutDateRoom, $uploadFile, $assignedBy);

    // Execute the statement
    if ($stmt->execute()) {
        $conn->commit();
        echo "New record created successfully";
    } else {
        throw new Exception("Error: " . $stmt->error);
    }

} catch (Exception $e) {
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
}

// Close connections
$stmt->close();
$conn->close();
?>
