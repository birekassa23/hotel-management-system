
<?php
//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Initialize response variables
$status = "error";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_email']) && isset($_POST['view_password'])) {
    $email = $conn->real_escape_string(trim($_POST['view_email']));
    $password = $conn->real_escape_string(trim($_POST['view_password']));

    $sql = "SELECT cust_first_name, cust_last_name, cust_email, item_name, item_category,item_quantity, item_price, created_at, adjusted_Time 
            FROM 	reserved_inventory
            WHERE cust_email = ? AND cust_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        $mail = getMailer();
        try {

            // Recipients
            $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');  //  sender address
            $mail->addAddress($email);                                  // recipient

            $mail->isHTML(true);
            $mail->Subject = 'Payment Details for Your Reservation';
            $mail->Body = "<p>Dear {$payment['cust_first_name']} {$payment['cust_last_name']},</p>
                            <p>Your payment details are as follows:</p>
                            <strong>Category:</strong> {$payment['item_category']}<br>
                            <p><strong>Item Name:</strong> {$payment['item_name']}<br>
                            <strong>Quantity:</strong> {$payment['item_quantity']}<br>
                            <strong>Price:</strong> {$payment['item_price']}<br>
                            <strong>Payment Date:</strong> {$payment['created_at']}<br>
                            <strong>Adjusted Time:</strong> {$payment['adjusted_Time']}</p>
                            <p>If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                            <p>Thank you for choosing Ehitimamachochi Hotel.</p>";
            $mail->AltBody = "Dear {$payment['cust_first_name']} {$payment['cust_last_name']},\n\nYour payment details are as follows:\n\nItem Name: {$payment['item_name']}\nCategory: {$payment['item_category']}\n<strong>Quantity:</strong> {$payment['item_quantity']}<br>\nPrice: {$payment['item_price']}\nPayment Date: {$payment['created_at']}\nAdjusted Time: {$payment['adjusted_Time']}\n\nIf you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\nThank you for choosing Ehitimamachochi Hotel.";

            $mail->send();
            $status = "success";
            $message = "The payment details have been sent to your email.";
        } catch (Exception $e) {
            $message = "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        $message = "Payment details not found.";
    }

    if ($stmt->error) {
        $message = "SQL Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $message = "Invalid request method or missing parameters.";
}

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
