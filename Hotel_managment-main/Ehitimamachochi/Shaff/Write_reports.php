<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Report - Ehototmamachochi Hotel</title>
     <?php include 'asset/bootstrap_links.php'; ?>
    <style>
        .report-section {
            margin-top: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: gray;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', Times, serif;">
    <div class="d-flex flex-column min-vh-100">
        <!-- Navigation Bar -->
        <?php include 'asset/nav-bar.php'; ?>

        <!-- Category Buttons -->
        <section id="Category_btn" class="my-3">
            <div class="d-flex justify-content-center" style="gap: 10%; text-align: center; margin: top 30px;">
                <button class="w_r btn"
                    style="width: 400px; height: 200px; position: relative; border: none; background-color: blue;color :white; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;"
                    onclick="loadFoodItems('fast_food')">
                    <h1>Fast Food</h1>
                    <span class="tooltip"
                        style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); background: rgba(0, 0, 0, 0.7); color: white; padding: 5px 10px; border-radius: 5px; display: none;">Fast
                        Food</span>
                </button>

                <button class="w_r btn"
                    style="width: 400px; height: 200px; position: relative; border: none; background-color: blue; color :white; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;"
                    onclick="loadFoodItems('food')">
                    <h1>Normal Foods</h1>
                    <span class="tooltip"
                        style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); background: rgba(0, 0, 0, 0.7); color: white; padding: 5px 10px; border-radius: 5px; display: none;">Normal
                        Food</span>
                </button>
            </div>
        </section>

        <script>
            // Show tooltip on hover
            document.querySelectorAll('.w_r').forEach(button => {
                button.addEventListener('mouseenter', function () {
                    this.querySelector('.tooltip').style.display = 'block';
                    this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.5)';
                    this.style.transform = 'scale(1.05)';
                    this.querySelector('img').style.transform = 'scale(1.1)'; // Slightly scale the image
                });

                button.addEventListener('mouseleave', function () {
                    this.querySelector('.tooltip').style.display = 'none';
                    this.style.boxShadow = 'none';
                    this.style.transform = 'scale(1)';
                    this.querySelector('img').style.transform = 'scale(1)'; // Reset image scale
                });
            });
        </script>

        <!-- Write Reports Section (hidden by default) -->
        <section id="WriteReports" class="container-fluid report-section" style="display: none;">
            <button onclick="goBack()" class="btn btn-secondary mb-3" aria-label="Go back to category selection">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <h2 style="text-align:center; margin-bottom :20px;"> ለየ አንዳዱ የ ምግብ አይንት የተጠቀምናቸው ግባቶች እና መጠናችው</h2>
            <form action="submit_report.php" method="post">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <label for="report_provider" class="me-2">your name:</label>
                        <input type="text" class="form-control" name="report_provider" id="report_provider"
                            value="<?php echo htmlspecialchars($report_provider_name); ?>" readonly required>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="report_type" class="me-2">Report Type:</label>
                        <select name="report_type" id="report_type" required>
                            <option value="" selected>Select report type</option>
                            <option value="fast_food">Fast Food</option>
                            <option value="normal_food">Normal Food</option>
                        </select>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="reported_date" class="me-2">Date:</label>
                        <input type="date" class="form-control" name="reported_date" id="reported_date" required>
                    </div>
                </div>
                <table id="reportTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name of Foods</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="3" class="text-center">Select a report type to load data...</td>
                        </tr>
                    </tbody>
                </table>
                <div class="button-container">
                    <button style="width: 40%;" type="submit" class="btn btn-primary">Submit</button>
                    <button style="width: 40%;" type="reset" class="btn btn-danger">Clear</button>
                </div>
            </form>
        </section>
        
        <!-- Footer -->
        <?php include 'asset/footer.php'; ?>
        <script>
            // Show the Write Reports section when a category button is clicked
            document.querySelectorAll('.w_r').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById("WriteReports").style.display = 'block';
                    document.getElementById("Category_btn").style.display = 'none';
                });
            });
            document.getElementById('reported_date').max = new Date().toISOString().split("T")[0];


            function loadFoodItems(category) {
                const tableBody = document.querySelector('#tableBody');
                tableBody.innerHTML = '<tr><td colspan="3" class="loading">Loading...</td></tr>';

                fetch(`fetch_foods.php?category=${category}`)
                    .then(response => response.json())
                    .then(data => populateTableRows(data))
                    .catch(error => {
                        console.error('Error fetching food items:', error);
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Failed to load items</td></tr>';
                    });
            }

            function populateTableRows(data) {
                const tableBody = document.querySelector('#tableBody');
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    // First cell with item name
                    const itemNameCell = document.createElement('td');
                    itemNameCell.textContent = item.item_name;
                    row.appendChild(itemNameCell);
                    // Create input cells for each header (except the first one)
                    const headerCount = document.querySelectorAll('#reportTable thead th').length;
                    for (let i = 1; i < headerCount; i++) {
                        const inputCell = document.createElement('td');
                        const input = document.createElement('input');
                        input.type = 'number'; // Input type can be adjusted as needed
                        input.className = 'form-control'; // Add Bootstrap styling
                        input.placeholder = `Default Not used`; // Placeholder for clarity
                        inputCell.appendChild(input);
                        row.appendChild(inputCell);
                    }
                    tableBody.appendChild(row);
                });
            }

            function goBack() {
                // Hide the Write Reports section and show the category buttons
                document.getElementById("WriteReports").style.display = 'none';
                document.getElementById("Category_btn").style.display = 'block'; // Show category buttons again
            }

            // Call fetchHeaders on page load
            window.onload = function () {
                fetchHeaders();
            };

            function fetchHeaders() {
                const headerRow = document.querySelector('#reportTable thead tr');
                headerRow.innerHTML = '<th>Name of Foods</th>';
                const tableBody = document.querySelector('#tableBody');
                tableBody.innerHTML = '<tr><td colspan="3" class="loading">Loading headers...</td></tr>';

                fetch(`fetch_headers.php?fetchHeaders=1`)
                    .then(response => response.json())
                    .then(headers => {
                        headers.forEach(header => {
                            const th = document.createElement('th');
                            th.textContent = header;
                            headerRow.appendChild(th);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching headers:', error);
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Failed to load headers</td></tr>';
                    });
            }
        </script>
    </div>

</body>

</html>