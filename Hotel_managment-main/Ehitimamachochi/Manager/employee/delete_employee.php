<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mail configuration
include '../../assets/email_config.php';

// Include the database connection file
include '../../assets/conn.php';

$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['id'];

    // Step 1: Fetch employee's details (name, email) before deletion
    $sql = "SELECT f_name, l_name, email FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($f_name, $l_name, $email);
    $stmt->fetch();
    $stmt->close();

    // Step 2: If employee exists, proceed with deletion process
    if ($email) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Step 2.1: Delete attendance records associated with this employee
            $sql = "DELETE FROM attendance WHERE employee_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $employee_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting attendance records: " . $conn->error);
            }

            // Step 2.2: Delete the employee record from employees table
            $sql = "DELETE FROM employees WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $employee_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting employee: " . $conn->error);
            }

            // Commit the transaction if both deletes are successful
            $conn->commit();

            $message = "Employee with ID $employee_id was deleted successfully.";
            $messageType = 'success';

            // Step 3: Send notification email to the employee
            sendEmail($email, $f_name, $l_name);

        } catch (Exception $e) {
            // If any error occurs, rollback the transaction
            $conn->rollback();
            $message = $e->getMessage();
            $messageType = 'error';
        }
    } else {
        // If employee doesn't exist in the database
        $message = "Employee with ID $employee_id not found.";
        $messageType = 'error';
    }
}

// Close the database connection
$conn->close();

/**
 * Function to send email notification to the employee
 */
function sendEmail($email, $f_name, $l_name) {
    // Initialize PHPMailer
    $mail = getMailer();

    try {
        // Recipient settings
        $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
        $mail->addAddress($email);

        // Content settings (HTML and Plain Text)
        $mail->isHTML(true);
        $mail->Subject = 'You have been removed from the system!';
        $mail->Body    = "<p>Dear $f_name $l_name,</p>
                          <p>We regret to inform you that you have been removed from our system.</p>
                          <p>If you have any questions, feel free to contact us.</p>
                          <p>Best regards,<br>Ehitimamachochi Hotel</p>";
        $mail->AltBody = "Dear $f_name $l_name,\n\nWe regret to inform you that you have been removed from our system.\n\nIf you have any questions, feel free to contact us.\n\nBest regards,\nEhitimamachochi Hotel";

        // Attempt to send the email
        $mail->send();
    } catch (Exception $e) {
        // Handle email sending errors
        global $message, $messageType;
        $message = "Employee deleted, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Employee</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if (!empty($message)) : ?>
    <script>
        // Display a SweetAlert based on the result
        Swal.fire({
            icon: '<?php echo $messageType; ?>',
            title: '<?php echo ucfirst($messageType); ?>',
            text: '<?php echo $message; ?>',
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();  // Go back to the previous page
            }
        });
    </script>
<?php endif; ?>

</body>
</html>
