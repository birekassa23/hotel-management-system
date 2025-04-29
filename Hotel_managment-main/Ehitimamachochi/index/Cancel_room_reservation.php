
<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Ensure SweetAlert2 is included once
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Function to display SweetAlert messages
function showAlert($icon, $title, $text, $redirect = true) {
    $redirectScript = $redirect ? "window.history.back();" : "";
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '$icon',
                    title: '$title',
                    text: '$text',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $redirectScript
                    }
                });
            });
        </script>
    ";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = $conn->real_escape_string(trim($_POST['Delete_email']));
    $password = $conn->real_escape_string(trim($_POST['Delete_password']));

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if reservation exists
        $sql = "SELECT * FROM reserved_rooms WHERE email = ? AND verification_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the reservation details
            $reservation = $result->fetch_assoc();
            $first_name = $reservation['first_name'];
            $last_name = $reservation['last_name'];

            // Prepare SQL to insert data into canceled_room_reservation
            $sql = "INSERT INTO canceled_room_reservation (first_name, last_name, email, phone, room_type, room_id, room_price, checkin_date, checkout_date, verification_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssss", $first_name, $last_name, $reservation['email'], 
                                $reservation['phone'], $reservation['room_type'], $reservation['room_id'], 
                                $reservation['room_price'], $reservation['checkin_date'], $reservation['checkout_date'], 
                                $reservation['verification_code']);

            // Execute the insert and check if successful
            if ($stmt->execute()) {
                // Update room status back to 'free'
                $sql_update = "UPDATE table_rooms SET r_status = 'free' WHERE r_id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("s", $reservation['room_id']);
                $stmt_update->execute();

                // Delete the reservation from room_reservations
                $sql_delete = "DELETE FROM reserved_rooms WHERE email = ? AND verification_code = ?";
                $stmt_delete = $conn->prepare($sql_delete);
                $stmt_delete->bind_param("ss", $email, $password);

                if ($stmt_delete->execute()) {
                    showAlert('success', 'Success!', 'Reservation canceled successfully!');

                    // Send email with PHPMailer
                    $mail = getMailer();
                    try {

                        // Recipients
                        $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Reservation Canceled';
                        $mail->Body = "<p>Dear $first_name $last_name,</p>
                                        <p>Your reservation has been successfully <span style='color: red;'>canceled</span>.</p>
                                        <p>You can take your money by visiting Ehitimamachochi Hotel in person.</p>
                                        <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                        <p>Thank you for staying with us at Ehitimamachochi Hotel.</p>";
                        $mail->AltBody = "Dear $first_name $last_name,\n\nYour reservation has been successfully canceled.\n\nYou can claim your refund by visiting Ehitimamachochi Hotel in person.\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for staying with us at Ehitimamachochi Hotel.";

                        $mail->send();
                        $conn->commit();
                    } catch (Exception $e) {
                        $conn->rollback();
                        $error_message = "Failed to send email. Mailer Error: " . $mail->ErrorInfo;
                        showAlert('error', 'Email Sending Failed', $error_message);
                    }
                } else {
                    $conn->rollback();
                    $error_message = "Error canceling reservation.";
                    showAlert('error', 'Error', $error_message);
                }
            } else {
                $conn->rollback();
                $error_message = "Error transferring reservation data.";
                showAlert('error', 'Error', $error_message);
            }
        } else {
            $conn->rollback();
            $error_message = "Reservation not found.";
            showAlert('error', 'Error', $error_message);
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        showAlert('error', 'Error', 'An unexpected error occurred.');
    }

    $conn->close();
}
?>
