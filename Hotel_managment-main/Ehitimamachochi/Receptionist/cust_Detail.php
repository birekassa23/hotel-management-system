<?php
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $type = isset($_GET['type']) ? $_GET['type'] : '';

//include database connection
include '../assets/conn.php';

    switch ($type) {
        case 'meeting_halls':
            $sql = "SELECT CONCAT(first_name, ' ', last_name) as full_name, email, phone, kebele_id, hall_id as reservation_id, hall_price as price, checkin_date, checkout_date, assigned_by FROM reserved_meeting_halls";
            break;
        case 'rooms':
            $sql = "SELECT CONCAT(first_name, ' ', last_name) as full_name, email, phone, kebele_id, room_id as reservation_id, room_price as price, checkin_date, checkout_date, assigned_by FROM reserved_rooms";
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid type']);
            exit;
    }

    $result = $conn->query($sql);

    if (!$result) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        $conn->close();
        exit;
    }

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Output data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);

    // Close the connection
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Table</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        *{
        font-family:'Times New Roman';
        }
        .navbar {
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

   <!-- Main Content -->
<main class="container mt-5">
    <h1 class="mb-4 text-center">Reservation Information</h1>

    <!-- Search Section -->
    <div class="d-flex mb-4 justify-content-between align-items-center">
        <div class="btn-group" role="group">
            <button class="btn btn-primary me-2" onclick="fetchData('meeting_halls')">Hall Reservations</button>
            <button class="btn btn-primary" onclick="fetchData('rooms')">Room Reservations</button>
        </div>

        <!-- Date Filter -->
        <div class="d-flex align-items-center">
            <input type="date" class="form-control me-2" placeholder="Enter Date" />
            <button class="btn btn-success">Search</button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Kebele ID</th>
                    <th>Reservation ID</th>
                    <th>Price</th>
                    <th>Check-In Date</th>
                    <th>Check-Out Date</th>
                    <th>Assigned By</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Table rows will be dynamically inserted here -->
            </tbody>
        </table>
    </div>
</main>



    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved. This Website is powered by MTU Department of SE
            Group 1 Members</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for fetching data and updating the table -->
    <script>
        async function fetchData(type) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Loading...</td></tr>';

            try {
                const response = await fetch(`cust_Detail.php?action=fetch&type=${type}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
                loadTable(data);
            } catch (error) {
                console.error('Error fetching data:', error);
                tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">An error occurred: ${error.message}</td></tr>`;
            }
        }

        function loadTable(data) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.full_name}</td>
                    <td>${item.email}</td>
                    <td>${item.phone}</td>
                    <td>${item.kebele_id}</td>
                    <td>${item.reservation_id}</td>
                    <td>${item.price}</td>
                    <td>${item.checkin_date}</td>
                    <td>${item.checkout_date}</td>
                    <td>${item.assigned_by}</td>
                `;
                tableBody.appendChild(row);
            });
        }
    </script>
</body>

</html>