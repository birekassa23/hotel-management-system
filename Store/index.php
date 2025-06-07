<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Manager - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap_links.php'; ?> <!-- Include Bootstrap CSS links -->

    <style>
        /* Custom styles */

        /* Make the page fill the entire height */
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* The content area that should fill the remaining space */
        .content {
            flex: 1;
            padding-bottom: 20px; /* Add some padding at the bottom to make room for footer */
        }

        /* Styling for the report section */
        .report-section {
            margin-top: 20px;
        }

        .card-header {
            font-weight: bold;
        }

        .card-body {
            text-align: center;
            color: #666;
        }

        /* Footer styling */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
            /* Push the footer to the bottom */
        }

        /* Ensure nav items are styled */
        .nav-item {
            font-size: 16px;
        }

        .nav-item:hover {
            border-bottom: 1px solid blue;
        }

        /* Ensure the sections are properly displayed */
        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        #defaultSection {
            display: block;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', Times, serif;">
    <!-- Main content structure -->
    <div class="d-flex flex-column min-vh-100">
        <?php include 'asset/nav-bar.php'; ?> <!-- Include Navbar -->

        <!-- Today's Activities Section -->
        <section class="container mt-4 content">
            <h1 class="text-center">Today's Activities</h1>
            <div class="row g-5 justify-content-center">
                <!-- Card for Stocked Items -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header" style="background-color:green;color:white;">
                            Today's Stocked Items
                        </div>
                        <div class="card-body">
                            <p>This section is under construction.</p>
                        </div>
                    </div>
                </div>
                <!-- Card for Out-of-Stock Items -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header" style="background-color:#333;color:white;">
                            Today's Out-of-Stock Items
                        </div>
                        <div class="card-body">
                            <p>This section is under construction.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <?php include 'asset/footer.php'; ?> <!-- Include Footer -->
    </div>
</body>

</html>
