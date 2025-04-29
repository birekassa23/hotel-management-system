
<?php
//include database connection
include '../assets/conn.php';

try {
    // Get and trim user input from the POST request
    $fromUserName = trim($_POST['Username']);
    $theComment = trim($_POST['message']);

    // Check if the input fields are not empty
    if (!empty($fromUserName) && !empty($theComment)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO `comment` (`fromUserName`, `Date`, `theComment`) VALUES (?, CURRENT_TIMESTAMP, ?)");

        // Bind parameters to the SQL statement
        $stmt->bind_param("ss", $fromUserName, $theComment);

        // Execute the SQL statement
        $stmt->execute();

        // Redirect with a success message
        header("Location: index.php?status=success");
        exit();
    } else {
        echo "Error: Username or message cannot be empty.";
    }

} catch (Exception $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}
?>
