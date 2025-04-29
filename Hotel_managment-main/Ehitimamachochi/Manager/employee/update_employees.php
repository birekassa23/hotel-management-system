<?php
// Include database and email configuration
include '../../assets/conn.php';
include '../../assets/email_config.php';

// Initialize message variables
$message = '';
$messageType = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $ids = $_POST['ids'];
    $f_names = $_POST['f_name'];
    $l_names = $_POST['l_name'];
    $sexes = $_POST['sex'];
    $ages = $_POST['age'];
    $emails = $_POST['email'];
    $phone_nos = $_POST['phone_no'];
    $positions = $_POST['position'];
    $edu_statuses = $_POST['edu_status'];
    $current_documents = $_POST['current_document'];
    $current_kebele_ids = $_POST['current_kebele_id'];

    // Handle uploaded files
    $documents = $_FILES['document'];
    $kebele_ids = $_FILES['kebele_id'];

    // Start the transaction
    $conn->begin_transaction();

    try {
        // Iterate over each employee and update records
        for ($i = 0; $i < count($ids); $i++) {
            $id = $ids[$i];
            $f_name = $f_names[$i];
            $l_name = $l_names[$i];
            $sex = $sexes[$i];
            $age = $ages[$i];
            $email = $emails[$i];
            $phone_no = $phone_nos[$i];
            $position = $positions[$i];
            $edu_status = $edu_statuses[$i];
            $document = $current_documents[$i];
            $kebele_id = $current_kebele_ids[$i];

            // Update document if a new file is uploaded
            if (!empty($documents['name'][$i])) {
                $documentPath = 'uploads/documents/' . basename($documents['name'][$i]);
                if (move_uploaded_file($documents['tmp_name'][$i], $documentPath)) {
                    $document = $documentPath;
                } else {
                    throw new Exception("Failed to upload document for employee ID $id.");
                }
            }

            // Update kebele ID if a new file is uploaded
            if (!empty($kebele_ids['name'][$i])) {
                $kebeleIdPath = 'uploads/kebele_ids/' . basename($kebele_ids['name'][$i]);
                if (move_uploaded_file($kebele_ids['tmp_name'][$i], $kebeleIdPath)) {
                    $kebele_id = $kebeleIdPath;
                } else {
                    throw new Exception("Failed to upload kebele ID for employee ID $id.");
                }
            }

            // Prepare the SQL query to update the employee record
            $sql = "UPDATE employees 
                    SET f_name = ?, l_name = ?, sex = ?, age = ?, email = ?, phone_no = ?, position = ?, edu_status = ?, document = ?, kebele_id = ?
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssi", $f_name, $l_name, $sex, $age, $email, $phone_no, $position, $edu_status, $document, $kebele_id, $id);

            if (!$stmt->execute()) {
                throw new Exception("Error updating employee ID $id: " . $stmt->error);
            }

            // Send notification email to the employee
            $mail = getMailer();
            $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Employee Details Updated';
            $mail->Body = "
                <p>Dear $f_name $l_name,</p>
                <p>Your details have been successfully updated. Here is your updated information:</p>
                <ul>
                    <li>Name: $f_name $l_name</li>
                    <li>Sex: $sex</li>
                    <li>Age: $age</li>
                    <li>Email: $email</li>
                    <li>Phone: $phone_no</li>
                    <li>Position: $position</li>
                    <li>Education Status: $edu_status</li>
                </ul>
                <p>Best regards,<br>Ehitimamachochi Hotel</p>";

            if (!$mail->send()) {
                throw new Exception("Failed to send email for employee ID $id: " . $mail->ErrorInfo);
            }
        }

        // Commit the transaction if all updates succeed
        $conn->commit();
        $message = 'All employees updated successfully!';
        $messageType = 'success';

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $message = "Transaction failed: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employees</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (!empty($message)) : ?>
    <script>
        Swal.fire({
            icon: '<?php echo $messageType; ?>',
            title: '<?php echo ucfirst($messageType); ?>',
            text: '<?php echo $message; ?>',
        }).then(() => {
            window.location.href = "../index.php"; // Redirect back to the main page or employee list
        });
    </script>
<?php endif; ?>
</body>
</html>
