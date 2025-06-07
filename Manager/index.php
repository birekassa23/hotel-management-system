<?php
// Include database connection
// include '../assets/conn.php';
// session_start(); // Start the session

// Check if the user is logged in and has the correct position
// if (!isset($_SESSION['username']) || 
//     (strtolower($_SESSION['position']) !== 'manager')) {
//     // Redirect to login page if not logged in or not a manager
//     header("Location: ../index/index.php");
//     exit();
// }

// Close the database connection
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Panel - Ehototmamachochi Hotel</title>
    <!-- Include Header with all the resources -->
    <?php include 'assets/header.php'; ?>

    <style>
        /* General Page Styling */
        html, body {
            height: 100%;
            font-family: 'Times New Roman', Times, serif;
        }

        .content {
            flex: 1;
        }

        footer {
            margin-top: auto;
            padding: 20px;
            background-color: #333;
            color: #f8f9fa;
            text-align: center;
            font-size: 14px;
            border-top: 3px solid #00c8ff;
        }

        /* Card Styling */
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Navbar Link Hover Effect */
        .navbar-nav .nav-link::after {
            content: '';
            display: block;
            height: 2px;
            background-color: white;
            width: 0;
            position: absolute;
            left: 0;
            bottom: 0;
            transition: width 0.3s;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="d-flex flex-column">

    <!-- Navbar -->
    <?php include "assets/navbar.php"; ?>

    <!-- Main Content -->
    <div class="container content my-4">
        <h2 class="text-center mb-4">Today's Expense, Income, and Profit</h2>
        <div class="row">
            <!-- Cards for Expense, Income, and Profit -->
            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-primary text-white">Today's Expense</div>
                    <div class="card-body">
                        <p id="wechi" class="profit-text">Calculating...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-success text-white">Today's Income</div>
                    <div class="card-body">
                        <p id="gebi" class="profit-text">Calculating...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-warning text-dark">Today's Profit</div>
                    <div class="card-body">
                        <p id="tirf" class="profit-text">Calculating...</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-center my-4">Profit Prediction</h2>
        <div class="row">
            <!-- Cards for Profit Prediction -->
            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-info text-white">Weekly Profit</div>
                    <div class="card-body">
                        <p id="weeklyProfit" class="profit-text">Calculating...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-info text-white">Monthly Profit</div>
                    <div class="card-body">
                        <p id="monthlyProfit" class="profit-text">Calculating...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-info text-white">Yearly Profit</div>
                    <div class="card-body">
                        <p id="yearlyProfit" class="profit-text">Calculating...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include '../assets/footer.php'; ?>
</body>
</html>
