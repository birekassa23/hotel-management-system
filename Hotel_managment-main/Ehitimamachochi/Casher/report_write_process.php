<?php
//include database connection
include '../assets/conn.php';

// Check connection
if ($conn->connect_error) {
    $error_message = "Connection failed: " . $conn->connect_error;
    $showError = true;
} 
else {
    // Get POST data
    $reportProvider = $_POST['report_provider'];
    $reportedDate = $_POST['reported_date'];
    $transactionType = $_POST['transactionType'];

    // Prepare and bind
    if ($transactionType === 'Deposit') {
        $stmt = $conn->prepare("INSERT INTO deposit_report (`Report Provider`, `Amount`, `Source from`, `reported_date`) VALUES (?, ?, ?, ?)");
    } else {
        $stmt = $conn->prepare("INSERT INTO withdrawal_report (`Report Provider`, `Amount`, `Source from`, `reported_date`) VALUES (?, ?, ?, ?)");
    }

    if (!$stmt) {
        $error_message = "Prepare failed: " . $conn->error;
        $showError = true;
    } else {
        // Bind parameters
        $stmt->bind_param("ssss", $reportProvider, $amount, $sourceFrom, $reportedDate);

        // Loop through the table rows and insert data
        foreach ($_POST['amount'] as $index => $amount) {
            $sourceFrom = $_POST['source_from'][$index];
            $stmt->execute();
        }

        // Close connections
        $stmt->close();
        $conn->close();

        $success_message = "Report submitted successfully!";
        $showSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Submission</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($showError) && $showError): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo addslashes($error_message); ?>'
                }).then(() => {
                    window.history.back();
                });
            <?php elseif (isset($showSuccess) && $showSuccess): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?php echo addslashes($success_message); ?>'
                }).then(() => {
                    window.location.href = 'index.php'; // Redirect to success page if needed
                });
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <!-- Optional: You can include some fallback HTML here if needed -->
</body>
</html>
