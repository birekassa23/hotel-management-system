<?php
// Include database connection
include '../assets/conn.php';

// Start session
session_start();

// Initialize error message
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from the form
    $user_username = $_POST['username'];
    $user_password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']); // Check if "Remember Me" is checked

    // Prepare and execute statement to check username and retrieve password and position
    $stmt = $conn->prepare("SELECT password, position, is_present FROM employees WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $user_username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists
    if ($stmt->num_rows === 0) {
        $error_message = 'Username not found';
    } else {
        // Bind result and fetch values
        $stmt->bind_result($db_password, $db_position, $is_present);
        $stmt->fetch();

        // Verify password
        if (!password_verify($user_password, $db_password)) {
            $error_message = 'Incorrect password';
        } elseif ($is_present !== 'yes') {
            // Verify attendance
            $error_message = 'Access denied. Your attendance is marked as absent.';
        } else {
            // If all checks pass, set session variables
            $_SESSION['username'] = $user_username; // Store username in session
            $_SESSION['position'] = $db_position;    // Store position in session

            // Set cookie if "Remember Me" is checked
            if ($remember_me) {
                setcookie('username', $user_username, time() + (86400 * 30), "/", "", true, true); // Cookie lasts for 30 days
            } else {
                // If not checked, clear the cookie
                if (isset($_COOKIE['username'])) {
                    setcookie('username', '', time() - 3600, "/"); // Expire cookie
                }
            }

            // Redirect based on position
            header("Location: ../$db_position/index.php");
            exit();
        }
    }

    // Close statement
    $stmt->close();
}

// Output SweetAlert2 for error message if there is an error
if (!empty($error_message)) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '$error_message',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('loader').style.display = 'none'; // Hide loader after error
                    window.location.href = 'index.php';
                }
            });
        });
    </script>
    ";
}

// Close connection
$conn->close();
?>
