<?php
// Function to get hotel information from the assets folder
function getHotelInfo()
{
    $jsonData = file_get_contents('../assets/hotel_config.json'); // Ensure this path is correct
    return json_decode($jsonData, true);
}

// Get the hotel information
$hotelInfo = getHotelInfo();
?>

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



    <!-- Contact Us Modal -->
    <div id="myContact_UsModal" class="modal" onclick="if (event.target == this) closeContact_UsModal();"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
        <div id="myContact_UsModal_content" class="modal-content"
            style="background-color: white; margin: 10% auto; padding: 20px; border-radius: 8px; width: 60%; position: relative; animation: modalopen 0.5s;">
            <span id="closeButton" class="close" onclick="closeContact_UsModal()"
                style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 28px; font-weight: bold; cursor: pointer;"
                onmouseover="this.style.color='#ff0000';" onmouseout="this.style.color='#ff6f61';" aria-label="Close">
                <i class="fas fa-times"></i>
            </span>
            <div class="modal-title" style="text-align: center; width: 100%;">
                <h2 style="margin: 0;">Contact Us</h2>
            </div>
            <hr style="border-top: 1px solid #ccc;">
            <div class="modal-body" style="padding-top: 10px;">
                <div id="contactInfo" class="contact-info"
                    style="text-align: left; padding-left: 10%; font-size: 18px;">
                    <div style="color: #000;">
                        <!-- Phone Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fas fa-phone" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Phone</h2>
                                <a id="phoneLink" href="tel:+251917828062"
                                    style="color: #000; text-decoration: none; font-size: 18px;"><span><?php echo htmlspecialchars($hotelInfo['phone']); ?></span></a>
                            </div>
                        </div>

                        <!-- Email Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fas fa-envelope" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Email Address</h2>
                                <a id="emailLink" href="mailto:contact@ehitimamachochihotel.com"
                                    style="color: #000; text-decoration: none; font-size: 18px;"><span><?php echo htmlspecialchars($hotelInfo['email']); ?></span></a>
                            </div>
                        </div>

                        <!-- Facebook Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fab fa-facebook-f" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Facebook</h2>
                                <a id="facebookLink" href="https://facebook.com/ehitimamachochihotel"
                                    style="color: #000; text-decoration: none; font-size: 18px;">facebook.com/ehitimamachochihotel</a>
                            </div>
                        </div>

                        <!-- Twitter Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fab fa-twitter" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Twitter</h2>
                                <a id="twitterLink" href="https://twitter.com/ehitimamachochi"
                                    style="color: #000; text-decoration: none; font-size: 18px;">twitter.com/ehitimamachochi</a>
                            </div>
                        </div>

                        <!-- Instagram Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fab fa-instagram" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Instagram</h2>
                                <a id="instagramLink" href="https://instagram.com/ehitimamachochihotel"
                                    style="color: #000; text-decoration: none; font-size: 18px;">instagram.com/ehitimamachochihotel</a>
                            </div>
                        </div>

                        <!-- Office Address Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fas fa-map-marker-alt" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Office Address</h2>
                                <a id="mapLink"
                                    href="https://www.google.com/maps/place/Ehitmamachoch+Hotel/@7.1987875,35.4256296,13z/data=!4m6!3m5!1s0x17a913f104bddabd:0xe94caf4f7ead4a4d!8m2!3d7.2012757!4d35.4155521!16s%2Fg%2F11tbxhd1hg?entry=ttu&g_ep=EgoyMDI0MTAyMy4wIKXMDSoASAFQAw%3D%3D"
                                    target="_blank" style="color: #000; text-decoration: none; font-size: 18px;">South
                                    <span><?php echo htmlspecialchars($hotelInfo['adress']); ?></span>
                                    <span><?php echo htmlspecialchars($hotelInfo['name']); ?></span></a>
                            </div>
                        </div>

                        <!-- Help Center Section -->
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div style="margin-right: 15px;">
                                <i class="fas fa-question-circle" style="font-size: 2.2em; color: #007bff;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0;">Help Center</h2>
                                <a id="helpLink" href="help.html"
                                    style="color: #000; text-decoration: none; font-size: 18px;">Help Center</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end of Contact Us Modal -->

    <!-- Contact Us Modal JS -->
    <script>
        function closeContact_UsModal() {
            document.getElementById('myContact_UsModal').style.display = 'none';
        }

        function openContact_UsModal() {
            document.getElementById('myContact_UsModal').style.display = 'block';
        }
    </script>

    <!-- CSS for Modal Animation -->
    <style>
        @keyframes modalopen {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Hover Effects */
        a:hover {
            color: #007bff;
            text-decoration: underline;
        }
    </style>


    <!-- Login Form Modal -->
    <div id="loginModal" class="modal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
        <div class="modal-content"
            style="background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 500px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); position: relative;">
            <span class="close-button" onclick="closeModal()"
                style="position: absolute; top: 10px; right: 10px; color: #ff6f61; font-size: 28px; font-weight: bold; cursor: pointer;"
                onmouseover="this.style.color='#ff0000';" onmouseout="this.style.color='#ff6f61';">
                <i class="fas fa-times"></i>
            </span>
            <h2 class="modal-title" style="text-align: center; margin-top: 0; color: #333;" id="log-header">Login Here
            </h2>
            <?php
            // Check for existing cookie
            $username = isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : '';
            ?>

            <!-- Login Form -->
            <form method="post" action="loginprocess.php" style="padding: 10px;" id="loginform">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="username" style="display: block; margin-bottom: 5px; color: #555;">Username:</label>
                    <input type="text" id="username" name="username" required value="<?php echo $username; ?>"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px; color: #555;">Password:</label>
                    <input type="password" id="password" name="password" required
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <p>
                        <input type="checkbox" id="remember_me">
                        <label for="remember_me">Remember me</label>
                        <a href="#" style="display:flex; justify-content:right; padding-right:10%;"
                            onclick="document.getElementById('forgotpass').style.display='block'; document.getElementById('loginform').style.display='none'; document.getElementById('log-header').innerHTML = 'Forget Using Email '; return false;">Forget
                            password ?</a>
                    </p>
                </div>
                <div class="button-group" style="text-align: center;">
                    <button type="submit"
                        style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-right: 10px; width:40%;">Login</button>
                    <button type="reset"
                        style="background-color: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width:40%;">Clear</button>
                </div>
            </form>

            <!-- Forgot Password Section -->
            <div class="section" id="forgotpass" style="display:none;">
                <form action="forgotprocess.php" method="post">
                    <label for="email" style="display: block; margin-bottom: 5px; color: #555;">Enter your
                        email:</label>
                    <input type="email" id="email" name="email" required
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">

                    <!-- Flexbox container for the text and link -->
                    <div style="display: flex; justify-content: gap:15px; align-items: center; margin-top: 10px;">
                        <p style="margin: 0; color: #555;">If you remembered your password:</p>
                        <a href="#" style="padding-right: 10%;"
                            onclick="document.getElementById('forgotpass').style.display='none'; document.getElementById('loginform').style.display='block';document.getElementById('log-header').innerHTML = 'Login Here'; return false;">Login</a>
                    </div>

                    <div class="button-group" style="text-align: center; margin-top: 15px;">
                        <button type="submit"
                            style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-right: 10px; width:40%;">Submit</button>
                        <button type="reset"
                            style="background-color: #f44336; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width:40%;">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Login Form Modal -->
    <div class="loader" id="loader"></div>
    <style>
        /* Loader style */
        .loader {
            display: none;
            /* Hide initially */
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        // Show loader on form submission
        document.getElementById('loginForm').addEventListener('submit', function () {
            document.getElementById('loader').style.display = 'block';
        });
    </script>

    <script>
        // Get elements
        var loginModal = document.getElementById("loginModal");

        // Function to show the modal
        function showLoginModal() {
            loginModal.style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            loginModal.style.display = "none";
        }

        // Close the modal if the user clicks anywhere outside of the modal
        window.onclick = function (event) {
            if (event.target == loginModal) {
                closeModal();
            }
        }

        // Optional: Close the modal if the user presses the Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>







    <!-- content after here is displayed on the browsers when pages is onload -->
    <!-- Welcome Section -->
    <p style="margin: 110px 0;"></p><!--this for spacing only-->
    <div class="jumbotron jumbotron-fluid bg-light text-dark"
        style="padding: 2rem 1rem; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <div class="container">
            <h1 class="display-4"
                style="color: #007bff; font-weight: bold; font-family: 'Times New Roman', serif;  text-align: center;">
                Welcome <span><?php echo htmlspecialchars($hotelInfo['name']); ?></span>
            </h1>
            <hr class="my-4" style="border-top: 2px solid #000000;">
            <h2 style="font-size: 24px; font-weight: bold; font-family: 'Times New Roman', serif; color: #000000;">
                Services We Offer
            </h2>
            <ul
                style="font-size: 19px; font-family: 'Times New Roman', serif; color: #000000; list-style-type: disc; margin-left: 20px;">
                <li>Exquisite accommodations with various room options</li>
                <li>Culinary delights at our restaurant, featuring traditional Ethiopian dishes and modern creations
                </li>
                <li>A diverse selection of beverages at our bar, including local Ethiopian beer and international
                    favorites</li>
                <li>State-of-the-art meeting halls for your events and gatherings</li>
            </ul>
            <h2 style="font-size: 24px; font-weight: bold; font-family: 'Times New Roman', serif; color: #000000;">
                Our Location
            </h2>
            <p style="font-size: 18px; font-family: 'Times New Roman', serif; color: #000000;">
                Conveniently situated in the heart of South West Ethiopia, in the vibrant town of Teppi, our hotel
                offers easy access to local attractions and cultural experiences.
            </p>
        </div>
    </div><!-- end of Welcome Section -->

    <!-- Room Reservation jumbotron Section -->
    <div class="jumbotron jumbotron-fluid mt-5 py-5 px-5 bg-light text-dark rounded"
        style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h1 class="display-4"
            style="color: #007bff; font-weight: bold; font-family: 'Times New Roman', serif; text-align: center;">
            Explore Our Rooms</h1>
        <p class="lead" style="font-size: 25px; color: #000000;font-family: 'Times New Roman', serif; ">
            Welcome to our hotel! We offer a variety of comfortable and luxurious rooms tailored to your needs. <br>
        </p>
        <p style="font-size: 20px; color: #000000; font-family: 'Times New Roman', serif;"> Our rooms come with modern
            amenities to ensure a pleasant stay:
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            <div style="flex: 1; min-width: 220px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #000000;">Standard Rooms:</h2>
                <ul
                    style="list-style-type: circle; padding-left: 20px; font-size: 14px; color: #000000; font-family: 'Times New Roman', serif;">
                    <li style="list-style-type: none;font-size: 18px; color:#001000;"><u>Packages includes</u></li>
                    <li>Free Wi-Fi</li>
                    <li>common normal shawor</li>
                </ul>
            </div>
            <div style="flex: 1; min-width: 220px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #000000;">Deluxe Rooms:</h2>
                <ul
                    style="list-style-type: circle; padding-left: 20px; font-size: 14px; color: #000000;font-family: 'Times New Roman', serif;">
                    <li style="list-style-type: none;font-size: 18px; color:#001000;"><u>Packages includes </u></li>
                    <li>Free Wi-Fi</li>
                    <li> normal shawor</li>
                </ul>
            </div>
            <div style="flex: 1; min-width: 220px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #000000;">Suite Rooms:</h2>
                <ul
                    style="list-style-type: circle; padding-left: 20px; font-size: 14px; color: #000000;font-family: 'Times New Roman', serif;">
                    <li style="list-style-type: none;font-size: 18px; color:#001000;"><u>Packages includes </u></li>
                    <li>Free Wi-Fi</li>
                    <li>shawor(hoat and cold)</li>
                </ul>
            </div>
            <div style="flex: 1; min-width: 220px;">
                <h2 style="font-size: 24px; font-weight: bold; color: #000000; ">Luxury Rooms:</h2>
                <ul
                    style="list-style-type: circle; padding-left: 20px; font-size: 14px; color: #000000;font-family: 'Times New Roman', serif;">
                    <li style="list-style-type: none; font-size: 18px; color:#001000;"> <u>Packages includes</u> </li>
                    <li>Free Wi-Fi</li>
                    <li>shawor(hoat and cold)</li>
                    <li>Flat-TV</li>
                </ul>
            </div>
        </div>
        </p>

        <hr class="my-4" style="border-top: 2px solid #000000;">
        <center>
            <p style="font-size: 18px; color: #000000;">Ready to make a reservation? Click below!</p>
            <div class="container mt-5">
                <div class="">
                    <a href="room_page.php" class="btn btn-primary rounded-pill shadow-lg py-2 px-4" role="button">
                        Click to Reserve Room
                    </a>
                </div>
            </div>
        </center>
    </div><!-- end of Room Reservation jumbotron Section -->

    <!-- this is image for beds -->
    <div class="row">
        <!-- Luxury Room -->
        <div class="col-md-3">
            <div id="deluxe_room" style="position: relative; overflow: hidden;">
                <img src="./images\room\leve1\level1.2.jpg" alt="deluxe_room_img" width="300" height="400"
                    class="room_image" style="transition: transform 0.3s;">
                <p class="room_quality"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0,0,0,0.7); color: #fff; padding: 5px; border-radius: 5px;">
                    Luxury Room</p>
            </div>
        </div>

        <!-- Sweet Room -->
        <div class="col-md-3">
            <div id="deluxe_room" style="position: relative; overflow: hidden;">
                <img src="./images/room/level2/level2.jpg" alt="deluxe_room_img" width="300" height="400"
                    class="room_image" style="transition: transform 0.3s;">
                <p class="room_quality"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0,0,0,0.7); color: #fff; padding: 5px; border-radius: 5px;">
                    Sweet Room</p>
            </div>
        </div>

        <!-- Deluxe Room -->
        <div class="col-md-3">
            <div id="deluxe_room" style="position: relative; overflow: hidden;">
                <img src="./images\room\level3\level3.0.jpg" alt="deluxe_room_img" width="300" height="400"
                    class="room_image" style="transition: transform 0.3s;">
                <p class="room_quality"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0,0,0,0.7); color: #fff; padding: 5px; border-radius: 5px;">
                    Deluxe Room</p>
            </div>
        </div>

        <!-- Standard Room -->
        <div class="col-md-3">
            <div id="deluxe_room" style="position: relative; overflow: hidden;">
                <img src="./images\room\level4\level4.jpg" alt="deluxe_room_img" width="300" height="400"
                    class="room_image" style="transition: transform 0.3s;">
                <p class="room_quality"
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: rgba(0,0,0,0.7); color: #fff; padding: 5px; border-radius: 5px;">
                    Standard Room</p>
            </div>
        </div>
    </div> <!-- this is end of image for beds -->

    <!-- Restaurant Section -->
    <div id="food_image">
        <div class="jumbotron jumbotron-fluid mt-5 py-5 px-5 bg-light text-dark rounded"
            style="font-family: 'Times New Roman', serif; color: #000; font-size: 18px; width: 100%; margin: 0; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <!-- Centered Heading -->
            <h1 class="display-4" style="color: #007bff; font-weight: bold; margin-bottom: 1rem; text-align: center;">
                Experience Authentic Ethiopian Flavors</h1>

            <!-- Left-Aligned Text with Margin -->
            <p class="lead" style="margin-bottom: 1rem; text-align: left; margin-left: 20px;">
                Enjoy traditional Ethiopian dishes at <span><?php echo htmlspecialchars($hotelInfo['name']); ?></span>
                in <span><?php echo htmlspecialchars($hotelInfo['adress']); ?></span>. Our menu offers a real taste
                of local cuisine.
            </p>
            <p style="margin-bottom: 1rem; text-align: left; margin-left: 20px;">
                Try popular dishes like Doro Wat, Tibs, Kitfo, Gomen, and Kikil. Each meal is prepared with care,
                following traditional recipes.
            </p>
            <p style="margin-bottom: 1rem; text-align: left; margin-left: 20px;">
                Join us for a memorable dining experience. Click below to make a reservation and enjoy an authentic
                Ethiopian meal.
            </p>

            <!-- Centered Button -->
            <div style="text-align: center; margin-top: 1rem;">
                <a href="restaurant_page.php" class="btn btn-primary rounded-pill shadow-sm" role="button">
                    Click here for reservation
                </a>
            </div>
        </div>
        <!--this is image for food -->
        <center>
            <table style="width: 100%;">
                <tr>
                    <td id="image1" style="width: 25%; text-align: left;">
                        <div class="image-container" style="  width: 100%; overflow: hidden;">
                            <img src="./images/restorant/food/img1.jpg" alt="food_image"
                                style="height:100%; width: 100%;" data-description="Description of item 1">
                        </div>
                    </td>
                    <td id="image2" style="width: 25%; text-align: left;">
                        <div class="image-container" style=" width: 100%; overflow: hidden;">
                            <img src="./images/restorant/food/img2.jpg" alt="food_image"
                                style="height:100%; width: 100%;" data-description="Description of item 2">
                        </div>
                    </td>
                    <td id="image3" style="width: 25%; text-align: left;">
                        <div class="image-container" style=" width: 100%; overflow: hidden;">
                            <img src="./images/restorant/food/img3.jpg" alt="food_image"
                                style="height:100%; width: 100%;" data-description="Description of item 3">
                        </div>
                    </td>
                    <td id="image4" style="width: 25%; text-align: left;">
                        <div class="image-container" style=" width: 100%; overflow: hidden;">
                            <img src="./images/restorant/food/img4.jpg" alt="food_image"
                                style="height:100%; width: 100%;" data-description="Description of item 4">
                        </div>
                    </td>
                </tr>
            </table>
        </center> <!--  this is end of image for food -->
    </div><!--End of Restaurant Section -->

    <!--drinking_things_contents-->
    <div id="drinking_things_contents">
        <div class="jumbotron jumbotron-fluid mt-5 py-5 px-5 bg-light text-dark rounded"
            style="font-family: 'Times New Roman', serif; color: #000; font-size: 18px; width: 100%; margin: 0; text-align: center;">
            <h1 class="display-4" style="color: #007bff; font-weight: bold;">Discover Our Beverage Selection</h1>
            <p class="lead">
                Relax at our bar with a delightful variety of drinks, from local favorites to global classics.
            </p>
            <div style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px; max-width: 300px; text-align: center;">
                    <h3>Alcoholic Beverages</h3>
                    <ul class="list-unstyled" style="padding: 0; margin: 0; display: inline-block; text-align: left;">
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-beer" viewBox="0 0 16 16">
                                <path
                                    d="M5 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1H5V3zM4 4h8v8H4V4zm7-1h1v1H5V3h6zm-1 8a1 1 0 0 1-2 0h2zM1 4v9h14V4H1z" />
                            </svg>
                            Balageru Berra
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-beer" viewBox="0 0 16 16">
                                <path
                                    d="M5 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1H5V3zM4 4h8v8H4V4zm7-1h1v1H5V3h6zm-1 8a1 1 0 0 1-2 0h2zM1 4v9h14V4H1z" />
                            </svg>
                            Meta Beer
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-beer" viewBox="0 0 16 16">
                                <path
                                    d="M5 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1H5V3zM4 4h8v8H4V4zm7-1h1v1H5V3h6zm-1 8a1 1 0 0 1-2 0h2zM1 4v9h14V4H1z" />
                            </svg>
                            Dashen Beer
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-beer" viewBox="0 0 16 16">
                                <path
                                    d="M5 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1H5V3zM4 4h8v8H4V4zm7-1h1v1H5V3h6zm-1 8a1 1 0 0 1-2 0h2zM1 4v9h14V4H1z" />
                            </svg>
                            Bedele Beer
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-beer" viewBox="0 0 16 16">
                                <path
                                    d="M5 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1H5V3zM4 4h8v8H4V4zm7-1h1v1H5V3h6zm-1 8a1 1 0 0 1-2 0h2zM1 4v9h14V4H1z" />
                            </svg>
                            Meta Beer And more!
                        </li>
                    </ul>
                </div>
                <div style="flex: 1; min-width: 200px; max-width: 300px; text-align: center;">
                    <h3>Soft Drinks</h3>
                    <ul class="list-unstyled" style="padding: 0; margin: 0; display: inline-block; text-align: left;">
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cup-straw" viewBox="0 0 16 16">
                                <path
                                    d="M11 1v1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h2V1h4zM3 4v10h10V4H3z" />
                            </svg>
                            Coca-Cola
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cup-straw" viewBox="0 0 16 16">
                                <path
                                    d="M11 1v1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h2V1h4zM3 4v10h10V4H3z" />
                            </svg>
                            Fanta
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cup-straw" viewBox="0 0 16 16">
                                <path
                                    d="M11 1v1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h2V1h4zM3 4v10h10V4H3z" />
                            </svg>
                            Sprite
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cup-straw" viewBox="0 0 16 16">
                                <path
                                    d="M11 1v1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h2V1h4zM3 4v10h10V4H3z" />
                            </svg>
                            Pepsi
                        </li>
                        <li style="margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cup-straw" viewBox="0 0 16 16">
                                <path
                                    d="M11 1v1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h2V1h4zM3 4v10h10V4H3z" />
                            </svg>
                            7 Up And more!
                        </li>
                    </ul>
                </div>
            </div>
            <p> Each beer offers a unique taste of Ethiopian brewing tradition. Visit us at
                <span><?php echo htmlspecialchars($hotelInfo['name']); ?></span> and
                enjoy a memorable experience!
            </p>
            <!-- Centered Button -->
            <div style="text-align: center; margin-top: 1rem;">
                <a href="restaurant_page.php" class="btn btn-primary rounded-pill shadow-sm" role="button">
                    Click here for reservation
                </a>
            </div>
        </div>

        <center>
            <table style="width: 100%;">
                <!-- Images for Drinking Things -->
                <tr>
                    <td style="width: 25%; text-align: left;">
                        <div class="image-container">
                            <img style="width: 100%;height:100%;" src="./images/restorant/drink/beer1.jpg"
                                alt="beverages_image" data-description="Description of beer 1">
                        </div>
                    </td>

                    <td style="width: 25%; text-align: left;">
                        <div class="image-container">
                            <img style="width: 100%;height:100%;" src="./images/restorant/drink/beer2.jpg"
                                alt="beverages_image" data-description="Description of beer 2">
                        </div>
                    </td>

                    <td style="width: 25%; text-align: left;">
                        <div class="image-container">
                            <img style="width: 100%;height:100%;" src="./images/restorant/drink/beer3.jpg"
                                alt="beverages_image" data-description="Description of beer 3">
                        </div>
                    </td>

                    <td style="width: 25%; text-align: left;">
                        <div class="image-container">
                            <img style="width: 100%;height:100%;" src="./images/restorant/drink/beer4.jpg"
                                alt="beverages_image" data-description="Description of beer 4">
                        </div>
                    </td>
                </tr><!-- End of Images for Drinking Things -->
            </table>
        </center>
    </div><!--End of drinking_things_contents-->

    <!--meeting_halls_content--->
    <div id="meeting_halls_content" style="font-family: 'Times New Roman', serif; padding: 20px;">
        <div class="jumbotron jumbotron-fluid mt-5 py-5 px-5 bg-light text-dark rounded"
            style="border: 1px solid #ddd; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <!-- Centered Heading -->
            <h1 class="display-4" style="color: #007bff; text-align: center; font-weight: bold; ">Host Your Events with
                Us</h1>
            <!-- Centered Content Section -->
            <div style="text-align: center; padding: 0 15px;">
                <div style="display: inline-block; text-align: left; margin-left: 20px;">
                    <p class="lead" style="font-size: 1.5rem; color: #000; margin-bottom: 10px;">
                        Our meeting halls are perfect for your events, whether they are for work, government, or any
                        other group.
                    </p>

                    <p style="font-size: 18px; color: #000; margin-bottom: 18px;">
                        We have spacious halls that can fit up to 120 people. They are great for meetings and
                        presentations.
                    </p>

                    <p style="font-size: 18px; color: #000; margin-bottom: 18px;">
                        Our halls come with modern equipment and flexible seating to make your event go smoothly.
                    </p>

                    <p style="font-size: 18px; color: #000; margin-bottom: 18px;">
                        Book your meeting hall today and enjoy our professional service at
                        <span><?php echo htmlspecialchars($hotelInfo['name']); ?></span>.
                    </p>
                </div>
                <!-- Centered Button for Reservation -->
                <div style="text-align: center; margin-top: 20px;">
                    <a href="meetingHalls_page.php" class="btn btn-primary rounded-pill shadow-sm" role="button">
                        Click here for meeting hall reservation
                    </a>
                </div>
            </div>
        </div>

        <!-- Images of meeting halls -->
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="image-container">
                    <img style="width: 100%; height:  100%;; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);"
                        src="./images/meeting_hills/img1.jpg" alt="Meeting Halls Images"
                        data-description="Description of meeting hall 1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="image-container">
                    <img style="width: 100%; height:  100%;; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);"
                        src="./images/meeting_hills/img2.jpg" alt="Meeting Halls Images"
                        data-description="Description of meeting hall 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="image-container">
                    <img style="width: 100%; height:  100%;; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);"
                        src="./images/meeting_hills/img3.jpg" alt="Meeting Halls Images"
                        data-description="Description of meeting hall 3">
                </div>
            </div>
            <div class="col-md-3">
                <div class="image-container">
                    <img style="width: 100%; height:  100%;; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);"
                        src="./images/meeting_hills/img4.jpg" alt="Meeting Halls Images"
                        data-description="Description of meeting hall 4">
                </div>
            </div>
        </div><!-- End of Images for Meeting Halls -->
    </div><!--End of meeting_halls_content--->


    <!-- this section holds aboutus,our services,..... ,  -->
    <div style="padding: 20px; margin: 0%;background-color: #222;">
        <hr style="color: white; border-color: white;">
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            <!-- About Us -->
            <div
                style="flex: 1; min-width: 25%; background-color: #222; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); font-family: 'Times New Roman', serif; font-size: 12px;">
                <h2 style="color: #007bff; text-align: center; font-size: 18px;">About Us</h2>
                <p>Welcome to <strong><span><?php echo htmlspecialchars($hotelInfo['name']); ?></span></strong>, a
                    charming getaway nestled in <span><?php echo htmlspecialchars($hotelInfo['adress']); ?></span>.</p>
                <p>Established on <strong>September 2, 2014</strong>, we are located
                    near<span><?php echo htmlspecialchars($hotelInfo['adress']); ?></span>
                    Academy.</p>
                <p>Enjoy weddings, relaxation, and live music concerts at our venue.</p>
            </div>

            <!-- Our Services -->
            <div
                style="flex: 1; min-width: 25%; background-color: #222; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); font-family: 'Times New Roman', serif; font-size: 12px;">
                <h2 style="color: #007bff; text-align: center; font-size: 18px;">Our Services</h2>
                <a href="#"
                    style="display: flex; align-items: center; color: white; text-decoration: none; padding: 10px; border-bottom: 1px solid #444;">
                    <i class="fas fa-house-user" style="color: #00c8ff; margin-right: 8px;"></i> Room Reservation
                </a>
                <a href="#"
                    style="display: flex; align-items: center; color: white; text-decoration: none; padding: 10px; border-bottom: 1px solid #444;">
                    <i class="fas fa-briefcase" style="color: #00c8ff; margin-right: 8px;"></i>
                    Meeting Halls
                </a>
                <a href="#"
                    style="display: flex; align-items: center; color: white; text-decoration: none; padding: 10px; border-bottom: 1px solid #444;">
                    <i class="fas fa-cocktail" style="color: #00c8ff; margin-right: 8px;"></i>
                    Bar and Restaurant
                </a>
                <a href="#"
                    style="display: flex; align-items: center; color: white; text-decoration: none; padding: 10px;">
                    <i class="fas fa-heart" style="color: #00c8ff; margin-right: 8px;"></i>
                    Place for Weddings
                </a>
            </div>

            <!-- Contact Us -->
            <div
                style="flex: 1; min-width: 25%; background-color: #222; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); font-family: 'Times New Roman', serif; font-size: 12px;">
                <h2 style="color: #007bff; text-align: center; font-size: 18px;">Contact Us</h2>
                <p style="margin-bottom: 10px;">
                    <i class="fas fa-phone" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="tel:+251917828062"
                        style="color: white; text-decoration: none;"><span><?php echo htmlspecialchars($hotelInfo['phone']); ?></span></a>
                </p>
                <p style="margin-bottom: 10px;">
                    <i class="fas fa-envelope" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="mailto:Ehitimamachochi@gmail.com"
                        style="color: white; text-decoration: none;"><span><?php echo htmlspecialchars($hotelInfo['email']); ?></span></a>
                </p>
                <p style="margin-bottom: 10px;">
                    <i class="fab fa-facebook-f" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="https://facebook.com" style="color: white; text-decoration: none;">
                        <span><?php echo htmlspecialchars($hotelInfo['facebook']); ?></span></a>
                </p>
                <p style="margin-bottom: 10px;">
                    <i class="fab fa-twitter" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="https://twitter.com"
                        style="color: white; text-decoration: none;"><span><?php echo htmlspecialchars($hotelInfo['twitter']); ?></span></a>
                </p>
                <p style="margin-bottom: 10px;">
                    <i class="fab fa-instagram" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="https://instagram.com"
                        style="color: white; text-decoration: none;"><span><?php echo htmlspecialchars($hotelInfo['instagram']); ?></span></a>
                </p>
                <p style="margin-bottom: 10px;">
                    <i class="fas fa-map-marker-alt" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="https://www.google.com/maps/place/Ehitmamachoch+Hotel/@7.1987875,35.4256296,13z/data=!4m6!3m5!1s0x17a913f104bddabd:0xe94caf4f7ead4a4d!8m2!3d7.2012757!4d35.4155521!16s%2Fg%2F11tbxhd1hg?entry=ttu&g_ep=EgoyMDI0MTAyMy4wIKXMDSoASAFQAw%3D%3D"
                        style="color: white; text-decoration: none;"
                        target="_blank"><span><?php echo htmlspecialchars($hotelInfo['adress']); ?></span><span><?php echo htmlspecialchars($hotelInfo['town']); ?></span><span><?php echo htmlspecialchars($hotelInfo['regin']); ?></span><span><?php echo htmlspecialchars($hotelInfo['Countery']); ?></span></a>
                </p>
                <p style="margin-bottom: 0;">
                    <i class="fas fa-question-circle" style="color: #00c8ff; margin-right: 8px;"></i>
                    <a href="help.html" style="color: white; text-decoration: none;">Help Center</a>
                </p>
            </div>

            <!-- Comment Section / Form -->
            <div
                style="flex: 1; min-width: 25%; background-color: #222; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); font-family: 'Times New Roman', serif; font-size: 12px;">
                <h2 style="color: #007bff; text-align: center; font-size: 18px;">We Value Your Feedback</h2>
                <span id="error_comment_form"
                    style="color: red; display: none; text-align: center; margin-bottom: 15px;"></span>

                <center>
                    <form id="commentForm" action="comment_handler.php" method="post"
                        style="text-align: center; width: 25%; display: flex; flex-direction: column;">
                        <div style="margin-bottom: 15px;">
                            <label for="Username" style="display: block; margin-bottom: 5px;"><i class="fas fa-user"
                                    style="color: #007bff;"></i> Your Name:</label>
                            <span style="color: red; display: none;" id="errorUsername"></span>
                            <input type="text" id="Username" name="Username" placeholder="Enter your name"
                                style="width: 100%; padding: 8px; font-size: 12px; border-radius: 4px; border: 1px solid #ccc;"
                                required>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label for="message" style="display: block; margin-bottom: 5px;"><i
                                    class="fas fa-pencil-alt" style="color: #007bff;"></i> Comment:</label>
                            <span style="color: red; display: none;" id="errorMessage"></span>
                            <textarea id="message" name="message" rows="5" placeholder="Write your comment here!"
                                style="width: 100%; padding: 8px; font-size: 12px; color: black; border-radius: 4px; border: 1px solid #ccc;"
                                required></textarea>
                        </div>
                        <div style="text-align: center; margin-top: auto;">
                            <button type="submit"
                                style="width: 45%; color: white; background-color: green; border: none; padding: 10px; font-size: 12px; border-radius: 4px; margin-right: 10px;">Submit</button>
                            <button type="reset"
                                style="width: 45%; color: white; background-color: red; border: none; padding: 10px; font-size: 12px; border-radius: 4px;">Clear</button>
                        </div>
                    </form>

                    <!-- Add this element for the form-wide error message -->
                    <span id="error_comment_form"
                        style="color: red; display: none; text-align: center; margin-bottom: 15px;"></span>

                    <script>
                        const form = document.getElementById('commentForm');
                        const inputName = document.getElementById('Username');
                        const inputMessage = document.getElementById('message');
                        const errorUsername = document.getElementById('errorUsername');
                        const errorMessage = document.getElementById('errorMessage');
                        const errorCommentForm = document.getElementById('error_comment_form');

                        function validateUsername() {
                            const username = inputName.value.trim();
                            const regex = /^[a-zA-Z\s]{2,}$/; // Only letters and spaces, at least 2 characters
                            if (!regex.test(username)) {
                                errorUsername.style.display = 'block';
                                errorUsername.textContent = "Username must contain only letters and be at least 2 characters long.";
                                return false;
                            }
                            errorUsername.style.display = 'none';
                            return true;
                        }

                        function validateMessage() {
                            const message = inputMessage.value.trim();
                            const sentenceRegex = /\b\w+\b.*?\./g; // Matches sentences with words ending in a period
                            const wordCount = message.split(/\s+/).filter(word => word.length > 0).length;

                            if (message.length < 10 || !sentenceRegex.test(message) || wordCount < 3) {
                                errorMessage.style.display = 'block';
                                errorMessage.textContent = "Message must be at least 10 characters long and contain at least one sentence with 3 words.";
                                return false;
                            }
                            errorMessage.style.display = 'none';
                            return true;
                        }

                        // Validate dynamically
                        inputName.addEventListener('input', validateUsername);
                        inputMessage.addEventListener('input', validateMessage);

                        // Validate on submit
                        form.addEventListener('submit', function (e) {
                            const isNameValid = validateUsername();
                            const isMessageValid = validateMessage();

                            if (!isNameValid || !isMessageValid) {
                                e.preventDefault(); // Prevent form submission

                                // Reset inputs (optional)
                                inputName.value = "";
                                inputMessage.value = "";

                                // Show global error message
                                errorCommentForm.style.display = 'block';
                                errorCommentForm.textContent = "Invalid comment submission. Please correct the errors above.";
                            } else {
                                errorCommentForm.style.display = 'none'; // Hide global error message
                            }
                        });
                    </script>

                </center>
            </div>
        </div>
    </div>


    <!--the following are JavaScript to handle d/t actions -->

    <!--JavaScript to handle navbar hide/show on scroll-->
    <script>
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop) {
                // Scrolling down
                navbar.classList.remove('navbar-visible');
                navbar.classList.add('navbar-hidden');
            } else {
                // Scrolling up
                navbar.classList.remove('navbar-hidden');
                navbar.classList.add('navbar-visible');
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
        });
    </script>



    <!-- this is js for enlarging bed image -->
    <script>
        document.querySelectorAll('.room_image').forEach(item => {
            item.addEventListener('mouseenter', event => {
                let quality = event.target.parentElement.querySelector('.room_quality');
                let image = event.target;
                quality.style.display = 'block';
                image.style.transform = 'scale(1.1)';
            });
            item.addEventListener('mouseleave', event => {
                let quality = event.target.parentElement.querySelector('.room_quality');
                let image = event.target;
                quality.style.display = 'none';
                image.style.transform = 'scale(1)';
            });
        });
    </script>



    <!--JavaScript to handle Drinking Things image description  and description -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const imageContainers = document.querySelectorAll('.image-container');

            imageContainers.forEach(container => {
                const img = container.querySelector('img');
                const description = img.dataset.description;

                container.addEventListener('mouseover', function () {
                    img.style.transform = 'scale(1.1)';
                    // Show description
                    console.log(description); // You can replace this with code to display the description
                });

                container.addEventListener('mouseout', function () {
                    img.style.transform = 'scale(1)';
                    // Hide description
                });
            });
        });
    </script>


    <!--JavaScript to handle Meeting Halls image zoom and description -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const imageContainers = document.querySelectorAll('.image-container');

            imageContainers.forEach(container => {
                const img = container.querySelector('img');
                const description = img.dataset.description;

                container.addEventListener('mouseover', function () {
                    img.style.transform = 'scale(1.1)';
                    // Show description
                    console.log(description); // You can replace this with code to display the description
                });
                container.addEventListener('mouseout', function () {
                    img.style.transform = 'scale(1)';
                    // Hide description
                });
            });
        });
    </script>

    <!-- JavaScript to set equal height for all image containers and handle hover effect -->
    <script>
        window.addEventListener('load', function () {
            var images = document.querySelectorAll('.image-container img');
            var maxHeight = 0;

            images.forEach(function (image) {
                if (image.offsetHeight > maxHeight) {
                    maxHeight = image.offsetHeight;
                }
            });

            images.forEach(function (image) {
                image.parentNode.style.height = maxHeight + 'px';

                image.addEventListener('mouseover', function () {
                    image.style.transform = 'scale(1.1)';
                    var description = image.dataset.description;
                    console.log(description); // You can replace this with code to display the description
                });

                image.addEventListener('mouseout', function () {
                    image.style.transform = 'scale(1)';
                    // Hide description
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showAlert() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your comment has been successfully submitted.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            } else if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an error submitting your comment. Please try again.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            }

            // Remove the `status` parameter from the URL after the alert
            urlParams.delete('status');
            const newUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.replaceState({}, document.title, newUrl);
        }

        window.onload = showAlert;
    </script>

    <footer
        style="padding: 30px; background-color: #333; color: #f8f9fa; text-align: center; font-family: 'Times New Roman', serif; font-size: 14px; border-top: 3px solid #00c8ff;">
        <p style="margin: 0; line-height: 1.5;">
            &copy; 2024 EHITIMAMACHOCHI HOTEL. All rights reserved.<br>Powered by MTU Department of SE Group 1 Members
        </p>
    </footer>
    <!-- the following are Popper.js (required for Bootstrap's JavaScript components) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</body>

</html>