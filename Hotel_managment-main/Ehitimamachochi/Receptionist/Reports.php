<?php
// Include database connection
include '../assets/conn.php';

// Initialize arrays
$Reports = [];
$hall_report = [];

// Fetch inventory data for rooms
$sql = "SELECT * FROM `rooms_reports` WHERE 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Reports[] = $row;
    }
}

// Fetch inventory data for halls
$hall_sql = "SELECT * FROM `halls_reports` WHERE 1";
$result = $conn->query($hall_sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hall_report[] = $row;
    }
}

// Close the connection
$conn->close();
?>




<?php
// Include database connection
include '../assets/conn.php';
session_start(); // Start the session

// Check if the user is logged in
// if (!isset($_SESSION['username'])) {
//     // Redirect to login page if not logged in
//     header("Location: index/index.php");
//     exit();
// }

// // Access the session variables
// $username = $_SESSION['username'];
// $position = $_SESSION['position'];
// $report_provider_name = ''; // Initialize variable

// // Prepare and execute statement to fetch first name, last name, and ID number based on the username
// $stmt = $conn->prepare("SELECT f_name, l_name, id FROM employees WHERE username = ?");
// if (!$stmt) {
//     die("Prepare failed: " . $conn->error);
// }

// $stmt->bind_param("s", $username);
// $stmt->execute();
// $stmt->bind_result($f_name, $l_name, $id);
// $stmt->fetch();

// // Check if names and ID were retrieved successfully
// if ($f_name && $l_name && $id) {
//     $report_provider_name = 'ID: '.$id.',   Name : '.$f_name.' '.$l_name; // Combine first name, last name, and ID
// } else {
//     $report_provider_name = 'Unknown Provider'; // Fallback if no name or ID is found
// }

// // Close the statement and connection
// $stmt->close();
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Page - Ehototmamachochi Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Times New Roman';
        }

        .container-custom {
            width: 80%;
            margin: 0 auto;
        }

        .btn-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10%;
        }

        .btn-container .btn {
            flex: 1 1 calc(50% - 10%);
            margin-bottom: 1rem;
        }

        .hidden {
            display: none;
        }

        .visible {
            display: block;
        }

        .navbar {
            margin-bottom: 3%;
            height: 100px;
        }

        .nav-item {
            margin: 10px;
        }

        .nav-item:hover {
            font-size: 17px;
            border-bottom: 1px blue solid;
            background-color: #333;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-xl">
                <a class="navbar-brand mx-auto" href="index.php">Receptionalist Panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                    aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbar">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php" style="color: white !important;">
                                <i class="bi bi-house-door me-2"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="makeReservation.php" style="color: white !important;">
                                <i class="bi bi-calendar-check me-2"></i>Make Reservation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="cust_Detail.php" style="color: white !important;">
                                <i class="bi bi-people me-2"></i>Customer Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="Reports.php" style="color: white !important;">
                                <i class="bi bi-bar-chart-line me-2"></i>Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Settings.php" style="color: white !important;">
                                <i class="bi bi-gear me-2"></i>Account Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Container -->
    <div id="mainContainer" class="container-custom">
        <div class="list-group">
            <!-- View Reports -->
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('ViewReports')">
                <span><i class="bi bi-file-earmark-text me-2"></i>View Reports</span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <!-- Write Reports -->
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('WriteReports')">
                <span><i class="bi bi-pencil me-2"></i>Write Reports</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- View Reports Section -->
    <section id="ViewReports" class="container-custom hidden">
        <button onclick="goBack('mainContainer')" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('view_room_report')">
                <span><i class="bi bi-archive me-2"></i>Rooms Reports</span>
                <i class="bi bi-chevron-right"></i>
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                onclick="showSection('view_hall_report')">
                <span><i class="bi bi-building me-2"></i>Halls Reports</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </section>


    <!--View Rooms Report Section -->
    <section id="view_room_report" class=" container-custom  hidden">
        <button onclick="goBack('ViewReports')" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>
<!-- Search Bar Section -->
    <div class="d-flex justify-content-between mb-4">
        <div class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Search" />
            <button class="btn btn-primary">
                <i class="bi bi-search me-2"></i>Search
            </button>
        </div>
        <div class="d-flex">
            <button class="btn btn-outline-secondary me-2">
                <i class="bi bi-chevron-left me-2"></i>Previous
            </button>
            <button class="btn btn-outline-secondary">
                Next<i class="bi bi-chevron-right me-2"></i>
            </button>
        </div>
    </div>

    <!-- Report Table Section -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Room Type</th>
                    <th>No of Reserved Rooms</th>
                    <th>Reserved Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($Reports as $roomReport): ?>
                    <tr>
                        <td><?= htmlspecialchars($roomReport['room_type']) ?></td>
                        <td><?= htmlspecialchars($roomReport['no_of_reserved_room']) ?></td>
                        <td><?= htmlspecialchars($roomReport['reserved_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- More Details Button & Collapse Section -->
    <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-info" data-bs-toggle="collapse" data-bs-target="#demo">
            <i class="bi bi-info-circle me-2"></i> More Details
        </button>
    </div>

    <div id="demo" class="collapse mt-3">
        <p>This is the detailed information of the reserved room.</p>
    </div>
    </section>



    <!-- Halls Report Section -->
    <section id="view_hall_report" class="container-custom hidden">
        <button onclick="goBack('ViewReports')" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>
        <!-- Search Bar Section -->
     <div class="d-flex justify-content-between mb-4">
        <div class="d-flex">
            <input type="text" class="form-control me-2" placeholder="Search" />
            <button class="btn btn-primary">
                <i class="bi bi-search me-2"></i>Search
            </button>
        </div>
        <div class="d-flex">
            <button class="btn btn-outline-secondary me-2">
                <i class="bi bi-chevron-left me-2"></i>Previous
            </button>
            <button class="btn btn-outline-secondary">
                Next <i class="bi bi-chevron-right me-2"></i>
            </button>
        </div>
    </div>

    <!-- Hall Report Table Section -->
    <div class="table-responsive mb-4">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Hall Type</th>
                    <th>No of Reserved Halls</th>
                    <th>Reserved Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hall_report as $hall_reports): ?>
                    <tr>
                        <td><?= htmlspecialchars($hall_reports['hall_type']) ?></td>
                        <td><?= htmlspecialchars($hall_reports['number_of_reserved_halls']) ?></td>
                        <td><?= htmlspecialchars($hall_reports['hall_reserved_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Button to Toggle More Details -->
        <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#hallDetails">
            <i class="bi bi-info-circle me-2"></i>More Details
        </button>

        <!-- Collapsible Details Section -->
        <div id="hallDetails" class="collapse mt-3">
            <p>This is halls details.</p>
        </div>
    </div>
    </section>

    <script>
        // Function to show a specific section and hide all others
        function showSection(sectionId) {
            // Hide all sections
            const allSections = document.querySelectorAll('.container-custom, .report-section');
            allSections.forEach(section => section.classList.add('hidden'));

            // Show the selected section
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.remove('hidden');
            }
        }

        // Function to go back to the previous section
        function goBack(previousSectionId) {
            showSection(previousSectionId);
        }
    </script>

    <!-- CSS to hide the sections by default -->
    <style>
        .hidden {
            display: none;
        }

        .container-custom,
        .report-section {
            margin-top: 20px;
        }
    </style>




    <!-- Write Reports Section -->
    <section id="WriteReports" class="container-custom hidden">
        <button onclick="goBack('mainContainer')" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>
        <form action="submit_report.php" method="post">
            <div class="d-flex justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <label for="report_provider" class="me-2">Report Provider:</label>
                    <input type="text" class="form-control" name="report_provider" id="report_provider"
                        value="<?php echo htmlspecialchars($report_provider_name); ?>" readonly required>
                </div>
                <div class="d-flex align-items-center">
                    <select name="report_about" id="report_about" style="padding:10px;" required>
                        <option value="">Select Report About</option>
                        <option value="">Room Report</option>
                        <option value="">Halls Report</option>
                        <option value="">Other</option>
                    </select>
                </div>
                <div class="d-flex align-items-center">
                    <select name="report_type" id="report_type" style="padding:10px;" required>
                        <option value="">Select Report Type</option>
                        <option value="">Expense</option>
                        <option value="">Income</option>
                    </select>
                </div>
                <div class="d-flex align-items-center">
                    <label for="reported_date" class="me-2">Reported Date:</label>
                    <input type="date" class="form-control" name="reported_date" id="reported_date" readonly required>
                </div>
            </div>
            <table id="reportTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>List</th>
                        <th>Measurement</th>
                        <th>Quantity</th>
                        <th>Single Price</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows will be added here -->
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" onclick="addRow()">Add Row</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved. This Website is powered by MTU Department of SE
            Group 1 Members</p>
    </footer>


    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('reported_date').value = today;
        });

        let rowCount = 0;

        function addRow() {
            rowCount++;
            const tableBody = document.querySelector('#reportTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="list[]" class="form-control" required></td>
                <td><input type="text" name="measurement[]" class="form-control" required></td>
                <td><input type="number" name="quantity[]" class="form-control" step="any" required></td>
                <td><input type="number" name="single_price[]" class="form-control" step="any" required></td>
                <td><input type="number" name="total_price[]" class="form-control" step="any" readonly></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            `;
            tableBody.appendChild(newRow);

            // Add event listener to update total price when quantity or single price changes
            newRow.querySelector('input[name="quantity[]"]').addEventListener('input', updateTotalPrice);
            newRow.querySelector('input[name="single_price[]"]').addEventListener('input', updateTotalPrice);
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#reportTable tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
            rowCount = rows.length;
        }

        function updateTotalPrice(event) {
            const row = event.target.closest('tr');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const singlePrice = parseFloat(row.querySelector('input[name="single_price[]"]').value) || 0;
            const totalPrice = quantity * singlePrice;
            row.querySelector('input[name="total_price[]"]').value = totalPrice.toFixed(2);
        }

        function validateForm() {
            const rows = document.querySelectorAll('#reportTable tbody tr');
            let isValid = true;
            let errorMessages = [];

            rows.forEach((row, index) => {
                const list = row.querySelector('input[name="list[]"]').value.trim();
                const measurement = row.querySelector('input[name="measurement[]"]').value.trim();
                const quantity = row.querySelector('input[name="quantity[]"]').value.trim();
                const singlePrice = row.querySelector('input[name="single_price[]"]').value.trim();
                const totalPrice = row.querySelector('input[name="total_price[]"]').value.trim();

                let missingColumns = [];

                if (!list) missingColumns.push('List');
                if (!measurement) missingColumns.push('Measurement');
                if (!quantity) missingColumns.push('Quantity');
                if (!singlePrice) missingColumns.push('Single Price');
                if (!totalPrice) missingColumns.push('Total Price');

                if (missingColumns.length > 0) {
                    isValid = false;
                    errorMessages.push(`Row ${index + 1}: Missing ${missingColumns.join(', ')}`);
                }
            });

            if (!isValid) {
                alert('Please fill out all required fields:\n' + errorMessages.join('\n'));
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }

        // Attach validateForm function to form's submit event
        document.querySelector('form').addEventListener('submit', function (event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        function showSection(id) {
            document.querySelectorAll('section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(id).classList.remove('hidden');
            document.getElementById('mainContainer').classList.add('hidden');
        }

        function goBack() {
            document.querySelectorAll('section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById('mainContainer').classList.remove('hidden');
        }
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>