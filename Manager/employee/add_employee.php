<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database and mail configuration
include '../../assets/conn.php';
include '../../assets/email_config.php';

// Ensure the upload directories exist
if (!file_exists('uploads/documents/')) {
    mkdir('uploads/documents/', 0777, true);
}
if (!file_exists('uploads/kebele_ids/')) {
    mkdir('uploads/kebele_ids/', 0777, true);
}

// Function to generate random username
function generateUsername($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
}

// Function to generate random password
function generatePassword($length = 6) {
    $characters = '0123456789';
    return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $sex = $_POST['sex'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $position = $_POST['position'];
    $edu_status = $_POST['edu_status'];
    $document = $_FILES['document'];
    $kebele_id = $_FILES['kebele_id'];

    $message = '';
    $messageType = 'error';

    try {
        // Check for duplicate employee
        $stmt = $conn->prepare("SELECT * FROM employees WHERE f_name = ? OR phone_no = ?");
        $stmt->bind_param('ss', $f_name, $phone_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("An employee with this name or phone number already exists.");
        }

        // Generate credentials
        $username = generateUsername();
        $password = generatePassword();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle file uploads
        $document_path = 'uploads/documents/' . basename($document['name']);
        $kebele_id_path = 'uploads/kebele_ids/' . basename($kebele_id['name']);

        if (!move_uploaded_file($document['tmp_name'], $document_path) || !move_uploaded_file($kebele_id['tmp_name'], $kebele_id_path)) {
            throw new Exception("Error uploading files.");
        }

        // Insert data into employees table
        $stmt = $conn->prepare("INSERT INTO employees (f_name, l_name, sex, age, email, phone_no, position, edu_status, document, kebele_id, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssssss', $f_name, $l_name, $sex, $age, $email, $phone_no, $position, $edu_status, $document_path, $kebele_id_path, $username, $hashed_password);

        if (!$stmt->execute()) {
            throw new Exception("Error registering employee: " . $stmt->error);
        }

        // Send welcome email
        $mail = getMailer();
        $mail->setFrom('birekassa1400@gmail.com', 'Ehitimamachochi Hotel');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Ehitimamachochi Hotel!';
        $mail->Body = "
            <p>Dear $f_name $l_name, welcome to Ehitimamachochi Hotel!</p>
            <p>Your username: <b><span style='color:blue;'>$username</span></b></p>
            <p>Your password: <b><span style='color:blue;'>$password</span></b></p>
            <p>Thank you for joining our team.</p>";

        $mail->send();

        $message = "Employee registered successfully.";
        $messageType = 'success';

    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <style>
        .loader {
            display: none;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .hidden { display: none; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Loader -->
    <div id="loader" class="loader"></div>

    <!-- Display SweetAlert2 messages -->
    <?php if (!empty($message)) : ?>
        <script>
            document.getElementById('loader').style.display = 'none';
            Swal.fire({
                icon: '<?php echo $messageType; ?>',
                title: '<?php echo ucfirst($messageType); ?>',
                text: '<?php echo $message; ?>',
            }).then(() => {
                window.history.back();
            });
        </script>
    <?php endif; ?>

    <!-- Your form or content -->
</body>
<script>
    // Show loader when the form is submitted
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('loader').style.display = 'block';
    });
</script>
</html>
