<?php
//include database connection
include '../assets/conn.php';

//include mail config connection
include '../assets/email_config.php';

// Function to display messages
function displayMessage($type, $title, $message)
{
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = $conn->real_escape_string(trim($_POST['email']));
    $verification_code = $conn->real_escape_string(trim($_POST['password']));
    $item_name = $conn->real_escape_string(trim($_POST['item_name']));
    $item_category = $conn->real_escape_string(trim($_POST['item_category']));
    $required_quantity = intval($_POST['required_quantity']);
    $available_quantity = intval($_POST['available_quantity']);
    $item_price = floatval($_POST['new_update_price']);
    $created_at = $conn->real_escape_string(trim($_POST['update_current_date_and_time']));
    $adjusted_time = $conn->real_escape_string(trim($_POST['adjusted_Time']));

    // Check if payment details exist using email and verification_code
    $sql = "SELECT cust_first_name, cust_last_name, item_name, item_category, item_price, created_at, adjusted_Time 
            FROM reserved_inventory WHERE cust_email = ? AND cust_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user details
        $payment = $result->fetch_assoc();
        $first_name = $payment['cust_first_name'];
        $last_name = $payment['cust_last_name'];
        $previous_item_name = $payment['item_name'];
        $previous_item_category = $payment['item_category'];
        $previous_item_price = $payment['item_price'];
        $previous_created_at = $payment['created_at'];
        $previous_adjusted_time = $payment['adjusted_Time'];

        // Start transaction
        $conn->begin_transaction();

        // Insert previous data into updated_inventory_reservation
        $insert_sql = "INSERT INTO updated_inventory_reservation 
                        (first_name, last_name, email, phone, item_category, item_name, item_price, reserved_at, adjusted_Time, verification_code) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssssssssss", $first_name, $last_name, $email, $verification_code, $previous_item_category, $previous_item_name, $previous_item_price, $previous_created_at, $previous_adjusted_time, $verification_code);
        if (!$insert_stmt->execute()) {
            $conn->rollback();
            displayMessage('error', 'Error', 'Error logging the previous reservation data.');
            exit;
        }

        // Update reservation details
        $sql = "UPDATE reserved_inventory SET item_name = ?, item_category = ?, item_price = ?, created_at = ?, adjusted_Time = ?
                WHERE cust_email = ? AND cust_password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $item_name, $item_category, $item_price, $created_at, $adjusted_time, $email, $verification_code);

        if ($stmt->execute()) {
            // Commit transaction
            $conn->commit();

            // Send email
            $mail = getMailer();
            try {

                // Recipients
                $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Payment Details Updated';
                $mail->Body = "<p>Dear $first_name $last_name,</p>
                                <p>Your payment details have been successfully updated.</p>
                                <p><strong>Item Name:</strong> $item_name<br>
                                <strong>Category:</strong> $item_category<br>
                                <strong>Price:</strong> $item_price<br>
                                <strong>Created At:</strong> $created_at<br>
                                <strong>Adjusted Time:</strong> $adjusted_time</p>
                                <p>If you have any questions, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                <p>Thank you for choosing Ehitimamachochi Hotel.</p>";
                $mail->AltBody = "Dear $first_name $last_name,\n\nYour payment details have been updated.\n\nItem Name: $item_name\nCategory: $item_category\nPrice: $item_price\nCreated At: $created_at\nAdjusted Time: $adjusted_time\n\nIf you have any questions, contact us at contactus@ehitimamachochihotel.com.\n\nThank you for choosing Ehitimamachochi Hotel.";

                $mail->send();

                // Success message
                displayMessage('success', 'Success!', 'Payment details updated successfully!');
            } catch (Exception $e) {
                $conn->rollback();
                displayMessage('error', 'Email Sending Failed', "Failed to send email. Mailer Error: " . $mail->ErrorInfo);
            }
        } else {
            $conn->rollback();
            displayMessage('error', 'Error', 'Error updating payment details.');
        }
    } else {
        // Display error message
        displayMessage('error', 'Error', 'No matching record found for the provided email and password.');
    }

    $stmt->close();
    $conn->close();
}
?>
