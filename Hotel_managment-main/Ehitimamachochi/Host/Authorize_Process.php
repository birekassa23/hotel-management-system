<?php
//include database connection
include '../assets/conn.php';

// Handle form submission for updating reservation
if (isset($_POST['email']) && isset($_POST['password'])) {  // Check if form is submitted
    // Get the data from POST request
    $cust_email = $_POST['email'];
    $cust_password = $_POST['password'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Prepare the SELECT query to check if the reservation exists
        $sql = "SELECT * FROM reserved_inventory WHERE cust_email = ? AND cust_password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cust_email, $cust_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If the reservation exists, update the authorization column
            $update_sql = "UPDATE reserved_inventory SET isServed = 'yes' WHERE cust_email = ? AND cust_password = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $cust_email, $cust_password);

            if ($update_stmt->execute()) {
                // Commit transaction
                $conn->commit();
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Reservation updated successfully.',
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
                // Rollback transaction
                $conn->rollback();
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Error updating reservation. Please try again.',
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
            // Rollback transaction
            $conn->rollback();
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'No reservation found with the provided email and password. Please try again.',
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
    } catch (Exception $e) {
        // Rollback transaction in case of an exception
        $conn->rollback();
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred: " . $e->getMessage() . "',
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

    $conn->close();
}
?>
