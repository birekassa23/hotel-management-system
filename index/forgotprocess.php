<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Ensure SweetAlert2 is included
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Function to output SweetAlert2 script
function showAlert($icon, $title, $text) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$text',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        });
    </script>";
}

// Get the email from the form
$email = $_POST['email'];

// Check if the email exists in the database
$sql = "SELECT f_name, l_name, email FROM employees WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the user data
    $row = $result->fetch_assoc();
    $f_name = $row['f_name'];
    $l_name = $row['l_name'];
    
    // Generate a new 6-digit password
    $newPassword = rand(100000, 999999);
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update the employee's password in the database
    $updateSql = "UPDATE employees SET password = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $hashedPassword, $email);
    
    if ($updateStmt->execute()) {
        // Initialize PHPMailer and send the email
        $mail = getMailer();
        try {

            // Recipients
            $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'Your New Password';
            $mail->Body    = "Dear $f_name $l_name,<br><br>You have forgotten your password.<br>Your new password is: <strong>$newPassword</strong><br>Please log in and change it immediately.";
            $mail->AltBody = "Dear $f_name $l_name, You have forgotten your password. Your new password is: $newPassword. Please log in and change it immediately.";

            $mail->send();
            showAlert('success', 'Success', 'An email has been sent with your new password.');
        } catch (Exception $e) {
            showAlert('error', 'Error', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    } else {
        showAlert('error', 'Error', 'Failed to update the password.');
    }
} else {
    showAlert('error', 'Error', 'No account found with that email.');
}

$stmt->close();
$conn->close();
?>
