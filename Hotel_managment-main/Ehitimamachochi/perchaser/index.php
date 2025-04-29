<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchaser Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap_links.php'; ?>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        #defaultSection {
            display: block;
        }

        h1 {
            font-weight: bold;
        }

        .card-header {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <?php include 'asset/nav-bar.php'; ?>

        <!-- Main Content -->
        <section class="container mt-3 mb-0">
            <h1 class="text-center mb-4">Today's Activities</h1>
            <div class="row g-4 m-0 justify-content-center mb-0">
                <!-- Today's Purchased Items -->
                <div class="col-md-5 mb-0">
                    <div class="card">
                        <div class="card-header text-white" style="background-color: green;">
                            Today's Purchased Items
                        </div>
                        <div class="card-body text-center">
                            <p>Coming soon...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Today's Expenditure -->
                <div class="col-md-5 mb-0">
                    <div class="card">
                        <div class="card-header text-white" style="background-color: #333;">
                            Today's Expenditure
                        </div>
                        <div class="card-body text-center">
                            <p>Coming soon...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include 'asset/footer.php'; ?>
</body>
</html>
