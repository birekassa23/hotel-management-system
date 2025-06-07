
<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Ensure SweetAlert2 is included once
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
                    window.history.back();
                }
            });
        });
    </script>";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = $conn->real_escape_string(trim($_POST['cancel_email']));
    $password = $conn->real_escape_string(trim($_POST['cancel_password']));

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check if inventory reservation exists
        $sql = "SELECT * FROM reserved_inventory WHERE cust_email = ? AND cust_password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the reservation details
            $reservation = $result->fetch_assoc();
            $first_name = $reservation['cust_first_name'];
            $last_name = $reservation['cust_last_name'];
            $item_category = $reservation['item_category'];
            $item_name = $reservation['item_name'];
            $item_quantity = $reservation['item_quantity'];
            $item_price = $reservation['item_price'];
            $checkin_date = $reservation['created_at'];
            $checkout_date = $reservation['adjusted_Time'];
            $verification_code = $reservation['cust_password'];

            // Prepare SQL to insert data into canceled_inventory_reservation
            $sql = "INSERT INTO canceled_inventory_reservation (first_name, last_name, email, phone, item_category, item_name,item_quantity, item_price, checkin_date, checkout_date, verification_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssss", $first_name, $last_name, $email, $reservation['cust_phone'], $item_category, $item_name, $item_quantity,$item_price, $checkin_date, $checkout_date, $verification_code);
            
            if ($stmt->execute()) {
                // Update inventory status back to 'free'
                $table_name = ($item_category == 'food') ? 'table_foods' : 'table_beverages';
                $sql_update = "UPDATE $table_name SET quantity = quantity + $item_quantity WHERE item_name = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("s", $item_name);
                $stmt_update->execute();

                // Delete the reservation from reserved_inventory
                $sql_delete = "DELETE FROM reserved_inventory WHERE cust_email = ? AND cust_password = ?";
                $stmt_delete = $conn->prepare($sql_delete);
                $stmt_delete->bind_param("ss", $email, $password);

                if ($stmt_delete->execute()) {
                    // Commit transaction
                    $conn->commit();
                    showAlert('success', 'Success!', 'Reservation canceled successfully!');

                    // Send email with PHPMailer
                    $mail = getMailer();
                    try {

                        // Recipients
                        $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Inventory Reservation Canceled';
                        $mail->Body = "<p>Dear $first_name $last_name,</p>
                                        <p>Your inventory reservation has been successfully <span style='color: red;'>canceled</span>.</p>
                                        <p>You can claim your refund by visiting Ehitimamachochi Hotel in person.</p>
                                        <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                                        <p>Thank you for choosing Ehitimamachochi Hotel.</p>";
                        $mail->AltBody = "Dear $first_name $last_name,\n\nYour inventory reservation has been successfully canceled.\n\nYou can claim your refund by visiting Ehitimamachochi Hotel in person.\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for choosing Ehitimamachochi Hotel.";

                        $mail->send();
                    } catch (Exception $e) {
                        $error_message = "Failed to send email. Mailer Error: " . $mail->ErrorInfo;
                        showAlert('error', 'Email Sending Failed', $error_message);
                    }
                } else {
                    throw new Exception("Error canceling reservation.");
                }
            } else {
                throw new Exception("Error transferring reservation data.");
            }
        } else {
            throw new Exception("Reservation not found.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = $e->getMessage();
        showAlert('error', 'Error', $error_message);
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>
