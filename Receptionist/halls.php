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
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php" style="color: white !important;">
                                <i class="bi bi-house-door me-2"></i>Home
                            </a>
                        </li>
                        <!-- Make Reservation Navigation Item -->
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


    <!-- Loading Indicator -->
    <div id="loading">Loading...</div>


    <!-- List Section -->
    <section id="listSection" class="my-5">
        <div class="container">
            <div id="meetingHallTypes" class="btn-container">
                <!-- Meeting hall types will be populated here -->
            </div>
        </div>
    </section>

    <!-- Hall Reservation Form -->
    <section id="hallReservationForm" class="d-none my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <h2 class="text-center mb-4">Hall Reservation Form</h2>
                    <form id="hallForm" action="hall_reserve.php" method="post" enctype="multipart/form-data">
                        <!-- Form Fields -->
                        <div class="mb-3">
                            <label for="guestFNameHall" class="form-label">Guest First Name</label>
                            <input type="text" class="form-control" id="guestFNameHall" name="guestFNameHall" required>
                        </div>
                        <div class="mb-3">
                            <label for="guestLNameHall" class="form-label">Guest Last Name</label>
                            <input type="text" class="form-control" id="guestLNameHall" name="guestLNameHall" required>
                        </div>
                        <div class="mb-3">
                            <label for="kebele_id" class="form-label">Kebele ID</label>
                            <input type="file" class="form-control" id="kebele_id" name="kebele_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="guestPhoneHall" class="form-label">Guest Phone</label>
                            <input type="tel" class="form-control" id="guestPhoneHall" name="guestPhoneHall" required>
                        </div>
                        <div class="mb-3">
                            <label for="hallType" class="form-label">Hall Type</label>
                            <input type="text" class="form-control" id="hallType" name="hallType" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="hallPrice" class="form-label">Hall Price</label>
                            <input type="text" class="form-control" id="hallPrice" name="hallPrice" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="hallId" class="form-label">Hall ID</label>
                            <select id="hallId" class="form-select" name="hallId" required>
                                <!-- Hall IDs will be populated here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="checkInDateHall" class="form-label">Check-In Date</label>
                            <input type="date" class="form-control" id="checkInDateHall" name="checkInDateHall"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="checkOutDateHall" class="form-label">Check-out Date</label>
                            <input type="date" class="form-control" id="checkOutDateHall" name="checkOutDateHall"
                                required>
                        </div>
                        <div class="d-flex justify-content-center gap-2 my-3">
                            <button type="submit" class="btn btn-success" style="width:40%;">Submit</button>
                            <button type="reset" class="btn btn-danger" style="width:40%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div id="loading" style="display: none;">Loading...</div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved. This Website is powered by MTU Department of SE
            Group 1 Members</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Function to handle the reservation link click
            function handleReservationClick(event) {
                event.preventDefault(); // Prevent the default anchor behavior
                toggleReservationSection(); // Toggle sections visibility
            }

            // Function to toggle between the reservation section and default content
            function toggleReservationSection() {
                const reservationSection = document.getElementById("hallReservationForm");
                const listSection = document.getElementById("listSection");

                // Toggle visibility of sections
                reservationSection.classList.toggle("d-none");
                listSection.classList.toggle("d-none");
            }

            // Attach event listener to the reservation link
            const reservationLink = document.querySelector('.nav-link[href="#"]'); // Modify selector if needed
            if (reservationLink) {
                reservationLink.addEventListener('click', handleReservationClick);
            }

            // Function to show or hide the loading indicator
            function showLoading(show) {
                document.getElementById('loading').style.display = show ? 'block' : 'none';
            }

            // Function to fetch and display meeting hall types
            function fetchMeetingHallTypes() {
                showLoading(true);
                fetch('fetchMeetingHalls.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const meetingHallTypesContainer = document.getElementById('meetingHallTypes');
                        meetingHallTypesContainer.innerHTML = '';
                        data.forEach(hall => {
                            const button = document.createElement('button');
                            button.className = 'btn btn-primary';
                            button.innerHTML = `<b>${hall.type}</b> - Capacity: ${hall.capacity}, Status: ${hall.status}, Price: ${hall.price} ETB`;
                            button.onclick = () => {
                                fetchHallIds(hall.type);
                                document.getElementById('hallReservationForm').classList.remove('d-none');
                                document.getElementById('hallType').value = hall.type;
                                document.getElementById('hallPrice').value = hall.price;
                            };
                            meetingHallTypesContainer.appendChild(button);
                        });
                    })
                    .catch(error => console.error('Error fetching meeting hall types:', error))
                    .finally(() => showLoading(false));
            }

            // Function to fetch hall IDs based on selected hall type
            function fetchHallIds(hallType) {
                showLoading(true);
                fetch(`fetchHallIds.php?hallType=${encodeURIComponent(hallType)}`)
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Network response was not ok:', text);
                                throw new Error('Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const hallIdSelect = document.getElementById('hallId');
                        hallIdSelect.innerHTML = ''; // Clear existing options
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(hall => {
                                const option = document.createElement('option');
                                option.value = hall.id;
                                option.textContent = `${hall.id} Type: ${hall.type}`;
                                hallIdSelect.appendChild(option);
                            });
                        } else {
                            console.warn('No hall details available for this type.');
                        }
                    })
                    .catch(error => console.error('Error fetching hall details:', error))
                    .finally(() => showLoading(false));
            }

            // Fetch meeting hall types on page load
            fetchMeetingHallTypes();
        });

    </script>
</body>

</html>