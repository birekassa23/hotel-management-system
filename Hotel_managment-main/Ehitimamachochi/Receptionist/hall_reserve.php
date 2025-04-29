<?php
//include database connection
include '../assets/conn.php';

// Collect form data
$guestFNameHall = isset($_POST['guestFNameHall']) ? trim($_POST['guestFNameHall']) : '';
$guestLNameHall = isset($_POST['guestLNameHall']) ? trim($_POST['guestLNameHall']) : '';
$guestPhoneHall = isset($_POST['guestPhoneHall']) ? trim($_POST['guestPhoneHall']) : '';
$hallType = isset($_POST['hallType']) ? trim($_POST['hallType']) : '';
$hallPrice = isset($_POST['hallPrice']) ? trim($_POST['hallPrice']) : '';
$hallId = isset($_POST['hallId']) ? trim($_POST['hallId']) : '';
$checkInDateHall = isset($_POST['checkInDateHall']) ? trim($_POST['checkInDateHall']) : '';
$checkOutDateHall = isset($_POST['checkOutDateHall']) ? trim($_POST['checkOutDateHall']) : '';
$assignedBy = 'reception'; // Replace with actual logic to get the assigned user

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
    $sql = "INSERT INTO reserved_meeting_halls (first_name, last_name, phone, hall_type, hall_id, hall_price, checkin_date, checkout_date, assigned_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssss", $guestFNameHall, $guestLNameHall, $guestPhoneHall, $hallType, $hallId, $hallPrice, $checkInDateHall, $checkOutDateHall, $assignedBy);

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
