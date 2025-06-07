<?php
//include database connection
include '../assets/conn.php';

// SQL query to update all employees' `is_present` field to 'not'
$sql = "UPDATE employees SET is_present = 'not'";

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'; // Default to 'index.php' if referer is not available

if ($conn->query($sql) === TRUE) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "All employees access are denied .",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "' . $referer . '";
                    }
                });
            });
        </script>
    </body>
    </html>';
} else {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Error updating records: ' . $conn->error . '",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "' . $referer . '";
                    }
                });
            });
        </script>
    </body>
    </html>';
}

// Close the connection
$conn->close();
?>
