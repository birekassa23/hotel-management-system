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

    <!-- List Section -->
    <section id="listSection" class="my-5">
        <div class="container">
            <div id="room_types" class="btn-container">
                <!-- Room types will be populated here -->
            </div>
        </div>
    </section>

    <!-- Room Reservation Form -->
    <section id="RoomReservationForm" class="d-none my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <h2 class="text-center mb-4">Room Reservation Form</h2>
                    <form id="RoomForm" action="room_reserve.php">
                        <div class="mb-3">
                            <label for="guestFNameRoom" class="form-label">Guest First Name</label>
                            <input type="text" class="form-control" id="guestFNameRoom" name="guestFNameRoom" required>
                        </div>
                        <div class="mb-3">
                            <label for="guestLNameRoom" class="form-label">Guest Last Name</label>
                            <input type="text" class="form-control" id="guestLNameRoom" name="guestLNameRoom" required>
                        </div>
                        <div class="mb-3">
                            <label for="kebele_idRoom" class="form-label">Kebele ID</label>
                            <input type="file" class="form-control" id="kebele_idRoom" name="kebele_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="RoomType" class="form-label">Room Type</label>
                            <input type="text" class="form-control" id="RoomType" name="RoomType" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="RoomPrice" class="form-label">Room Price</label>
                            <input type="text" value="" class="form-control" id="RoomPrice" name="RoomPrice" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="RoomId" class="form-label">Room ID</label>
                            <select id="RoomId" class="form-select" name="RoomId" required>
                                <!-- Room IDs will be populated here -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="checkInDateRoom" class="form-label">Check-In Date</label>
                            <input type="date" class="form-control" id="checkInDateRoom" name="checkInDateRoom"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="checkOutDateRoom" class="form-label">Check-out Date</label>
                            <input type="date" class="form-control" id="checkOutDateRoom" name="checkOutDateRoom"
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

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved. This Website is powered by MTU Department of SE
            Group 1 Members</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Toggling Sections and Fetching Data -->
    <script>
        // Function to toggle between the reservation section and default content
        function toggleReservationSection() {
            const reservationSection = document.getElementById("RoomReservationForm");

            if (reservationSection.classList.contains("d-none")) {
                reservationSection.classList.remove("d-none");

            } else {
                reservationSection.classList.add("d-none");
                document.getElementById('listSection').classList.remove('d-none');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Fetch and display room types when the page loads
            fetch('fetchRooms.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const roomTypesContainer = document.getElementById('room_types');
                    roomTypesContainer.innerHTML = ''; // Clear previous content
                    data.forEach(room => {
                        const button = document.createElement('button');
                        button.className = 'btn btn-primary';
                        button.innerHTML = `<b>${room.r_type}</b> - price: ${room.r_price} ETB, ${room.count} available rooms`;
                        button.onclick = () => {
                            document.getElementById('RoomType').value = room.r_type;
                            document.getElementById('RoomPrice').value = room.r_price;
                            fetchRoomIds(room.r_type); // Ensure this function is defined
                            toggleReservationSection(); // Ensure this function is defined
                        };
                        roomTypesContainer.appendChild(button);
                    });
                })
                .catch(error => console.error('Error fetching room types:', error));
        });


        // Function to fetch room IDs based on selected room type
        function fetchRoomIds(roomType) {
            fetch(`fetchRoomIds.php?roomType=${roomType}`)
                .then(response => response.json())
                .then(data => {
                    const roomIdSelect = document.getElementById('RoomId');
                    roomIdSelect.innerHTML = '';
                    data.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.r_id;
                        option.textContent = room.r_id;
                        roomIdSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching room IDs:', error));
        }
    </script>

</body>

</html>