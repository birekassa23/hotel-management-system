<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Management - Ehototmamachochi Hotel</title>
    <?php include 'asset/header_links.php'; ?>

    <link rel="stylesheet" href="asset/index.css" class="css">
    <style>
        /* Ensures the footer is at the bottom of the page */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
            text-align: center;
            padding: 1rem 0;
        }
    </style>
</head>

<body>

    <?php include 'asset/navbar.php'; ?>
    <div class="container mt-5" style="max-width: 600px;">
        <h2 class="text-center mb-4"><i class="bi bi-gear-fill me-2"></i>Account Settings</h2>

        <section id="set_option" class="list-group">
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('change_username')">
                <span><i class="bi bi-person-fill me-2"></i>Change Username</span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('change_password')">
                <span><i class="bi bi-lock-fill me-2"></i>Change Password</span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="confirmLogout(event)">
                <span><i class="bi bi-box-arrow-right me-2"></i>Log out</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </section>

        <!-- Change Username Section -->
        <section class="card mt-4" id="change_username" style="display:none;">
            <div class="card-body">
                <div class="section-header">
                    <h3 class="card-title"><i class="bi bi-person-fill me-2"></i>Change Username</h3>
                    <i class="bi bi-arrow-left back-arrow" onclick="showSection('')"></i>
                </div>
                <form action="change_username_process.php" method="post">
                    <div class="mb-3">
                        <label for="current_username" class="form-label">Current Username:</label>
                        <input type="text" id="current_username" name="current_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" class="form-control"
                            required>
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
                        <button type="submit" name="change_username" class="btn btn-success"><i
                                class="bi bi-check-circle me-2"></i>Update</button>
                        <button type="reset" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Clear</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Change Password Section -->
        <section class="card mt-4" id="change_password" style="display:none;">
            <div class="card-body">
                <div class="section-header">
                    <h3 class="card-title"><i class="bi bi-lock-fill me-2"></i>Change Password</h3>
                    <i class="bi bi-arrow-left back-arrow" onclick="showSection('')"></i>
                </div>
                <form action="change_password_process.php" method="post">
                    <div class="mb-3">
                        <label for="current_username" class="form-label">Current Username:</label>
                        <input type="text" id="current_username" name="current_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password:</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                            required>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" name="change_password" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Change
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Clear
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php include '../assets/footer.php'; ?>
</body>
<!-- Include the shared footer scripts -->
<?php include 'asset/footer_scripts.php'; ?>
    <script>
        function showSection(id) {
            // Get all sections
            const sections = document.querySelectorAll('section.card');

            // Hide all sections
            sections.forEach(section => {
                if (section.id !== id) {
                    section.style.display = 'none';
                }
            });

            // Toggle the selected section
            const selectedSection = document.getElementById(id);
            selectedSection.style.display = (selectedSection.style.display === 'none' || selectedSection.style.display === '') ? 'block' : 'none';
        }

        function confirmLogout(event) {
            event.preventDefault(); // Prevent the default link behavior

            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to log out?',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the specified URL
                    window.location.href = "http://localhost/New/Ehitimamachochi/index/index.php";
                }
            });
        }
    </script>
</html>