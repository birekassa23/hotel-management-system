<?php
// Include database connection
include '../assets/conn.php';
session_start(); // Start the session

// // Check if the user is logged in
// if (!isset($_SESSION['username'])) {
//     header("Location: ../index/index.php"); // Redirect to login if not logged in
//     exit();
// }

// // Check if the user's position is 'casher' (case-insensitive)
// if (strtolower($_SESSION['position']) !== 'casher') {
//     header("Location: ../index/index.php"); // Redirect if not a 'casher'
//     exit();
// }
// ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Management - Ehototmamachochi Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <!-- Navbar -->
     <?php include 'asset/navbar.php' ?>
    <!-- Account Settings Section -->
    <div class="container mt-5" style="max-width: 600px;">
        <h2 class="text-center mb-4"><i class="bi bi-gear-fill me-2"></i>Account Settings</h2>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showSection('change_username')">
                <span><i class="bi bi-person-fill me-2"></i>Change Username</span><i class="bi bi-chevron-right"></i>
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showSection('change_password')">
                <span><i class="bi bi-lock-fill me-2"></i>Change Password</span><i class="bi bi-chevron-right"></i>
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="confirmLogout()">
                <span><i class="bi bi-box-arrow-right me-2"></i>Log out</span><i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <!-- Change Username Section -->
        <div class="card mt-4" id="change_username" style="display:none;">
            <div class="card-body">
                <h3 class="card-title"><i class="bi bi-person-fill me-2"></i>Change Username</h3>
                <form action="change_username_process.php" method="post">
                    <div class="mb-3">
                        <label for="current_username" class="form-label">Current Username:</label>
                        <input type="text" id="current_username" name="current_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_username" class="form-label">New Username:</label>
                        <input type="text" id="new_username" name="new_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_username" class="form-label">Confirm New Username:</label>
                        <input type="text" id="confirm_username" name="confirm_username" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" name="change_username" class="btn btn-success"><i class="bi bi-check-circle me-2"></i>Update</button>
                        <button type="reset" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Clear</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="card mt-4" id="change_password" style="display:none;">
            <div class="card-body">
                <h3 class="card-title"><i class="bi bi-lock-fill me-2"></i>Change Password</h3>
                <form action="change_password_process.php" method="post">
                    <div class="mb-3">
                        <label for="current_username" class="form-label">Current Username:</label>
                        <input type="text" id="current_username" name="current_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password:</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" name="change_password" class="btn btn-success"><i class="bi bi-check-circle me-2"></i>Change</button>
                        <button type="reset" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php include '../assets/footer.php'; ?>
</body>
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.card').forEach(card => card.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }
        
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</html>
