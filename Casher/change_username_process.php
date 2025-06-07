
<?php
//include database connection
include '../assets/conn.php';

// Handle form submission for updating username
if (isset($_POST['change_username'])) {
    // Get the updated data from POST request
    $current_username = $_POST['current_username'];
    $current_password = $_POST['current_password'];
    $new_username = $_POST['new_username'];
    $confirm_username = $_POST['confirm_username'];

    // Validate input
    if (empty($current_username) || empty($current_password) || empty($new_username) || empty($confirm_username)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'All fields are required.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            });
        </script>
        ";
        exit();
    }

    if ($new_username !== $confirm_username) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'New usernames do not match.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            });
        </script>
        ";
        exit();
    }

    // Prepare the SELECT query to check if the employee exists
    $sql = "SELECT * FROM employees WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $current_username, $current_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the employee exists, update the username
        $update_sql = "UPDATE employees SET username = ? WHERE username = ? AND password = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $new_username, $current_username, $current_password);

        if ($update_stmt->execute()) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Username updated successfully.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.history.back();
                        }
                    });
                });
            </script>
            ";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error updating username. Please try again.',
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

        $update_stmt->close();
    } else {
        // If no employee is found
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Username or password incorrect. Please try again.',
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

    $stmt->close();
    $conn->close();
}
?>
