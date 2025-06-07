<?php
// Include database connection
include '../assets/conn.php';

// Initialize arrays and total counters
$Reports = [];
$hall_report = [];
$Total_no_of_reserved_rooms = 0;
$Total_no_of_reserved_halls = 0;

// Get the current date
$current_date = date('Y-m-d');

// Fetch inventory data for rooms
$sql = "SELECT * FROM `rooms_reports` WHERE reserved_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $current_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Reports[] = $row;
    }
}

// Calculate total number of reserved rooms
foreach ($Reports as $roomReport) {
    $Total_no_of_reserved_rooms += $roomReport['no_of_reserved_room'];
}

// Fetch inventory data for halls
$hall_sql = "SELECT * FROM `halls_reports`";
$stmt = $conn->prepare($hall_sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hall_report[] = $row;
    }
}

// Calculate total number of reserved halls
foreach ($hall_report as $hall_reports) {
    $Total_no_of_reserved_halls += $hall_reports['no_of_reserved_hall']; // Assumes column name is `no_of_reserved_room`
}

// Close the connection
$conn->close();
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
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Times New Roman', Times, serif;
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

        .d-none {
            display: none;
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
                    <ul class="navbar-nav mb-2 mb-lg-0 bg-dark">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php" style="color: white !important;">
                                <i class="bi bi-house-door me-2"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="makeReservation.php" style="color: white;">
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
    <main class="flex-grow-1 d-block" id="default" style="margin-top:10px; ">
        <div class="my-4">
            <div class="d-flex flex-column flex-md-row w-75 mx-auto justify-content-between gap-2">
                <a id="roomReservationBtn" class="btn btn-primary w-75 py-3" href="rooms.php">Room Reservation</a>
                <a id="meetingHallsBtn" class="btn btn-secondary w-75 py-3" href="halls.php">Meeting Halls</a>
                <div class="dropdown w-75">
                    <button class="btn btn-info dropdown-toggle w-100 py-3" type="button" id="manageReservationDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">Manage Reservation</button>
                    <ul class="dropdown-menu w-100" aria-labelledby="manageReservationDropdown">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ViewReservationModal">View
                                Reservation</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#UpdateReservationModal">Update Reservation</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#CancelReservationModal">Cancel Reservation</a></li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="container-fluid container-center mt-10px " style="margin-top:10px;">
            <div class="table-center">
                <div class="row justify-content-center align-items-center" style="width: 80%; margin: auto;">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header header-blue">Reserved Rooms Information</div>
                            <div class="card-body text-center">
                                <h3>Today's Reserved Rooms</h3>
                                <h1 class="text-center" style="font-size: 44px;" id="reservedRooms">
                                    <span class="bg-black text-danger p-0" style="padding-left: 0px; padding-right: 0px;">0</span>
                                </h1>

                                <script>
                                    // Set the final number to animate towards
                                    const target = <?php echo $Total_no_of_reserved_rooms; ?>;
                                    const element = document.getElementById("reservedRooms");
                                    let current = 0;

                                    // Function to animate the counter
                                    function animateCounter() {
                                        if (current < target) {
                                            current++;
                                            element.innerHTML = current; // Update the content of the h1 element
                                        } else {
                                            clearInterval(counterInterval); // Stop the counter when it reaches the target
                                        }
                                    }

                                    // Set the interval for updating the count (100 ms for smooth animation)
                                    const counterInterval = setInterval(animateCounter, 100); 
                                </script>

                                <!-- Button to toggle More Details -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse"
                                    data-bs-target="#demo">
                                    More Details
                                </button>

                                <div id="demo" class="collapse">
                                    <p style="padding-top:20px; color:blue;">This is details of reserved rooms</p>

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Room Type</th>
                                                <th>Number of Reserved Rooms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($Reports as $roomReport): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($roomReport['room_type']) ?></td>
                                                    <td><?= htmlspecialchars($roomReport['no_of_reserved_room']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header header-blue">Reserved Halls Information</div>
                            <div class="card-body text-center">
                                <h3>Today's Reserved Halls</h3>
                                <h1><?php echo $Total_no_of_reserved_halls; ?> </h1>
                                <!-- Button to toggle More Details -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse"
                                    data-bs-target="#hall_demo">
                                    More Details
                                </button>

                                <div id="hall_demo " class="collapse">
                                    <p style="padding-top:20px; color:blue;">This is details of reserved rooms</p>

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Room Type</th>
                                                <th>Number of Reserved Rooms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($Reports as $roomReport): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($roomReport['room_type']) ?></td>
                                                    <td><?= htmlspecialchars($roomReport['no_of_reserved_room']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Reservation Section -->
    <section id="reservation" class="my-5 d-none">
        <div class="collapse mt-3" id="reservationContent" aria-labelledby="manageReservationDropdown">
            <div class="d-flex w-50 mx-auto justify-content-between gap-2">
                <a id="roomReservationBtn" class="btn btn-primary w-100" href="rooms.php">Room Reservation</a>
                <a id="meetingHallsBtn" class="btn btn-secondary w-100" href="halls.php">Meeting Halls</a>
                <div class="dropdown w-100">
                    <button class="btn btn-info dropdown-toggle w-100" type="button" id="manageReservationDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">Manage Reservation</button>
                    <ul class="dropdown-menu w-100" aria-labelledby="manageReservationDropdown">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ViewReservationModal">View
                                Reservation</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#UpdateReservationModal">Update Reservation</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#CancelReservationModal">Cancel Reservation</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript for Reservation Section -->
    <script>
        // Toggle reservation section visibility
        function toggleReservationSection() {
            const reservationSection = document.getElementById("reservation");
            const defaultContent = document.getElementById("default");

            reservationSection.classList.toggle("d-none");
            defaultContent.classList.toggle("d-none");
        }

        // Show specific section by ID and hide others
        function showSection(id) {
            document.querySelectorAll("section, #default").forEach(section => section.classList.add("d-none"));
            document.getElementById(id).classList.remove("d-none");
        }

        // Display section based on URL parameter
        document.addEventListener("DOMContentLoaded", function () {
            const section = new URLSearchParams(window.location.search).get("section");
            if (section === "reservation") {
                showSection("reservation");
            }
        });
    </script>



    <!-- View Reservation Modal -->
    <div class="modal fade" id="ViewReservationModal" tabindex="-1" aria-labelledby="ViewReservationLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ViewReservationLabel">View Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <label for="email">Email</label>
                        <input type="email" class="form-control mb-2">
                        <label for="password">Password</label>
                        <input type="password" class="form-control">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success mt-4" style="width: 45%;">cancel
                                Reservation</button>
                            <button type="reset" class="btn btn-danger mt-4" style="width: 45%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Reservation Modal -->
    <div class="modal fade" id="UpdateReservationModal" tabindex="-1" aria-labelledby="UpdateReservationLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UpdateReservationLabel">Update Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <label for="email">Email</label>
                        <input type="email" class="form-control mb-2">
                        <label for="password">Password</label>
                        <input type="password" class="form-control">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success mt-4" style="width: 45%;">cancel
                                Reservation</button>
                            <button type="reset" class="btn btn-danger mt-4" style="width: 45%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Reservation Modal -->
    <div class="modal fade" id="CancelReservationModal" tabindex="-1" aria-labelledby="CancelReservationLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CancelReservationLabel">Cancel Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <label for="email">Email</label>
                        <input type="email" class="form-control mb-2">
                        <label for="password">Password</label>
                        <input type="password" class="form-control">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success mt-4" style="width: 45%;">cancel
                                Reservation</button>
                            <button type="reset" class="btn btn-danger mt-4" style="width: 45%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved. This Website is powered by MTU Department of SE
            Group 1 Members</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>