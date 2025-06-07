<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>

    <!-- Include external CSS and icons -->
    <?php include 'header_links.php'; ?>

    <style>
        /* Set global font family */
        * {
            font-family: 'Times New Roman', Times, serif;
        }

        /* Additional styling for html and body */
        html,
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar styling */
        .navbar {
            background-color: #343a40; /* Dark background for navbar */
        }

        /* Navbar item styling */
        .navbar-nav .nav-link {
            font-size: 16px;
            margin-right: 15px;
            color: white; /* Set text color to white */
        }

        /* Active link color */
        .navbar-nav .nav-link.active {
            color: #f8f9fa; /* Light color when active */
        }

        /* Ensure navbar items are spaced well */
        .navbar-nav {
            background-color: #343a40; /* Dark background for navbar items */
            border-radius: 5px; /* Rounded corners for the background */
        }

        /* Adjust navbar on mobile devices */
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                font-size: 14px; /* Smaller font size on smaller screens */
            }

            .navbar-nav {
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>
 <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #343a40; height: 100px;">
        <a class="navbar-brand" href="#" style="padding-left:20px;">Ehitimamachochi Hotel Host </a>
        <div class="container-xl h-100 d-flex align-items-center">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"
                style="border-color: white;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex justify-content-center w-100 bg-dark">
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/New/Ehitimamachochi/Host/index.php" role="button"
                            aria-expanded="false" style="color: white !important;"> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="Available_Foods.php" class="nav-link"  id="" style="color: white !important; cursor:pointer;"> Available Foods </a>
                    </li>
                    <li class="nav-item">
                        <a href="Available_Beverages.php" class="nav-link" id="" style="color: white !important; cursor:pointer;"> Available Beverages</a>
                    </li>
                    <li class="nav-item">
                        <a href="AuthorizeCustomer.php" class="nav-link" id="" style="color: white !important; cursor:pointer;"> Authorize Customer </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="setting.php" role="button" style="color: white !important; cursor:pointer;"> Account Settings </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    
</body>
<!-- Include external JavaScript -->
    <?php include 'footer_scripts.php'; ?>
</html>
