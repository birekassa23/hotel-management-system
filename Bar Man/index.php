<?php
// Include database connection
include '../assets/conn.php';
session_start(); // Start the session

// // Check if the user's position is 'casher'
// if ($_SESSION['position'] !== 'BARMAN' && $_SESSION['position'] !== 'Casher') {
//     // Redirect to login page if the user is not a 'casher'
//     header("Location: ../index/index.php");
//     exit();
// }

// // Check if the user is logged in
// if (!isset($_SESSION['username'])) {
//     // Redirect to login page if not logged in
//     header("Location: ../index/index.php");
//     exit();
// }

// $conn->close();

// ?>

<script>
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Management - Ehototmamachochi Hotel</title>
    <!-- -->
    <?php include 'asset/header_links.php'; ?>
    <link rel="stylesheet" href="asset/index.css" class="css">
</head>

<body>
    <?php include 'asset/navbar.php'; ?>

    <!-- Main Content -->
    <main style="margin-top:10px">
        <div class="container">
            <h2>recently added </h2>
        </div>
    </main>

    <?php include '../assets/footer.php'; ?>

</body>
<!-- Include the shared footer scripts -->
<?php include 'asset/footer_scripts.php'; ?>

</html>