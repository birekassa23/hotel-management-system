<?php
// Include mail config connection
include '../assets/email_config.php';

// Include database connection
include '../assets/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = filter_var(trim($_POST['my_update_email']), FILTER_SANITIZE_EMAIL);
    $verification_code = trim($_POST['my_update_password']);
    $room_type = trim($_POST['my_update_room_type']);
    $room_id = trim($_POST['my_update_room_id']);
    $room_price = trim($_POST['my_update_room_price']);
    $checkin_date = trim($_POST['my_update_checkin_date']);
    $checkout_date = trim($_POST['my_update_checkout_date']);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showMessage('error', 'Invalid Email', 'Please provide a valid email address.');
        exit;
    }
    if (!is_numeric($room_price) || $room_price <= 0) {
        showMessage('error', 'Invalid Room Price', 'Please enter a valid room price.');
        exit;
    }
    if (strtotime($checkin_date) >= strtotime($checkout_date)) {
        showMessage('error', 'Invalid Dates', 'Check-out date must be later than check-in date.');
        exit;
    }

    // Check if reservation exists and fetch the necessary details
    $sql = "SELECT first_name, last_name, room_type, room_id, room_price, checkin_date, checkout_date, phone FROM reserved_rooms WHERE email = ? AND verification_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the reservation details
        $reservation = $result->fetch_assoc();
        $first_name = $reservation['first_name'];
        $last_name = $reservation['last_name'];
        $phone = $reservation['phone'];
        $old_room_type = $reservation['room_type'];
        $old_room_id = $reservation['room_id'];
        $old_room_price = $reservation['room_price'];
        $old_checkin_date = $reservation['checkin_date'];
        $old_checkout_date = $reservation['checkout_date'];

        // Check if there are changes to update
        if ($room_type !== $old_room_type || $room_id !== $old_room_id || $room_price !== $old_room_price || $checkin_date !== $old_checkin_date || $checkout_date !== $old_checkout_date) {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Update reservation details
                $sql_update = "UPDATE reserved_rooms SET room_type = ?, room_id = ?, room_price = ?, checkin_date = ?, checkout_date = ? WHERE email = ? AND verification_code = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("sssssss", $room_type, $room_id, $room_price, $checkin_date, $checkout_date, $email, $verification_code);

                if (!$stmt_update->execute()) {
                    throw new Exception("Failed to update reservation.");
                }

                // Log the update
                $sql_log = "INSERT INTO updated_room_reservation (first_name, last_name, email, phone, room_type, room_id, room_price, checkin_date, checkout_date, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_log = $conn->prepare($sql_log);
                $stmt_log->bind_param("sssssidsss", $first_name, $last_name, $email, $phone, $old_room_type, $old_room_id, $old_room_price, $old_checkin_date, $old_checkout_date, $verification_code);

                if (!$stmt_log->execute()) {
                    throw new Exception("Failed to log the update.");
                }

                // Check and update room reports (handle old and new room types)
                updateRoomReports($conn, $old_room_type, $room_type, $old_checkin_date, $checkin_date);

                // Commit the transaction after all updates
                $conn->commit();

                // Send confirmation email
                sendConfirmationEmail($first_name, $last_name, $email, $room_type, $room_id, $checkin_date, $checkout_date, $room_price);

                showMessage('success', 'Success!', 'Reservation updated successfully!');
            } catch (Exception $e) {
                $conn->rollback();
                showMessage('error', 'Error', $e->getMessage());
            }

        } else {
            showMessage('info', 'No Change', 'No changes detected in the reservation.');
        }

    } else {
        showMessage('error', 'Error', 'Reservation not found.');
    }

    $stmt->close();
    $conn->close();
}



// Function to update room reports
function updateRoomReports($conn, $old_room_type, $new_room_type, $old_checkin_date, $new_checkin_date) {
    // Handle the old room type
    if ($old_room_type !== $new_room_type) {
        $update_old_query = "UPDATE rooms_reports SET no_of_reserved_room = no_of_reserved_room - 1 WHERE room_type = ? AND reserved_date = ?";
        $stmt_update_old = $conn->prepare($update_old_query);
        $stmt_update_old->bind_param("ss", $old_room_type, $old_checkin_date);
        $stmt_update_old->execute();
        
        // Handle the new room type
        $query_new_type = "SELECT * FROM rooms_reports WHERE room_type = ? AND reserved_date = ?";
        $stmt_new_type = $conn->prepare($query_new_type);
        $stmt_new_type->bind_param("ss", $new_room_type, $new_checkin_date);
        $stmt_new_type->execute();
        $result_new_type = $stmt_new_type->get_result();

        if ($result_new_type->num_rows > 0) {
            // Update count for new room type
            $update_query_new = "UPDATE rooms_reports SET no_of_reserved_room = no_of_reserved_room + 1 WHERE room_type = ? AND reserved_date = ?";
            $stmt_update_new = $conn->prepare($update_query_new);
            $stmt_update_new->bind_param("ss", $new_room_type, $new_checkin_date);
            $stmt_update_new->execute();
        } else {
            // Insert new room type if not found
            $insert_query_new = "INSERT INTO rooms_reports (room_type, no_of_reserved_room, reserved_date) VALUES (?, 1, ?)";
            $stmt_insert_new = $conn->prepare($insert_query_new);
            $stmt_insert_new->bind_param("ss", $new_room_type, $new_checkin_date);
            $stmt_insert_new->execute();
        }
    }
}

// Function to send confirmation email
function sendConfirmationEmail($first_name, $last_name, $email, $room_type, $room_id, $checkin_date, $checkout_date, $room_price) {
    $mail = getMailer();
    $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Reservation Updated';
    $mail->Body = "<p>Dear $first_name $last_name,</p>
                   <p>Your reservation has been successfully updated.</p>
                   <p><strong>Room Type:</strong> $room_type<br>
                   <strong>Room ID:</strong> $room_id<br>
                   <strong>Check-in Date:</strong> $checkin_date<br>
                   <strong>Check-out Date:</strong> $checkout_date<br>
                   <strong>You have paid:</strong> $room_price ETB</p>
                   <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                   <p>Thank you for staying with us at Ehitimamachochi Hotel.</p>";
    $mail->AltBody = "Dear $first_name $last_name,\n\nYour reservation has been successfully updated.\n\nRoom Type: $room_type\nRoom ID: $room_id\nCheck-in Date: $checkin_date\nCheck-out Date: $checkout_date\nYou have paid: $room_price ETB\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for staying with us at Ehitimamachochi Hotel.";

    if (!$mail->send()) {
        throw new Exception("Failed to send email. Mailer Error: " . $mail->ErrorInfo);
    }
}

// Function to display SweetAlert messages
function showMessage($icon, $title, $message) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
              document.addEventListener('DOMContentLoaded', function() {
                  Swal.fire({
                      icon: '$icon',
                      title: '$title',
                      text: '$message',
                      confirmButtonText: 'OK'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.history.back();
                      }
                  });
              });
          </script>";
    exit;
}
?>
