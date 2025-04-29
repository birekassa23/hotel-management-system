<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Initialize response variables
$status = "error";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['View_email']) && isset($_POST['View_password'])) {
    $email = $conn->real_escape_string(trim($_POST['View_email']));
    $password = $conn->real_escape_string(trim($_POST['View_password']));

    $sql = "SELECT first_name, last_name, email, phone, hall_type, hall_id, hall_price, checkin_date, checkout_date 
            FROM reserved_meeting_halls 
            WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $message = "SQL Error: " . $conn->error;
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
                $mail->Subject = 'Meeting Hall Reservation Details';
                $mail->Body = "<p>Dear {$reservation['first_name']} {$reservation['last_name']},</p>
                                <p>Your meeting hall reservation details are as follows:</p>
                                <p><strong>Hall Type:</strong> {$reservation['hall_type']}<br>
                                <strong>Hall ID:</strong> {$reservation['hall_id']}<br>
                                <strong>Price:</strong> {$reservation['hall_price']}<br>
                                <strong>Check-in Date:</strong> {$reservation['checkin_date']}<br>
                                <strong>Check-out Date:</strong> {$reservation['checkout_date']}</p>
                                <p><strong>You have Paid:</strong> {$reservation['hall_price']}</p>
                                <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                <p>Thank you for staying with us at Ehitimamachochi Hotel.</p>";
                $mail->AltBody = "Dear {$reservation['first_name']} {$reservation['last_name']},\n\nYour meeting hall reservation details are as follows:\n\nHall Type: {$reservation['hall_type']}\nHall ID: {$reservation['hall_id']}\nPrice: {$reservation['hall_price']}\nCheck-in Date: {$reservation['checkin_date']}\nCheck-out Date: {$reservation['checkout_date']}\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for staying with us at Ehitimamachochi Hotel.";

                $mail->send();
                $status = "success";
                $message = "The reservation details have been sent to your email.";
            } catch (Exception $e) {
                $message = "Error sending email: " . $mail->ErrorInfo;
            }
        } else {
            $message = "Reservation not found.";
        }

        if ($stmt->error) {
            $message = "SQL Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
} else {
    $message = "Invalid request method or missing parameters.";
}

// Output SweetAlert2 script
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
