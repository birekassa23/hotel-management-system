<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shaff Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap_links.php'; ?>
    <style>
        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        #defaultSection {
            display: block;
            /* Ensure the default section is visible by default */
        }

        .card-header {
            font-weight: bold;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', Times, serif;">

    <div class="d-flex flex-column min-vh-100">
        <!-- Navigation Bar -->
        <?php include 'asset/nav-bar.php'; ?>

        <!-- Main Section -->
        <section class="container mt-4">
            <div class="row g-5 justify-content-center">
                <!-- Section Header -->
                <h1 class="text-center">Today's Activities</h1>

                <!-- Card 1 -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            Today's Outstoked Items for You
                        </div>
                        <div class="card-body">
                            <p>Here you can view the list of items outstoked today.</p>
                            <button class="btn btn-outline-success">View Details</button>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            Today's Expenditure for You
                        </div>
                        <div class="card-body">
                            <p>Check today's expenditure details.</p>
                            <button class="btn btn-outline-dark">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <?php include 'asset/footer.php'; ?>
    </div>
</body>

</html>
