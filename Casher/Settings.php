<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap5.1.3_links.php'; ?> <!-- Include Bootstrap links -->
    <style>
        /* Additional styling for a clean layout */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-arrow {
            cursor: pointer;
            font-size: 1.5rem;
        }

        .card {
            border-radius: 8px;
        }

        .container-fluid {
            margin-top: 50px;
        }

        .list-group-item {
            cursor: pointer;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
        }
    </style>
</head>

<body>
    <?php include 'asset/navbar.php'; ?> <!-- Include Navbar -->

    <div class="container-fluid" style="margin-top:100px;">
        <div class="row">
            <?php include 'asset/sidebar.php'; ?> <!-- Include Sidebar -->
            <div class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <div class="container mt-5" style="max-width: 600px;">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-gear-fill me-2"></i>Account Settings
                    </h2>

                    <!-- Settings Options -->
                    <section id="set_option" class="list-group">
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            onclick="showSection('change_username')">
                            <span><i class="bi bi-person-fill me-2"></i>Change Username</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                            onclick="showSection('change_password')">
                            <span><i class="bi bi-lock-fill me-2"></i>Change Password</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
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
                                    <input type="text" id="current_username" name="current_username"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password:</label>
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_username" class="form-label">New Username:</label>
                                    <input type="text" id="new_username" name="new_username" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_username" class="form-label">Confirm New Username:</label>
                                    <input type="text" id="confirm_username" name="confirm_username"
                                        class="form-control" required>
                                </div>
                                <div class="btn-group">
                                    <button type="submit" name="change_username" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>Update
                                    </button>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Clear
                                    </button>
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
                                    <input type="text" id="current_username" name="current_username"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password:</label>
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password:</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password:</label>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                        class="form-control" required>
                                </div>
                                <div class="btn-group">
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
            </div>
        </div>
    </div>

    <script>
        function showSection(id) {
            // Get all sections
            const sections = document.querySelectorAll('section.card');

            // Hide all sections
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Toggle the selected section
            const selectedSection = document.getElementById(id);
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }
        }

        function confirmLogout(event) {
    event.preventDefault(); // Prevent the default behavior of the link (no page reload)

    Swal.fire({
        icon: 'question',  // Icon for the dialog box
        title: 'Are you sure?',  // Title of the confirmation modal
        text: 'Do you want to log out?',  // Text displayed under the title
        showCancelButton: true,  // Show a cancel button
        confirmButtonText: 'Yes, log out',  // Confirm button text
        cancelButtonText: 'Cancel'  // Cancel button text
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirms the logout, redirect to the login page or home page
            window.location.href = "http://localhost/New/Ehitimamachochi/index/index.php";
        }
    });
}

    </script>
</body>

</html>