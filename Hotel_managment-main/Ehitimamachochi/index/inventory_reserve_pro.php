
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

//include mail config connection
include '../assets/email_config.php';

//include database connection
include '../assets/conn.php';

// Function to output SweetAlert2 script
function showAlert($icon, $title, $text) {
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

// Start transaction
$conn->begin_transaction();

// Generate a unique verification code
function generateVerificationCode($conn) {
    do {
        $code = rand(100000, 999999);
        $sql = "SELECT COUNT(*) FROM Reserved_inventory WHERE cust_password = ?";
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
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $category = $conn->real_escape_string($_POST['category']);
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $item_price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $current_date_and_time = $conn->real_escape_string($_POST['current_date_and_time']);
    $adjusted_Time = $conn->real_escape_string($_POST['adjusted_Time']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $required_quantity = $conn->real_escape_string($_POST['required_quantity']);
    $item_price = $required_quantity * $item_price;
    $quantity = $conn->real_escape_string($_POST['quantity']); // For subtracting quantity of selected item by one

    if ($category == 'food') {
        // Subtract the required quantity from the current quantity
        $new_quantity = $quantity - $required_quantity;
        
        // Construct the query with the updated quantity
        $myquery = "UPDATE table_foods SET quantity = $new_quantity WHERE item_name = '$item_name';";
    } else {
        // Subtract the required quantity from the current quantity
        $new_quantity = $quantity - $required_quantity;

        // Construct the query with the updated quantity
        $myquery = "UPDATE table_beverages SET quantity = $new_quantity WHERE item_name = '$item_name';";
    }

    // Execute the query and check for errors
    if (!$conn->query($myquery)) {
        showAlert('error', 'Update Error', 'Error updating quantity: ' . $conn->error);
        $conn->rollback();
        exit();
    }

    // Generate a unique verification code
    $verification_code = generateVerificationCode($conn);

    // Insert reservation data into the database
    $sql = "INSERT INTO reserved_inventory (cust_first_name, cust_last_name, cust_sex, cust_email, cust_phone, item_category, item_name,item_quantity, item_price, created_at, adjusted_Time, cust_password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        showAlert('error', 'Prepare Statement Failed', 'Prepare failed: ' . $conn->error);
        $conn->rollback();
        exit();
    }

    $stmt->bind_param("ssssssssssss", $first_name, $last_name, $sex, $email, $phone, $category, $item_name,$required_quantity, $item_price, $current_date_and_time, $adjusted_Time, $verification_code);

    if ($stmt->execute()) {
        require 'vendor/autoload.php'; // Include the Composer autoload file

        // Create a new PHPMailer instance
        $mail = getMailer();

        try {

            // Recipients
            $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
            $mail->addAddress($email, $first_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reservation Confirmation';
            $mail->Body = "<p>Dear $first_name $last_name, welcome to Ehitimamachochi Hotel</p>
                <p>You reserved:</p>
                <ul>
                    <li>Item Category: $category</li>
                    <li>Item Name: $item_name</li>
                    <li>Quantity: $required_quantity</li>
                    <li>You paid: $item_price ETB</li>
                </ul>
                <p>Reservation time: $current_date_and_time</p>
                <p>Your Adjusted date and time: <b>$adjusted_Time</b></p>
                <p><b>Your Verification Code:</b> <span style='font-size: 24px; font-weight: bold; color: #007BFF;'>$verification_code</span></p>
                <p>Thank you for your reservation at Ehitimamachochi Hotel. If you have any questions or need further assistance, please contact us at <a href='mailto:contactus@ehitimamachochihotel.com'>contactus@ehitimamachochihotel.com</a>.</p>
                <p>Thank you for choosing Ehitimamachochi Hotel.</p>";
            $mail->AltBody = "Dear $first_name $last_name,\n\n" .
                "Thank you for your reservation at Ehitimamachochi Hotel.\n\n" .
                "Item Category: $category\n" .
                "Item Name: $item_name\n" .
                "Quantity: $required_quantity\n" .
                "You paid: $item_price ETB\n" .
                "Reservation Time: $current_date_and_time\n" .
                "Adjusted Date and Time for Reservation: $adjusted_Time\n" .
                "Your Verification Code: $verification_code\n\n" .
                "If you have any questions or need further assistance, please contact us at contactus@ehitimamachochihotel.com.\n\n" .
                "Thank you for choosing Ehitimamachochi Hotel.";

            $mail->send();
            $conn->commit();
            showAlert('success', 'Reservation Completed', 'Your reservation has been completed successfully!');
        } catch (Exception $e) {
            showAlert('error', 'Email Sending Failed', 'Failed to send email. Mailer Error: ' . $mail->ErrorInfo);
            $conn->rollback();
            exit();
        }
    } else {
        showAlert('error', 'Database Error', 'Error: ' . $stmt->error);
        $conn->rollback();
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    showAlert('error', 'Invalid Request', 'Invalid request method or missing parameters.');
    $conn->rollback();
}
?>
