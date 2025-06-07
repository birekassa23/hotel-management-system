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

        .navbar {
            margin-bottom: 0;
            background-color: #343a40;
            height: 100px;
            align-items: center;
            gap: 10px;
        }

        .nav-item {
            margin: 10px;
        }

        .nav-item:hover {
            font-size: 17px;
            border-bottom: 1px blue solid;
            background-color: #333;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
        }

        .nav-link:hover {
            font-size: 18px;
        }

        .navbar-nav .nav-link.active {
            color: #ff5722 !important;
        }

        .navbar-nav {
            justify-content: center;
            /* Center-align the nav items */
            width: 100%;
            /* Ensure the ul takes full width to center items properly */
            gap: 10px;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Navbar Brand with icon -->
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-house-door"></i> Bar-man Panel
            </a>
            <!-- Toggler Button for Small Screens -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Home</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="view_beverages.php"><i class="bi bi-beer"></i> View Beverage</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="reports.php"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="settings.php"><i class="bi bi-gear"></i> Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->

    <!-- Include external JavaScript -->
    <?php include 'footer_scripts.php'; ?>
</body>

</html>
