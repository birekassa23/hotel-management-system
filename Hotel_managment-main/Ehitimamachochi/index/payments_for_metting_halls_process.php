
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Function to output SweetAlert2 script
function showAlert($icon, $title, $text)
{
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$text',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.history.back();
                }
            });
        });
    </script>";
}

// Generate a unique verification code
function generateVerificationCode($conn)
{
    do {
        $code = rand(100000, 999999);
        $sql = "SELECT COUNT(*) FROM reserved_meeting_halls WHERE password = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            showAlert('error', 'Prepare Statement Failed', 'Prepare failed: ' . $conn->error);
            exit();
        }

        $stmt->bind_param("i", $code);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            break;
        }
    } while (true);

    return $code;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = $conn->real_escape_string($_POST['phone']);
    $hall_type = $conn->real_escape_string($_POST['hall_type']);
    $hall_id = intval($_POST['hall_id']);
    $hall_price = isset($_POST['hall_price']) ? floatval($_POST['hall_price']) : 0.0;
    $checkin_date = $conn->real_escape_string($_POST['checkin_date']);
    $checkout_date = $conn->real_escape_string($_POST['checkout_date']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showAlert('error', 'Invalid Email', 'Please enter a valid email address.');
        exit();
    }

    // Generate a unique verification code
    $verification_code = generateVerificationCode($conn);

    // Validate check-in and check-out dates
    $current_date = date('Y-m-d');

    if ($checkin_date < $current_date) {
        showAlert('error', 'Invalid Check-in Date', 'Check-in date cannot be in the past.');
        exit();
    }

    if ($checkout_date <= $checkin_date) {
        showAlert('error', 'Invalid Check-out Date', 'Check-out date must be after the check-in date.');
        exit();
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert reservation data into the database
        $sql = "INSERT INTO reserved_meeting_halls (first_name, last_name, email, phone, hall_type, hall_id, hall_price, checkin_date, checkout_date, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            showAlert('error', 'Prepare Statement Failed', 'Prepare failed: ' . $conn->error);
            exit();
        }

        $stmt->bind_param("sssssiiidd", $first_name, $last_name, $email, $phone, $hall_type, $hall_id, $hall_price, $checkin_date, $checkout_date, $verification_code);

        if ($stmt->execute()) {
            // Update hall status to 'occupied'
            $sql_update = "UPDATE table_meeting_halls SET status = 'occupied' WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);

            if (!$stmt_update) {
                showAlert('error', 'Prepare Update Statement Failed', 'Prepare update failed: ' . $conn->error);
                exit();
            }

            $stmt_update->bind_param("i", $hall_id);
            $stmt_update->execute();
            $stmt_update->close();

            // Send confirmation email
            $mail = getMailer();

            try {
                // Server settings

                // Recipients
                $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
                $mail->addAddress($email, $first_name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reservation Confirmation';
                $mail->Body = "<p>Dear $first_name $last_name,</p>
                                <p>Your reservation has been confirmed at Your Hotel Name.</p>
                                <p>Hall Type: $hall_type</p>
                                <p>Hall ID: $hall_id</p>
                                <p>Price: $hall_price</p>
                                <p>Check-in Date: $checkin_date</p>
                                <p>Check-out Date: $checkout_date</p>
                                <p><b>Your Verification Code:</b> <span style='font-size: 24px; font-weight: bold; color: #007BFF;'>$verification_code</span></p>
                                <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contact@yourhotel.com'>contact@yourhotel.com</a>.</p>";

                $mail->AltBody = "Dear $first_name $last_name,\n\n" .
                    "Your reservation has been confirmed at Your Hotel Name.\n" .
                    "Hall Type: $hall_type\n" .
                    "Hall ID: $hall_id\n" .
                    "Price: $hall_price\n" .
                    "Check-in Date: $checkin_date\n" .
                    "Check-out Date: $checkout_date\n\n" .
                    "Your Verification Code: $verification_code\n\n" .
                    "If you have any questions or need further assistance, please contact us at contact@yourhotel.com.";
                $mail->send();
                $conn->commit();
                showAlert('success', 'Reservation Completed', 'Your reservation has been completed successfully!');

            } catch (Exception $e) {
                $conn->rollback();
                showAlert('error', 'Email Sending Failed', 'Failed to send email. Mailer Error: ' . $mail->ErrorInfo);
                exit();
            }
        } else {
            $conn->rollback();
            showAlert('error', 'Database Error', 'Error: ' . $stmt->error);
            exit();
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        showAlert('error', 'Transaction Failed', 'An unexpected error occurred.');
        exit();
    }

    $conn->close();
} else {
    showAlert('error', 'Invalid Request', 'Invalid request method or missing parameters.');
}
?>
