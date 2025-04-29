<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Management - Ehototmamachochi Hotel</title>
    <!-- Bootstrap CSS -->
    <?php include 'asset/header_links.php'; ?>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'asset/navbar.php'; ?>

    <!-- Main content -->
    <main class="flex-fill">
        <!-- Sections -->
        <section id="defaultSection" class="container mt-5">
            <h1>Here you can view all beverage items currently in the menu. </h1>
           
            <div class="d-flex justify-content-center align-items-center gap-4 my-3">
                <button style="width: 40%;" type="button" onclick="filterCategory('soft-drink')" class="btn btn-success">Soft-Drink</button>
                <button style="width: 40%;" type="button" onclick="filterCategory('alcohol-drink')" class="btn btn-success">Alcohol-Drink</button>
                <button style="width: 40%;" type="button" onclick="filterCategory('all')" class="btn btn-secondary">List All Beverages</button>
            </div>

            <div id="viewBeverageTableContainer" class="table-responsive">
                <!-- Table will be inserted here by JavaScript -->
            </div>

            <?php
            include '../assets/conn.php';
            $mysqli = $conn;

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Fetch all beverage data from the table
            $query = "SELECT * FROM `beverage_in_bar_man`";
            $result = $mysqli->query($query);

            $beverages = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $beverages[] = $row;
                }
            }
            $mysqli->close();

            // Convert PHP data to JSON format for use in JavaScript
            echo '<script>';
            echo 'const viewBeverageData = ' . json_encode($beverages) . ';';
            echo '</script>';
            ?>
        </section>
    </main>

    <?php include '../assets/footer.php'; ?>

    <!-- JavaScript to handle row addition, removal, and dynamic data update -->
    <script>
        // Initialize the beverage data from PHP
        const beverageData = [];
        function initializeBeverageData(data) {
            beverageData.length = 0;
            beverageData.push(...data);
        }

        // Load the data provided by PHP into beverageData
        initializeBeverageData(viewBeverageData);

        // Generate table HTML based on the data provided
        function generateTable(data) {
            let tableHTML = `
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Measurement</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(row => {
                tableHTML += `
                    <tr>
                        <td>${row.beverage_name}</td>
                        <td>${row.beverage_type}</td>
                        <td>${row.measurement}</td>
                        <td>${row.beverage_quantity}</td>
                    </tr>
                `;
            });

            tableHTML += '</tbody></table>';
            document.getElementById('viewBeverageTableContainer').innerHTML = tableHTML;
        }

        // Filter data based on the selected category
        function filterCategory(category) {
            let filteredData = viewBeverageData;
            if (category !== 'all') {
                filteredData = viewBeverageData.filter(beverage => beverage.beverage_type === category);
            }
            generateTable(filteredData);
        }

        // Initially load all items
        filterCategory('all');
    </script>

<!-- Include the shared footer scripts -->
<?php include 'asset/footer_scripts.php'; ?>
</body>

</html>
