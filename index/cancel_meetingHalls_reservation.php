
<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Ensure SweetAlert2 is included
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Function to display SweetAlert messages
function displayAlert($type, $title, $message) {
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '$type',
                    title: '$title',
                    text: '$message',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            });
        </script>
    ";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = $conn->real_escape_string(trim($_POST['Cancel_email']));
    $verification_code = $conn->real_escape_string(trim($_POST['Cancel_password']));
    $conn->begin_transaction();
    
    // Check if reservation exists
    $sql = "SELECT * FROM reserved_meeting_halls WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the reservation details
        $reservation = $result->fetch_assoc();
        $first_name = $reservation['first_name'];
        $last_name = $reservation['last_name'];

        // Prepare SQL to insert data into canceled_room_reservation
        $sql = "INSERT INTO canceled_meeting_halls_reservation (first_name, last_name, email, phone, hall_type, hall_id, hall_price, checkin_date, checkout_date, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $first_name, $last_name, $reservation['email'], 
                            $reservation['phone'], $reservation['hall_type'], $reservation['hall_id'], 
                            $reservation['hall_price'], $reservation['checkin_date'], $reservation['checkout_date'], 
                            $reservation['password']);

        // Execute the insert and check if successful
        if ($stmt->execute()) {
            // Update hall status back to 'free'
            $sql_update = "UPDATE table_meeting_halls SET status = 'free' WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $reservation['hall_id']);
            $stmt_update->execute();

            // Delete the reservation from meeting_halls_reservation
            $sql_delete = "DELETE FROM reserved_meeting_halls WHERE email = ? AND password = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("ss", $email, $verification_code);

            if ($stmt_delete->execute()) {
                displayAlert('success', 'Success!', 'Reservation canceled successfully!');

                // Send confirmation email
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
                                    <p>You can claim your refund by visiting Ehitimamachochi Hotel in person.</p>
                                    <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                    <p>Thank you for staying with us at Ehitimamachochi Hotel.</p>";
                    $mail->AltBody = "Dear $first_name $last_name,\n\nYour reservation has been successfully canceled.\n\nYou can claim your refund by visiting Ehitimamachochi Hotel in person.\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for staying with us at Ehitimamachochi Hotel.";

                    $mail->send();
                    $conn->commit();
                } catch (Exception $e) {
                    $error_message = "Failed to send email. Mailer Error: " . $mail->ErrorInfo;
                    displayAlert('error', 'Email Sending Failed', $error_message);
                    $conn->rollback();
                }
            } else {
                displayAlert('error', 'Error', 'Error canceling reservation.');
                $conn->rollback();
            }
        } else {
            displayAlert('error', 'Error', 'Error transferring reservation data.');
            $conn->rollback();
        }
    } else {
        displayAlert('error', 'Error', 'Reservation not found.');
        $conn->rollback();
    }

    $stmt->close();
    $conn->close();
}
?>
