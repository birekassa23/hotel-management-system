<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Initialize response variables
$status = "error";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['View_email']) && isset($_POST['View_password'])) {
    $email = trim($_POST['View_email']);
    $password = trim($_POST['View_password']);

    // Prepare and execute the SQL statement
    $sql = "SELECT first_name, last_name, email, phone, room_type, num_guests, room_id, room_price, checkin_date, checkout_date 
            FROM reserved_rooms 
            WHERE email = ? AND verification_code = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $message = "Failed to prepare SQL statement: " . $conn->error;
    } else {
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reservation = $result->fetch_assoc();
            $mail = getMailer();
            try {

                // Recipients
                $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reservation Details';
                $mail->Body    = "<p>Dear {$reservation['first_name']} {$reservation['last_name']},</p>
                                    <p>Your reservation details are as follows:</p>
                                    <p><strong>Room Type:</strong> {$reservation['room_type']}<br>
                                    <strong>Number of Guests:</strong> {$reservation['num_guests']}<br>
                                    <strong>Room ID:</strong> {$reservation['room_id']}<br>
                                    <strong>Check-in Date:</strong> {$reservation['checkin_date']}<br>
                                    <strong>Check-out Date:</strong> {$reservation['checkout_date']}<br>
                                    <strong>You have paid:</strong> {$reservation['room_price']} ETB</p>
                                    <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                    <p>Thank you for staying with us at Ehitimamachochi Hotel.</p>";
                $mail->AltBody = "Dear {$reservation['first_name']} {$reservation['last_name']},\n\nYour reservation details are as follows:\n\nRoom Type: {$reservation['room_type']}\nNumber of Guests: {$reservation['num_guests']}\nRoom ID: {$reservation['room_id']}\nCheck-in Date: {$reservation['checkin_date']}\nCheck-out Date: {$reservation['checkout_date']}\nYou have paid: {$reservation['room_price']} ETB\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for staying with us at Ehitimamachochi Hotel.";

                $mail->send();
                $status = "success";
                $message = "The reservation details have been sent to your email.";
            } catch (Exception $e) {
                $message = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $message = "Reservation not found.";
        }
    }

    if ($stmt->error) {
        $message = "SQL Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $message = "Invalid request method or missing parameters.";
}

// Display SweetAlert messages
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '" . ($status === 'success' ? 'success' : 'error') . "',
            title: '" . ($status === 'success' ? 'Success' : 'Error') . "',
            text: '" . $message . "'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
    });
</script>";
?>
