<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Manager - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap_links.php'; ?> <!-- Include Bootstrap CSS links -->

    <style>
        /* Ensure that the page content takes up available space */
        * {
            font-family: 'Times New Roman';
            margin: 0;
            padding: 0;
        }

        .container {
            flex-grow: 1;
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Styling for the collapse list group */
        .list-group-item {
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #007bff;
            color: #fff;
        }

        /* Card body padding and title styling */
        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .btn-link {
            font-size: 1.5rem;
        }

        .form-label {
            font-weight: bold;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <?php include 'asset/nav-bar.php'; ?> <!-- Include Navbar -->

        <!-- Main Container -->
        <div class="container">
            <!-- Account Settings Content -->
            <div id="default_set">
                <div class="section-header">
                    <button class="btn btn-link" onclick="goBack()">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <h3 class="text-center flex-grow-1 mb-0">Account Settings</h3>
                </div>

                <a href="#change_username_form" class="list-group-item list-group-item-action" data-bs-toggle="collapse" aria-expanded="false" aria-controls="change_username_form" ondblclick="toggleCollapse('change_username_form')">
                    <span><i class="bi bi-person-fill me-2"></i>Change Username</span>
                    <i class="bi bi-chevron-right"></i>
                </a>

                <!-- Change Username Form -->
                <div id="change_username_form" class="collapse">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title"><i class="bi bi-person-fill me-2"></i>Change Username</h3>
                            <form action="change_username_process.php" method="post">
                                <div class="mb-3">
                                    <label for="current_username" class="form-label">Current Username:</label>
                                    <input type="text" id="current_username" name="current_username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_username" class="form-label">New Username:</label>
                                    <input type="text" id="new_username" name="new_username" class="form-control" required>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="reset" class="btn btn-secondary ms-3">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="#change_password_form" class="list-group-item list-group-item-action" data-bs-toggle="collapse" aria-expanded="false" aria-controls="change_password_form" ondblclick="toggleCollapse('change_password_form')">
                    <span><i class="bi bi-lock-fill me-2"></i>Change Password</span>
                    <i class="bi bi-chevron-right"></i>
                </a>

                <!-- Change Password Form -->
                <div id="change_password_form" class="collapse">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title"><i class="bi bi-lock-fill me-2"></i>Change Password</h3>
                            <form action="change_password_process.php" method="post">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password:</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="reset" class="btn btn-secondary ms-3">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="#" class="list-group-item list-group-item-action" onclick="confirmLogout()">
                    <span><i class="bi bi-box-arrow-right me-2"></i>Log out</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <?php include 'asset/footer.php'; ?>
    
    <?php include 'asset/bootstrap_links.php'; ?> <!-- Include Bootstrap JS links -->

    <script>
        function goBack() {
            document.querySelectorAll('.collapse').forEach(section => section.classList.remove('show'));
            document.getElementById('default_set').style.display = 'block';
        }

        function toggleCollapse(targetId) {
            const targetElement = document.getElementById(targetId);
            if (targetElement.classList.contains('show')) {
                targetElement.classList.remove('show');
            } else {
                targetElement.classList.add('show');
            }
        }

        function confirmLogout() {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to log out?',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "http://localhost/New/Ehitimamachochi/index/index.php";
                }
            });
        }
    </script>
</body>

</html>
