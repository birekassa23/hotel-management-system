
<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = $conn->real_escape_string(trim($_POST['Update_email']));
    $verification_code = $conn->real_escape_string(trim($_POST['Update_password']));
    $hall_type = $conn->real_escape_string(trim($_POST['Update_hall_type']));
    $hall_id = $conn->real_escape_string(trim($_POST['Update_hall_id']));
    $hall_price = floatval($_POST['Update_hall_price']);
    $checkin_date = $conn->real_escape_string(trim($_POST['Update_checkin_date']));
    $checkout_date = $conn->real_escape_string(trim($_POST['Update_checkout_date']));

    // Check if reservation exists and fetch the necessary details
    $sql = "SELECT first_name, last_name FROM reserved_meeting_halls WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the first name and last name
        $reservation = $result->fetch_assoc();
        $first_name = $reservation['first_name'];
        $last_name = $reservation['last_name'];

        // Update the reservation
        $sql = "UPDATE reserved_meeting_halls 
                SET hall_type = ?, hall_id = ?, hall_price = ?, checkin_date = ?, checkout_date = ?
                WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $hall_type, $hall_id, $hall_price, $checkin_date, $checkout_date, $email, $verification_code);

        if ($stmt->execute()) {
            // Send email with PHPMailer
            $mail = getMailer();
            try {
                // Recipients
                $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reservation Updated';
                $mail->Body = "<p>Dear $first_name $last_name,</p>
                                <p>Your reservation has been successfully updated.</p>
                                <p><strong>Hall Type:</strong> $hall_type<br>
                                <strong>Hall ID:</strong> $hall_id<br>
                                <strong>Price:</strong> $hall_price<br>
                                <strong>Check-in Date:</strong> $checkin_date<br>
                                <strong>Check-out Date:</strong> $checkout_date</p>
                                <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                <p>Thank you for staying with us at Ehitimamachochi Hotel.</p>";
                $mail->AltBody = "Dear $first_name $last_name,\n\nYour reservation has been successfully updated.\n\nHall Type: $hall_type\nHall ID: $hall_id\nPrice: $hall_price\nCheck-in Date: $checkin_date\nCheck-out Date: $checkout_date\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for staying with us at Ehitimamachochi Hotel.";

                $mail->send();
                
                // Success message
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Reservation updated successfully!',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.history.back();
                                }
                            });
                        });
                    </script>
                ";

            } catch (Exception $e) {
                // Ensure SweetAlert2 is included
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                
                $error_message = "Failed to send email. Mailer Error: " . $mail->ErrorInfo;
                echo "
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Email Sending Failed',
                                text: '$error_message',
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
        } else {
            // Ensure SweetAlert2 is included
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

            $error_message = "Error updating reservation.";
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '$error_message',
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
    } else {
        // Ensure SweetAlert2 is included
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

        $error_message = "Reservation not found.";
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '$error_message',
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

    $stmt->close();
    $conn->close();
}
?>
