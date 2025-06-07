<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/payment.css" class="css">
</head>

<body>
    <!-- Wrapper for full-height layout -->
    <div class="d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="font-size: 1.25rem; height: 100px;">
            <div class="container-xl h-100">
                <a class="navbar-brand" href="index.php">Casher Panel</a>
                <div class="collapse navbar-collapse h-100 d-flex align-items-center" id="navbarNav">
                    <ul class="navbar-nav d-flex justify-content-center w-100 mb-0">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="index.php" style="margin: 0 1rem;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="Reports.php" style="margin: 0 1rem;">Reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="payment.php" style="margin: 0 1rem;">Pay Salary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="Settings.php" style="margin: 0 1rem;">Account Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Container -->
        <div class="container my-5">
            <h1 class="text-center mb-4">Employee Payment</h1>

            <?php
            // Include database connection
            include '../assets/conn.php';

            $showAlert = false;

            // Query to fetch employee data
            $query = "SELECT * FROM `employees`";
            $result = $conn->query($query);

            // Check if the employee table has data
            if ($result->num_rows > 0) {
                echo '<table class="table table-striped table-hover">';
                echo '<thead><tr>';
                echo '<th>First Name</th><th>Last Name</th><th>Sex</th><th>Age</th><th>Email</th><th>Phone No</th><th>Position</th><th>Education Status</th><th>Payment Status</th><th>Actions</th>';
                echo '</tr></thead>';
                echo '<tbody>';

                // Loop through the results and display them
                while ($row = $result->fetch_assoc()) {
                    $regDate = new DateTime($row['reg_date']);
                    $currentDate = new DateTime();
                    $interval = $currentDate->diff($regDate);
                    $monthsPassed = ($interval->y * 12) + $interval->m;

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['f_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['l_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['sex']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['age']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['phone_no']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['position']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['edu_status']) . '</td>';
                    echo '<td>' . ($row['payment_status'] == 0 ? 'Unpaid' : 'Paid') . '</td>';
                    echo '<td>';

                    if ($row['payment_status'] == 0) {
                        if ($monthsPassed >= 1) {
                            $showAlert = true;
                        }
                        $id = htmlspecialchars($row['id']);
                        $email = htmlspecialchars($row['email']);
                        $salary = htmlspecialchars($row['salary']);
                        $accountNo = htmlspecialchars($row['Account_no']);

                        echo '<form action="payProcess.php" method="POST" style="display:inline-block;">';
                        echo '<input type="hidden" name="id" value="' . $id . '">';
                        echo '<input type="hidden" name="email" value="' . $email . '">';
                        echo '<input type="hidden" name="salary" value="' . $salary . '">';
                        echo '<input type="hidden" name="account_no" value="' . $accountNo . '">';
                        echo '<button type="submit" class="btn btn-primary btn-sm" onclick="return confirm(\'Are you sure you want to process payment?\')">Pay</button>';
                        echo '</form>';
                    } else {
                        echo '<a href="#" class="btn btn-secondary btn-sm btn-disabled">Paid</a>';
                    }

                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="alert alert-warning">No employee records found.</div>';
            }

            $conn->close();
            ?>

            <!-- Check if alert needs to be shown -->
            <?php if ($showAlert): ?>
                <script>
                    window.onload = function () {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Payment Overdue',
                            text: 'Reminder: Payment has not been processed for more than a month.',
                        });
                    };
                </script>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p style="margin: 0;">&copy; 2024 Ehototmamachochi Hotel. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
