<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Types</title>
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="room.css">
    <style>
        .navbar {
            height: 80px;
            padding: 1rem 2rem;
            background-color: #343a40;
        }

        .nav-link {
            color: #ffffff !important;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .nav-link:hover {
            border-bottom: 2px solid lightblue;
            color: lightblue !important;
        }

        .dropdown-menu {
            background-color: #343a40;
        }

        .dropdown-item {
            color: #ffffff !important;
        }

        .dropdown-item:hover {
            color: lightblue !important;
            background-color: #495057;
        }

        .navbar-nav {
            align-items: center;
        }

        .navbar-nav .nav-link {
            padding: 1rem;
            color: #ffffff;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-xl d-flex align-items-center">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#" style="color: #ffffff; margin-right: 30%;">Reserve Room</a>
            <div class="collapse navbar-collapse align-items-left" id="navbarNav">
                <ul class="navbar-nav ms-auto bg-dark">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#viewReservationModal">
                            <i class="bi bi-eye me-2"></i> View Your Reservation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#MyUpdateReservationModal">
                            <i class="bi bi-pencil me-2"></i> Update Your Reservation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#deleteReservationModal">
                            <i class="bi bi-trash me-2"></i> Cancel Your Reservation
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Main content -->
    <div class="container mt-5 pt-5 flex-fill">
        <h2 class="text-center mb-4" style="font-family: 'Times New Roman', Times, serif; color: black;">Select Your
            Choice For Reservation</h2>
        <div class="row g-3">
            <!-- Standard Room Button -->
            <div class="col-md-6 mb-4">
                <div id="standard_bed_btn" class="text-center p-3"
                    style="background-image: url('images/room/level4/level4.1.jpg'); background-size: cover; background-position: center; color: black; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); cursor: pointer; transition: background-color 0.3s; position: relative; height: 300px;">
                    <!-- Room Details on the Left Side -->
                    <div
                        style="width: 35%; background-color: rgba(255, 255, 255, 0.8); border-radius: 8px; padding: 10px; text-align: left; position: absolute; left: 20px; top: 10px;  height: auto; max-height: 60%; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <p style="font-size: 18px; font-weight: bold; margin: 0;">
                                <a href="#" style="color: black; text-decoration: none;">
                                    <i class="fas fa-bed"></i> Standard Rooms
                                </a>
                            </p>
                            <div style="padding-left: 10px;">
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Free Rooms : <span style="font-weight: bold;" id="num_std">0</span>
                                </p>
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Price : <span id="std_price" style="font-weight: bold;"></span> ETB
                                </p>
                            </div>
                            <!-- Room Packages -->
                            <div style="padding: 0; margin: 0; margin-top: 10px;">
                                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">Room
                                    Packages</p>
                                <ul
                                    style="list-style-type: none; padding-left: 0; margin-left: 0; margin-bottom: 0; font-size: 12px; line-height: 1.5;">
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-wifi"></i> Free Wi-Fi
                                    </li>
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-shower"></i> Common shawor
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Reserve Button -->
                    <button class="btn btn-primary"
                        style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); width: 60%;"
                        onclick="showModal('Standard')">
                        <i class="fas fa-calendar-check"></i> Reserve Now
                    </button>
                </div>
            </div>

            <!-- Deluxe Room Button -->
            <div class="col-md-6 mb-4">
                <div id="deluxe_bed_btn" class="text-center p-3"
                    style="background-image: url('images/room/level3/level3.1.jpg'); background-size: cover; background-position: center; color: black; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); cursor: pointer; transition: background-color 0.3s; position: relative; height: 300px;">
                    <!-- Room Details on the Left Side -->
                    <div
                        style="width: 35%; background-color: rgba(255, 255, 255, 0.8); border-radius: 8px; padding: 10px; text-align: left; position: absolute; left: 20px; top: 10px;  height: auto; max-height: 60%; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <p style="font-size: 18px; font-weight: bold; margin: 0;">
                                <a href="#" style="color: black; text-decoration: none;">
                                    <i class="fas fa-bed"></i> Deluxe Rooms
                                </a>
                            </p>
                            <div style="padding-left: 10px;">
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Free Rooms : <span style="font-weight: bold;" id="num_del">0</span>
                                </p>
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Price: <span id="del_price" style="font-weight: bold;"></span> ETB
                                </p>
                            </div>
                            <!-- Room Packages -->
                            <div style="padding: 0; margin: 0; margin-top: 10px;">
                                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">Room
                                    Packages</p>
                                <ul
                                    style="list-style-type: none; padding-left: 0; margin-left: 0; margin-bottom: 0; font-size: 12px; line-height: 1.5;">
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-wifi"></i> Free Wi-Fi
                                    </li>
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-shower"></i> normal shawor
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Reserve Button -->
                    <button class="btn btn-primary"
                        style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); width: 60%;"
                        onclick="showModal('Deluxe')">
                        <i class="fas fa-calendar-check"></i> Reserve Now
                    </button>
                </div>
            </div>

            <!-- Suite Room Button -->
            <div class="col-md-6 mb-4">
                <div id="suite_bed_btn" class="text-center p-3"
                    style="background-image: url('images/room/level2/level2.0.jpg'); background-size: cover; background-position: center; color: black; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); cursor: pointer; transition: transform 0.3s, background-color 0.3s; position: relative; height: 300px;">
                    <!-- Room Details on the Left Side -->
                    <div
                        style="width: 35%; background-color: rgba(255, 255, 255, 0.8); border-radius: 8px; padding: 10px; text-align: left; position: absolute; left: 20px; top: 10px;  height: auto; max-height: 60%; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <p style="font-size: 18px; font-weight: bold; margin: 0;">
                                <a href="#" style="color: black; text-decoration: none;">
                                    <i class="fas fa-bed"></i> Suite Rooms
                                </a>
                            </p>
                            <div style="padding-left: 10px;">
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Free Rooms: <span style="font-weight: bold;" id="num_sui"
                                        style="font-weight: bold;">0</span>
                                </p>
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Price: <span id="sui_price" style="font-weight: bold;"></span> ETB
                                </p>
                            </div>
                            <!-- Room Packages -->
                            <div style="padding: 0; margin: 0; margin-top: 10px;">
                                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">Room
                                    Packages</p>
                                <ul
                                    style="list-style-type: none; padding-left: 0; margin-left: 0; margin-bottom: 0; font-size: 12px; line-height: 1.5;">
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-wifi"></i> Free Wi-Fi
                                    </li>
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-shower"></i> Hot & Cold Shower
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Reserve Button -->
                    <button class="btn btn-primary"
                        style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); width: 60%;"
                        onclick="showModal('Suite')">
                        <i class="fas fa-calendar-check"></i> Reserve Now
                    </button>
                </div>
            </div>

            <!-- Luxury Room Button -->
            <div class="col-md-6 mb-4">
                <div id="luxury_bed_btn" class="text-center p-3"
                    style="background-image: url('images/room/leve1/level1.1.jpg'); background-size: cover; background-position: center; color: black; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); cursor: pointer; transition: background-color 0.3s; position: relative; height: 300px;">
                    <!-- Room Details on the Left Side -->
                    <div
                        style="width: 35%; background-color: rgba(255, 255, 255, 0.8); border-radius: 8px; padding: 10px; text-align: left; position: absolute; left: 20px; top: 10px; height: auto; max-height: 60%; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <p style="font-size: 18px; font-weight: bold; margin: 0;">
                                <a href="#" style="color: black; text-decoration: none;">
                                    <i class="fas fa-bed"></i> Luxury Rooms
                                </a>
                            </p>
                            <div style="padding-left: 10px;">
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Free Rooms: <span style="font-weight: bold;" id="num_lux">0</span>
                                </p>
                                <p style="font-size: 14px; margin-bottom: 5px;">
                                    Price: <span id="lux_price" style="font-weight: bold;"></span> ETB
                                </p>
                            </div>
                            <!-- Room Packages -->
                            <div style="padding: 0; margin: 0; margin-top: 10px;">
                                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">Room
                                    Packages</p>
                                <ul
                                    style="list-style-type: none; padding-left: 0; margin-left: 0; margin-bottom: 0; font-size: 12px; line-height: 1.5;">
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-wifi"></i> Free Wi-Fi
                                    </li>
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-shower"></i> Hot & Cold Shower
                                    </li>
                                    <li style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-tv"></i> TV
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Reserve Button -->
                    <button class="btn btn-primary"
                        style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); width: 60%;"
                        onclick="showModal('Luxury')">
                        <i class="fas fa-calendar-check"></i> Reserve Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fetch room counts and prices
            fetch('fetch_room_counts.php')
                .then(response => response.json())
                .then(data => {
                    // Update the count of available rooms
                    document.getElementById('num_std').textContent = data.standard ? data.standard.count : '0';
                    document.getElementById('num_del').textContent = data.deluxe ? data.deluxe.count : '0';
                    document.getElementById('num_sui').textContent = data.suite ? data.suite.count : '0';
                    document.getElementById('num_lux').textContent = data.luxury ? data.luxury.count : '0';

                    // Update the prices of the rooms
                    document.getElementById('std_price').textContent = data.standard ? data.standard.price.toFixed(2) : '0.00';
                    document.getElementById('del_price').textContent = data.deluxe ? data.deluxe.price.toFixed(2) : '0.00';
                    document.getElementById('sui_price').textContent = data.suite ? data.suite.price.toFixed(2) : '0.00';
                    document.getElementById('lux_price').textContent = data.luxury ? data.luxury.price.toFixed(2) : '0.00';
                })
                .catch(error => console.error('Error:', error));
        });

        function showModal(roomType) {
            document.getElementById('room_type').value = roomType;
            document.getElementById('room_payment').style.display = 'block';
        }

        function hideModal() {
            document.getElementById('room_payment').style.display = 'none';
        }

    </script>


    <!-- Room Payment Modal -->
    <div id="room_payment" class="modal" aria-labelledby="modalTitle">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="position: relative; padding-bottom: 20px;">
                    <span id="closeButton" class="close" onclick="hideModal()"
                        style="position: absolute; top: 20px; right: 20px;">
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </span>
                    <h3 style="text-align: center;">Insert Valid Information!</h3>
                    <hr>
                </div>

                <div class="modal-body">
                    <form action="room_payment.php" method="post" id="paymentForm">
                        <p id="invalidForm"
                            style="padding :0; margin:0; font-size :24px;color:red; text-align: center; display:none;">
                        </p>
                        <fieldset
                            style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                            <legend style="font-weight: bold; font-size: 1.2em;">Your Information</legend>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" onchange="firstNameValidation()" name="first_name"
                                        class="form-control" id="first_name" placeholder="First name" required>
                                    <small id="firstNameError" style="display: none; color: red;"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" onchange="lastNameValidation()" name="last_name"
                                        class="form-control" id="last_name" placeholder="Last name" required>
                                    <small id="lastNameError" style="display: none; color: red;"></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email *</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Example@example.com" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone">Phone *</label>
                                    <input type="tel" onchange="phoneNumberValidation()" name="phone"
                                        class="form-control" id="phone" required>
                                    <small id="phoneError" style="display: none; color: red;"></small>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Room Information -->
                        <fieldset style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                            <legend style="font-weight: bold; font-size: 1.2em;">Room Information</legend>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="room_type">Room Type *</label>
                                    <input type="text" class="form-control" id="room_type" name="room_type" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="num_guests">Number of Guests *</label>
                                    <input type="number" class="form-control" id="num_guests" name="num_guests"
                                        onchange="validateNoOfGuests()" required>
                                    <small id="errorNoOfGuest"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="room_id">Room Id *</label>
                                    <select name="room_id" id="room_id" class="form-control" required>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="room_price">Rooms Price *</label>
                                    <input type="number" class="form-control" id="room_price" name="room_price" readonly
                                        required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="checkin_date">Check-In Date *</label>
                                    <input onchange="validateDates()" type="date" class="form-control" id="checkin_date"
                                        name="checkin_date" required>
                                    <small id="errorCheckinDate"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="checkout_date">Check-Out Date *</label>
                                    <input onchange="validateDates()" type="date" class="form-control"
                                        id="checkout_date" name="checkout_date" required>
                                    <small id="errorCheckOutDate"></small>
                                </div>
                            </div>
                        </fieldset>
                        <!-- Terms and policy fild -->
                        <fieldset
                            style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 20px; margin-top: 20px;">
                            <legend style="font-weight: bold; font-size: 1.2em;">
                                <input type="checkbox" id="termsCheckbox" required>
                                I accept the Terms and Policy
                            </legend>
                            <ul style="list-style-type: disc; padding-left: 0; padding-left:10%;">
                                <li>Reservations can be canceled before 02:00 PM local time.</li>
                                <li>Reservations can be canceled within 1 hour of booking.</li>
                                <li>You will receive half of 75% of your payment, either in person or by contacting
                                    us
                                    through the phone.</li>
                            </ul>
                        </fieldset>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success mt-4" style="width: 45%;">Reserve
                                Room</button>
                            <button type="reset" class="btn btn-danger mt-4" style="width: 45%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Reservation Modal -->
    <div class="modal fade" id="viewReservationModal" tabindex="-1" aria-labelledby="viewReservationModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewReservationModalLabel">View Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="View_room_reservation.php" method="POST">
                        <div class="mb-3">
                            <label for="View_email" class="form-label">Email:</label>
                            <input type="email" id="View_email" name="View_email" class="form-control"
                                placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="View_password" class="form-label">Password:</label>
                            <input type="password" id="View_password" name="View_password" class="form-control"
                                required>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 5%;">
                            <button style="width: 40%;" type="submit" class="btn btn-primary">View
                                Reservation</button>
                            <button style="width: 40%;" type="reset" class="btn btn-danger">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Reservation Modal -->
    <div class="modal fade" id="MyUpdateReservationModal" tabindex="-1" aria-labelledby="MyUpdateReservationModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="MyUpdateReservationModalLabel">Update Reservation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="invalidUpdateForm"
                        style="padding :0; margin:0; font-size :24px;color:red; text-align: center; display:none;">
                    </p>
                    <form id="updateReservationForm" action="update_room_reservation.php" method="POST">
                        <div class="mb-3">
                            <label for="my_update_email" class="form-label">Enter Email:</label>
                            <input type="email" id="my_update_email" name="my_update_email" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="my_update_password" class="form-label">Enter Password:</label>
                            <input type="password" id="my_update_password" name="my_update_password"
                                class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="my_update_room_type" class="form-label">Select New Room Type:</label>
                            <select name="my_update_room_type" id="my_update_room_type" class="form-control" required>
                                <option value="standard" selected>Standard</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="suite">Suite</option>
                                <option value="luxury">Luxury</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="my_update_room_id" class="form-label">Select New Room ID:</label>
                            <select name="my_update_room_id" id="my_update_room_id" class="form-control" required>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="my_update_room_price" class="form-label">Payment:</label>
                            <input type="number" id="my_update_room_price" name="my_update_room_price"
                                class="form-control" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="my_update_checkin_date">New Check-In Date:</label>
                            <input type="date" class="form-control" id="my_update_checkin_date"
                                onchange="validateDatesUpdateForm()" name="my_update_checkin_date" required>
                            <small id="errorUpdateCheckinDate"></small>
                        </div>
                        <div class="mb-3">
                            <label for="my_update_checkout_date">New Check-Out Date:</label>
                            <input type="date" class="form-control" id="my_update_checkout_date"
                                onchange="validateDatesUpdateForm()" name="my_update_checkout_date" required>
                            <small id="errorUpdateCheckOutDate"></small>
                        </div>
                        <!-- Terms and policy fild -->
                        <fieldset
                            style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 20px; margin-top: 20px;">
                            <legend style="font-weight: bold; font-size: 1.2em;">
                                <input type="checkbox" id="termsCheckbox" required>
                                I accept the Terms and Policy
                            </legend>
                            <ul style="list-style-type: disc; padding-left: 0; padding-left:10%;">
                                <li>Reservations can be Updated within 4 hour of booking before 02:00 AM LT.</li>
                                <li>Reservations Updated With Out Provided Date It Will Need Extra Money.</li>
                            </ul>
                        </fieldset>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-success mt-4" style="width: 45%;">Update
                                Reservation</button>
                            <button type="reset" class="btn btn-danger mt-4" style="width: 45%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>


    </script>
    <script>
        document.getElementById("paymentForm").onsubmit = validateForm;
        document.getElementById("updateReservationForm").onsubmit = validateUpdateForm;

        function validateForm(event) {
            let isValid = true;

            // Call each validation function and update isValid
            isValid &= firstNameValidation();
            isValid &= lastNameValidation();
            isValid &= phoneNumberValidation();
            isValid &= validateNoOfGuests();
            isValid &= validateDates();

            // If any validation fails, prevent form submission  
            if (!isValid) {
                event.preventDefault();
                const invalidFormMessage = document.getElementById("invalidForm");
                invalidFormMessage.innerHTML = "Please insert valid data!";
                invalidFormMessage.style.display = "block";

                // Scroll to the error message
                invalidFormMessage.scrollIntoView({ behavior: "smooth" });
            }
        }

        function validateUpdateForm(event) {
            let isValid = true;
            // Call each validation function and update isValid
            isValid &= validateDatesUpdateForm();
            // If any validation fails, prevent form submission  
            if (!isValid) {
                event.preventDefault();
                const invalidFormMessage = document.getElementById("invalidUpdateForm");
                invalidFormMessage.innerHTML = "Please insert valid data!";
                invalidFormMessage.style.display = "block";
                // Scroll to the error message
                invalidFormMessage.scrollIntoView({ behavior: "smooth" });
            }
        }

        // Validation Functions
        function firstNameValidation() {
            const firstNameInput = document.getElementById("first_name").value.trim();
            const firstNameError = document.getElementById("firstNameError");
            const nameRegex = /^[A-Za-z]+$/;
            if (!firstNameInput || !nameRegex.test(firstNameInput)) {
                firstNameError.style.display = 'block';
                firstNameError.style.color = 'red';
                firstNameError.textContent = "First name should only contain alphabets.";
                return false;
            }
            firstNameError.style.display = 'none';
            return true;
        }

        function lastNameValidation() {
            const lastNameInput = document.getElementById("last_name").value.trim();
            const lastNameError = document.getElementById("lastNameError");
            const nameRegex = /^[A-Za-z]+$/;
            if (!lastNameInput || !nameRegex.test(lastNameInput)) {
                lastNameError.style.display = 'block';
                lastNameError.style.color = 'red';
                lastNameError.textContent = "Last name should only contain alphabets.";
                return false;
            }
            lastNameError.style.display = 'none';
            return true;
        }

        function phoneNumberValidation() {
            const phoneInput = document.getElementById("phone").value.trim();
            const phoneError = document.getElementById("phoneError");
            const phoneRegex = /^(?:\+251|251|0?9)\d{8}$/;
            if (!phoneInput || !phoneRegex.test(phoneInput)) {
                phoneError.style.display = 'block';
                phoneError.style.color = 'red';
                phoneError.textContent = "Phone number must follow the Ethiopian format, e.g., +251911234567 or 0911234567.";
                return false;
            }
            phoneError.style.display = 'none';
            return true;
        }

        function validateNoOfGuests() {
            const numGuestsInput = document.getElementById("num_guests").value;
            const numGuests = parseInt(numGuestsInput, 10);
            const errorNoOfGuest = document.getElementById("errorNoOfGuest");
            if (numGuests <= 0 || numGuests >= 3) {
                errorNoOfGuest.textContent = "Number of guests must be greater than 0 and less than 3.";
                errorNoOfGuest.style.color = "red";
                return false;
            }
            errorNoOfGuest.textContent = "";
            return true;
        }

        function validateDates() {
            const checkinDateInput = document.getElementById("checkin_date").value;
            const checkoutDateInput = document.getElementById("checkout_date").value;
            const errorCheckinDate = document.getElementById("errorCheckinDate");
            const errorCheckOutDate = document.getElementById("errorCheckOutDate");
            const currentDate = new Date();
            const checkinDate = new Date(checkinDateInput);
            const checkoutDate = new Date(checkoutDateInput);
            let valid = true;
            if (checkinDate < currentDate.setHours(0, 0, 0, 0)) {
                errorCheckinDate.textContent = "Check-in date must be today or a future date.";
                errorCheckinDate.style.color = "red";
                valid = false;
            } else {
                errorCheckinDate.textContent = "";
            }
            if (checkinDateInput && checkoutDateInput && checkoutDate <= checkinDate) {
                errorCheckOutDate.textContent = "Check-out date must be after the check-in date.";
                errorCheckOutDate.style.color = "red";
                valid = false;
            } else {
                errorCheckOutDate.textContent = "";
            }
            return valid;
        }
        function validateDatesUpdateForm() {
            const checkinDateInput = document.getElementById("my_update_checkin_date").value;
            const checkoutDateInput = document.getElementById("my_update_checkout_date").value;
            const errorCheckinDate = document.getElementById("errorUpdateCheckinDate");
            const errorCheckOutDate = document.getElementById("errorUpdateCheckOutDate");
            const currentDate = new Date();
            const checkinDate = new Date(checkinDateInput);
            const checkoutDate = new Date(checkoutDateInput);
            let valid = true;
            if (checkinDate < currentDate.setHours(0, 0, 0, 0)) {
                errorCheckinDate.textContent = "Check-in date must be today or a future date.";
                errorCheckinDate.style.color = "red";
                valid = false;
            } else {
                errorCheckinDate.textContent = "";
            }
            if (checkinDateInput && checkoutDateInput && checkoutDate <= checkinDate) {
                errorCheckOutDate.textContent = "Check-out date must be after the check-in date.";
                errorCheckOutDate.style.color = "red";
                valid = false;
            } else {
                errorCheckOutDate.textContent = "";
            }
            return valid;
        }
    </script>

    <!-- Cancel Reservation Modal -->
    <div class="modal fade" id="deleteReservationModal" tabindex="-1" aria-labelledby="deleteReservationModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteReservationModalLabel">Cancel Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="Cancel_room_reservation.php" method="POST">
                        <div class="mb-3">
                            <label for="Delete_email" class="form-label">Email:</label>
                            <input type="email" id="Delete_email" name="Delete_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="Delete_password" class="form-label">Password:</label>
                            <input type="password" id="Delete_password" name="Delete_password" class="form-control"
                                required>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 5%;">
                            <button type="submit" class="btn btn-success mt-4" style="width: 40%;">Cancel
                                Reservation</button>
                            <button type="reset" class="btn btn-danger mt-4" style="width: 40%;">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="mt-5 bg-dark text-light text-center py-3 " style="width: 100%; color: white;">
        <p>&copy; 2024 Ehototmamachochi Hotel. All rights reserved.</p>
    </footer>
</body>
<!-- Bootstrap 5.1.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


<script>
    let baseRoomPrice = 0; // Store the base room price separately

    document.addEventListener('DOMContentLoaded', () => {
        // Check for initial validations and fetch data
        validateInitialInputs();

        // Set up event listeners for changes in inputs
        document.getElementById('room_id').addEventListener('change', (event) => {
            const roomId = event.target.value;
            fetchRoomPrice(roomId);
        });

        document.getElementById('checkin_date').addEventListener('change', calculateTotalPrice);
        document.getElementById('checkout_date').addEventListener('change', calculateTotalPrice);
    });

    function showModal(roomType) {
        document.getElementById('room_type').value = roomType;
        document.getElementById('room_payment').style.display = 'block';
        fetchRoomIds(roomType); // Fetch room IDs when modal is shown

        // Validate initial inputs when the modal is shown
        validateInitialInputs();
    }

    function hideModal() {
        document.getElementById('room_payment').style.display = 'none';
    }

    function validateInitialInputs() {
        const roomType = document.getElementById('room_type').value;
        if (roomType) {
            fetchRoomIds(roomType);
        }

        validateDates();
    }

    function fetchRoomIds(roomType) {
        if (!roomType) return;
        fetch('get_room_ids.php?room_type=' + encodeURIComponent(roomType))
            .then(retrievedRoomIds => retrievedRoomIds.json())
            .then(data => {
                const roomIdSelect = document.getElementById('room_id');
                if (data.length === 0) {
                    roomIdSelect.innerHTML = '<option value="">No rooms available</option>';
                } else {
                    let options = '';
                    data.forEach(room => {
                        options += `<option value="${room.r_id}">Room ID: ${room.r_id}</option>`;
                    });
                    roomIdSelect.innerHTML = options;
                    // Fetch the price for the first room when available
                    if (data.length > 0) {
                        fetchRoomPrice(data[0].r_id);
                    }
                }
            })
            .catch(error => console.error('Error fetching room IDs:', error));
    }

    function fetchRoomPrice(roomId) {
        if (!roomId) return; // Checks if it's not empty
        fetch('get_room_price.php?room_id=' + encodeURIComponent(roomId))
            .then(responsedRoomPrice => responsedRoomPrice.json())
            .then(data => {
                const priceInput = document.getElementById('room_price');
                if (data.length > 0) {
                    baseRoomPrice = parseFloat(data[0].r_price); // Store the base price
                    priceInput.value = baseRoomPrice.toFixed(2); // Display the base price
                    calculateTotalPrice(); // Recalculate total price when room price is fetched
                } else {
                    priceInput.value = '';
                    baseRoomPrice = 0; // Reset base price if no data
                }
            })
            .catch(error => console.error('Error fetching room price:', error));
    }

    // This Function Helps Us Calculate Room Price According to the check-in and check-out dates
    function calculateTotalPrice() {
        const checkinDate = document.getElementById('checkin_date').value;
        const checkoutDate = document.getElementById('checkout_date').value;
        // Ensure both dates and a valid room price are present
        if (checkinDate && checkoutDate && !isNaN(baseRoomPrice) && baseRoomPrice > 0) {
            const checkin = new Date(checkinDate);
            const checkout = new Date(checkoutDate);
            // Calculate the difference in days
            const daysDiff = (checkout - checkin) / (1000 * 60 * 60 * 24);
            // Ensure the difference is positive
            if (daysDiff > 0) {
                const totalPrice = daysDiff * baseRoomPrice; // Always multiply by the base price
                document.getElementById('room_price').value = totalPrice.toFixed(2);
            } else {
                document.getElementById('room_price').value = ''; // Clear price if invalid dates
            }
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        let baseRoomPrice = 0;

        // Function to fetch and populate room IDs based on the selected room type
        function updatefetchRoomIds(roomType) {
            if (!roomType) return;

            fetch(`get_room_ids.php?room_type=${encodeURIComponent(roomType)}`)
                .then(response => response.json())
                .then(data => {
                    const roomIdSelect = document.getElementById('my_update_room_id');
                    roomIdSelect.innerHTML = data.length === 0
                        ? '<option value="">No rooms available</option>'
                        : data.map(room => `<option value="${room.r_id}">Room ID: ${room.r_id}</option>`).join('');

                    if (data.length > 0) {
                        roomIdSelect.selectedIndex = 0; // Select the first room ID
                        updatefetchRoomPrice(roomIdSelect.value); // Fetch price for the first room ID
                    } else {
                        document.getElementById('my_update_room_price').value = ''; // Clear price if no rooms
                    }
                })
                .catch(error => console.error('Error fetching room IDs:', error));
        }

        // Function to fetch and update room price based on the selected room ID
        function updatefetchRoomPrice(roomId) {
            if (!roomId) return;
            fetch(`get_room_price.php?room_id=${encodeURIComponent(roomId)}`)
                .then(response => response.json())
                .then(data => {
                    const priceInput = document.getElementById('my_update_room_price');
                    if (data && data.length > 0 && data[0].r_price) {
                        baseRoomPrice = parseFloat(data[0].r_price); // Store the base price
                        priceInput.value = baseRoomPrice.toFixed(2); // Display the base price
                        calculateTotalPrice(); // Recalculate total price
                    } else {
                        priceInput.value = '';
                        baseRoomPrice = 0; // Reset base price if no data
                    }
                })
                .catch(error => console.error('Error fetching room price:', error));
        }

        // Function to calculate total price based on the date range and room price
        function calculateTotalPrice() {
            const checkinDate = document.getElementById('my_update_checkin_date').value;
            const checkoutDate = document.getElementById('my_update_checkout_date').value;

            // Validate the dates before calculating the total price
            if (validateDatesUpdateForm()) {
                if (checkinDate && checkoutDate && !isNaN(baseRoomPrice) && baseRoomPrice > 0) {
                    const checkin = new Date(checkinDate);
                    const checkout = new Date(checkoutDate);
                    const daysDiff = (checkout - checkin) / (1000 * 60 * 60 * 24);

                    if (daysDiff > 0) {
                        const totalPrice = daysDiff * baseRoomPrice;
                        document.getElementById('my_update_room_price').value = totalPrice.toFixed(2);
                    }
                }
            } else {
                document.getElementById('my_update_room_price').value = ''; // Clear price if invalid dates
            }
        }

        // Set up event listeners
        document.getElementById('my_update_room_type').addEventListener('change', (event) => {
            updatefetchRoomIds(event.target.value); // Fetch room IDs when room type changes
        });

        document.getElementById('my_update_room_id').addEventListener('change', (event) => {
            updatefetchRoomPrice(event.target.value); // Fetch room price when room ID changes
        });

        document.getElementById('my_update_checkin_date').addEventListener('change', calculateTotalPrice); // Calculate price when check-in date changes
        document.getElementById('my_update_checkout_date').addEventListener('change', calculateTotalPrice); // Calculate price when check-out date changes

        // Initialize room IDs based on the default selected room type
        const initialRoomType = document.getElementById('my_update_room_type').value;
        updatefetchRoomIds(initialRoomType);
    });
</script>

</html>