<?php
//include database connection
include '../../assets/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Retrieve form data
    $itemName = $_POST['item_name'];
    $category = $_POST['category'];
    $itemType = $_POST['item_type'];

    // Sanitize inputs
    $itemName = htmlspecialchars($itemName, ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $itemType = htmlspecialchars($itemType, ENT_QUOTES, 'UTF-8');

    // Determine the table based on item type
    $table = '';
    if ($itemType === 'foods') {
        $table = 'foods'; // Replace with actual food table name
    } elseif ($itemType === 'beverages') {
        $table = 'beverages'; // Replace with actual beverage table name
    }

    // Prepare SQL query
    $sql = "DELETE FROM $table WHERE item_name = ? AND category = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param('ss', $itemName, $category);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Item deleted successfully.',
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
                                icon: 'info',
                                title: 'No Changes!',
                                text: 'No item was deleted. Please check if the item exists.',
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
        } else {
            echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Error executing the deletion. Please try again.',
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
    } else {
        echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to prepare the SQL statement.',
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
