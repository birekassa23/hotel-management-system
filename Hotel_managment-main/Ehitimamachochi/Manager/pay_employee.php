<?php
//include database connection
include '../assets/conn.php';

// Get employee ID from form
$employee_id = $_POST['employee_id'];

// Update payment status to 'Paid'
$sql = "UPDATE employees SET payment_status = 'Paid' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);

if ($stmt->execute()) {
    echo "Payment updated successfully.";
} else {
    echo "Error updating payment: " . $conn->error;
}

// Close connection
$stmt->close();
$conn->close();

// Redirect back to the Employee Payment page
header("Location: Employee Payment.php");
exit();
?>