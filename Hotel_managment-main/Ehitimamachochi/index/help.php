<!DOCTYPE html>
<html lang="en">
<!-- this is also the head part which is not visible-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Font Awesome (if needed) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body style="font-family: 'Times New Roman';"><!-- this is the body part which is visible on browsers -->

    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow fixed-top mb-5 p-3">
        <div class="container-xl">
            <a class="navbar-brand" href="./index.php">
                <!-- Circle with E letter -->
                <span class="d-inline-flex align-items-center justify-content-center bg-info text-white rounded-circle"
                    style="width: 40px; height: 40px; font-size: 20px; font-weight: bold;">
                    E
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav d-flex justify-content-center flex-grow-1 mb-2 mb-lg-0">
                    <li class="nav-item mx-2">
                        <a class="nav-link nav-link-custom active" aria-current="page" href="./index.php">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>

                    <li class="nav-item  mx-2" style="color:white; important;">
                        <a class="nav-link  nav-link-custom" href="room_page.php" id="roomsDropdown"
                            aria-expanded="false" style="color: white !important;">
                            Rooms
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link nav-link-custom" href="restaurant_page.php" aria-disabled="true"
                            style="color: white !important;">
                            Bar & Restaurant
                        </a>
                    </li>

                    <li class="nav-item  mx-2">
                        <a class="nav-link  nav-link-custom" href="meetingHalls_page.php" aria-expanded="false"
                            style="color: white !important;">
                            Meeting Halls
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link nav-link-custom" onclick="openContact_UsModal()"
                            style="color: white !important;">
                            Contact Us
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a onclick="showLoginModal()" style="font-size: 24px; color: white !important;">
                            <i class="bi bi-person-plus nav-link nav-link-custom" style="color: white !important;"></i>
                        </a>
                    </li>
                </ul>

                <form class="d-flex align-items-center ms-3">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Custom CSS -->
    <style>
        .nav-link-custom {
            color: #ffffff;
            /* Default color */
            transition: font-size 0.3s ease;
            /* Smooth transition for font size */
            font-size: 16px;
            /* Increase font size on hover */
        }

        .nav-link-custom:hover {
            color: white;
            /* White color on hover */
            background-color: rgba(255, 255, 255, 0.1);
            /* Optional: Add a slight background color on hover for better visibility */
            font-size: 18px;
            /* Increase font size on hover */
            text-decoration: none;
        }

        .nav-link-custom.active {
            color: #ffffff;
            /* Maintain white color for active link */
            background-color: rgba(255, 255, 255, 0.3);
            /* Optional: Highlight color for active link */
        }
    </style>
    
    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <!-- Videos Section -->
        <h2 class="text-center mb-4">Here's How to Make a Reservation</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/VIDEO_ID_1" allowfullscreen></iframe>
                </div>
                <p class="text-center mt-2">This is how To Make Food Reservation</p>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/VIDEO_ID_2" allowfullscreen></iframe>
                </div>
                <p class="text-center mt-2">This is how To Make Beverage Reservation</p>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/VIDEO_ID_3" allowfullscreen></iframe>
                </div>
                <p class="text-center mt-2">This is how To Make Room Reservation</p>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/VIDEO_ID_4" allowfullscreen></iframe>
                </div>
                <p class="text-center mt-2">This is how To Make Hall Reservation</p>
            </div>
        </div>

        <!-- PDF Guides Section -->
        <h2 class="text-center mt-5 mb-4">
            <i class="bi bi-book"></i> Reservation Guides
        </h2>
        <div class="d-grid gap-3 text-start px-3 px-md-4 px-lg-5"> <!-- Use responsive padding for better control -->
            <a href="path/to/guide1.pdf" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-file-earmark-text me-2"></i> View Food Reservation Guide
            </a>
            <a href="path/to/guide2.pdf" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-file-earmark-text me-2"></i> View Beverage Reservation Guide
            </a>
            <a href="path/to/guide3.pdf" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-file-earmark-text me-2"></i> View Room Reservation Guide
            </a>
            <a href="path/to/guide4.pdf" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-file-earmark-text me-2"></i> View Halls Reservation Guide
            </a>
        </div>


        <!-- Contact Information -->
        <div class="bg-white p-4 mt-5 rounded shadow-sm">
            <h2 class="text-center">Contact Us</h2>
            <p class="text-center mb-0">For reservations and inquiries, please reach out to us:</p>
            <div class="mt-3 text-start px-4 px-lg-5"> <!-- Use text-start for left alignment and larger padding -->
                <p class="mb-1">
                    <i class="bi bi-envelope-fill text-primary"></i>
                    <strong>Email: </strong> Ehitimamachochihotel@gmail.com
                </p>
                <p class="mb-1">
                    <i class="bi bi-telephone-fill text-primary"></i>
                    <strong>Phone: </strong> +251 91782 8062
                </p>
                <p class="mb-0">
                    <i class="bi bi-geo-alt-fill text-primary"></i>
                    <strong>Address: </strong> Sowth west Ethiopia, Teppi, Ayer Meda, Ehitimamachochi Hotel.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2024 Ehitimamachochi Hotel. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>