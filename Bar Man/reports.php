<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barman-Report-page</title>
    <?php include 'asset/header_links.php'; ?>
    <link rel="stylesheet" href="asset/report.css" class="css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'asset/navbar.php'; ?>

    <!-- Main Container -->
    <div id="mainContainer" class="container-custom">
        <div class="container">
            <div class="list-group">
                <!-- View Reports (Dropdown) -->
                <a href="#"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 rounded-3 mb-2 bg-light"
                    data-bs-toggle="collapse" data-bs-target="#viewReportsContent" aria-expanded="false"
                    aria-controls="viewReportsContent">
                    <span><i class="bi bi-file-earmark-text me-2"></i><strong>View Reports</strong></span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <!-- Collapsible Dropdown Content -->
                <div id="viewReportsContent" class="collapse">
                    <div class="list-group ms-3">
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 mb-2 bg-light"
                            onclick="showSection('view_received_report')">
                            <span><i class="bi bi-archive me-2"></i>Received Items Reports</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 mb-2 bg-light"
                            onclick="showSection('view_sold_report')">
                            <span><i class="bi bi-building me-2"></i>Sold Items Reports</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Write Reports -->
                <a href="#"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 rounded-3 mb-2 bg-light"
                    onclick="showSection('new_inserted_WriteReports')">
                    <span><i class="bi bi-pencil me-2"></i><strong>Write Reports</strong></span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
 <!-- CSS to hide the sections by default -->
    <style>
        .hidden {
            display: none;
        }

        .container-custom,
        .report-section {
            margin-top: 20px;
        }
    </style>





    <!-- in this section i have to display number of beverage inserted at specific date on beverage-in-barman tble -->
    <!-- automatically search for todays date data and displayed according to searched date  -->
    <!-- when user clicks the next and previous date button search from table if previous date cliked date = date-1  and if current date and the searched date==todaays date make next day table disable -->
    <!-- and search accordinglly and display in attractive and smart way -->

    <!-- View Received item  Report Section -->
    <section id="view_received_report" class="container-custom hidden">
    <div class="container">
        <!-- Back Button -->
        <button onclick="goBack('ViewReports')" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </button>

        <!-- Search and Navigation Section -->
        <div class="d-flex justify-content-between mb-4">
            <!-- Search Date Section -->
            <div class="d-flex flex-grow-1 gap-2">
                <input type="date" id="searchDate" class="form-control" placeholder="Search by date">
                <button class="btn btn-primary btn-custom" onclick="searchByDate()">
                    <i class="bi bi-search me-2"></i> Search
                </button>
            </div>

            <!-- Date Navigation Section -->
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-custom" onclick="goToPreviousPage()"> 
                    <i class="bi bi-chevron-left me-2"></i> Previous Day
                </button>
                <button class="btn btn-outline-secondary btn-custom" onclick="goToNextPage()" id="nextDayButton"> 
                    Next Day <i class="bi bi-chevron-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Tab Section -->
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'SoftBeverage')" id="defaultOpen">Soft Beverage</button>
            <button class="tablinks" onclick="openTab(event, 'AlcoholBeverage')">Alcohol Beverage</button>
            <button class="tablinks" onclick="openTab(event, 'BothBeverage')">Both Beverages</button>
        </div>

        <!-- Tab Content -->
        <div id="SoftBeverage" class="tabcontent">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Beverage Name</th>
                            <th>Beverage Type</th>
                            <th>Measurement</th>
                            <th>Quantity</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody id="softBeverageTableBody"></tbody>
                </table>
            </div>
        </div>

        <div id="AlcoholBeverage" class="tabcontent">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Beverage Name</th>
                            <th>Beverage Type</th>
                            <th>Measurement</th>
                            <th>Quantity</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody id="alcoholBeverageTableBody"></tbody>
                </table>
            </div>
        </div>

        <div id="BothBeverage" class="tabcontent">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Beverage Name</th>
                            <th>Beverage Type</th>
                            <th>Measurement</th>
                            <th>Quantity</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody id="bothBeverageTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const today = new Date().toISOString().split('T')[0];
        const searchDateInput = document.getElementById("searchDate");

        searchDateInput.value = today;
        searchByDate(today); // Automatically load today's data
        toggleNextDayButton(today);
    });

    function searchByDate(date = null) {
        const selectedDate = date || document.getElementById("searchDate").value;

        fetch(`/getBeveragesByDate?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => updateTables(data))
            .catch(err => console.error("Error fetching data:", err));

        toggleNextDayButton(selectedDate);
    }

    function updateTables(data) {
        // Clear table content
        ["softBeverageTableBody", "alcoholBeverageTableBody", "bothBeverageTableBody"].forEach(id => {
            document.getElementById(id).innerHTML = "";
        });

        data.forEach(beverage => {
            const row = `
                <tr>
                    <td>${beverage.name}</td>
                    <td>${beverage.type}</td>
                    <td>${beverage.measurement}</td>
                    <td>${beverage.quantity}</td>
                    <td>${beverage.date}</td>
                    <td>${beverage.added_by}</td>
                </tr>`;
            
            if (beverage.type === "Soft Beverage") {
                document.getElementById("softBeverageTableBody").innerHTML += row;
            } else if (beverage.type === "Alcohol Beverage") {
                document.getElementById("alcoholBeverageTableBody").innerHTML += row;
            }

            document.getElementById("bothBeverageTableBody").innerHTML += row;
        });
    }

    function goToPreviousPage() {
        const searchDateInput = document.getElementById("searchDate");
        const currentDate = new Date(searchDateInput.value);

        currentDate.setDate(currentDate.getDate() - 1);
        const formattedDate = currentDate.toISOString().split('T')[0];
        searchDateInput.value = formattedDate;

        searchByDate(formattedDate);
    }

    function goToNextPage() {
        const searchDateInput = document.getElementById("searchDate");
        const currentDate = new Date(searchDateInput.value);

        currentDate.setDate(currentDate.getDate() + 1);
        const formattedDate = currentDate.toISOString().split('T')[0];
        searchDateInput.value = formattedDate;

        searchByDate(formattedDate);
    }

    function toggleNextDayButton(selectedDate) {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("nextDayButton").disabled = (selectedDate === today);
    }
</script>

<style>
    .btn-custom {
        min-width: 120px;
        padding: 10px 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-custom:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
</style>
 <script>
        // Function to show a specific section and hide all others
        function showSection(sectionId) {
            // Hide all sections
            const allSections = document.querySelectorAll('.container-custom, .report-section');
            allSections.forEach(section => section.classList.add('hidden'));

            // Show the selected section
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.remove('hidden');
            }
        }
        // Function to go back to the previous section
        function goBack(previousSectionId) {
            showSection(previousSectionId);
        }
    </script>

<!--  this is the the end of the 1st section -->









    <!-- view sold report Section  this is from different table and from  different table -->
    <!-- <section id="view_sold_report" class="container-custom hidden">
        <div class="container">
            <button onclick="goBack('sold_ViewReports')" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Back
            </button> -->
            <!-- Search Bar Section -->
            <!-- <div class="d-flex justify-content-between mb-4">
                <div class="d-flex flex-grow-1 gap-2">
                    <input type="date" id="sold_searchDate" class="form-control" placeholder="Search by date">
                    <button class="btn btn-primary btn-custom" onclick="searchByDate()">
                        <i class="bi bi-search me-2"></i>Search
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-custom" onclick="goToPreviousPage()">
                        <i class="bi bi-chevron-left me-2"></i>Previous
                    </button>
                    <button class="btn btn-outline-secondary btn-custom" onclick="goToNextPage()"> Next<i
                            class="bi bi-chevron-right ms-2"></i>
                    </button>
                </div>
            </div> -->


            <!-- js -->
            <!-- <style>
                .btn-custom {
                    width: 20%;
                    min-width: 120px;
                    /* Ensures readability on smaller screens */
                }

                /* Adds padding and a subtle shadow for a more modern look */
                .btn-custom {
                    padding: 10px 0;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s, box-shadow 0.3s;
                }

                /* Hover effect for buttons */
                .btn-custom:hover {
                    background-color: #007bff !important;
                    color: white !important;
                    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
                }

                .form-control {
                    max-width: 60%;
                }
            </style> -->


            <!-- Tab Styling -->
            <style>
                /* .tab {
                    float: left;
                    border: 1px solid #ccc;
                    background-color: #f1f1f1;
                    width: 30%;
                    height: 300px;
                }

                .tab button {
                    display: block;
                    background-color: inherit;
                    color: black;
                    padding: 22px 16px;
                    width: 100%;
                    border: none;
                    outline: none;
                    text-align: left;
                    cursor: pointer;
                    transition: 0.3s;
                    font-size: 17px;
                } */

                /* .tab button:hover {
                    background-color: #ddd;
                }

                .tab button.active {
                    background-color: #ccc;
                }

                .tabcontent {
                    float: left;
                    padding: 0px 12px;
                    border: 1px solid #ccc;
                    width: 70%;
                    border-left: none;
                    height: 300px;
                    display: none;
                }

                .tabcontent.active {
                    display: block;
                } */
            </style>


<!-- Tabs buttons of sold item  Sections -->
            <!-- <div class="tab">
                <button class="tablinks" onclick="openTab(event, 'sold_SoftBeverage')" id="sold_defaultOpen">Soft
                    Beverage</button>
                <button class="tablinks" onclick="openTab(event, 'sold_AlcoholBeverage')">Alcohol Beverage</button>
                <button class="tablinks" onclick="openTab(event, 'sold_BothBeverage')">Both Beverages</button>
            </div> -->


<!-- Report Table Section for Soft Beverage -->
            <!-- <div id="sold_SoftBeverage" class="tabcontent">
                <div class="table-responsive">
                    <table class="table" style="width:100%; margin-bottom:30px;">
                        <thead>
                            <tr>
                                <th>Beverage Name</th>
                                <th>Beverage Type</th>
                                <th>Measurement</th>
                                <th>Quantity</th>
                                <th>Date Added</th>
                                <th>Added By</th>
                            </tr>
                        </thead>
                        <tbody id="sold_softBeverageTableBody"></tbody>
                    </table>
                </div>
            </div> -->
            <!-- this is searching sold soft beverage reports -->


            <!-- this is about sold bevereage report -->
            <!-- <div id="sold_AlcoholBeverage" class="tabcontent">
                <div class="table-responsive">
                    <table class="table" style="width:100%; margin-bottom:30px;">
                        <thead>
                            <tr>
                                <th>Beverage Name</th>
                                <th>Beverage Type</th>
                                <th>Measurement</th>
                                <th>Quantity</th>
                                <th>Date Added</th>
                                <th>Added By</th>
                            </tr>
                        </thead>
                        <tbody id="sold_alcoholBeverageTableBody"></tbody>
                    </table>
                </div>
            </div> -->
            <!-- i need js around here -->


            <!-- this is table to display both beverge sold report  -->
            <!-- <div id="sold_BothBeverage" class="tabcontent">
                <div class="table-responsive">
                    <table class="table" style="width:100%; margin-bottom:30px;">
                        <thead>
                            <tr>
                                <th>Beverage Name</th>
                                <th>Beverage Type</th>
                                <th>Measurement</th>
                                <th>Quantity</th>
                                <th>Date Added</th>
                                <th>Added By</th>
                            </tr>
                        </thead>
                        <tbody id="sold_bothBeverageTableBody"></tbody>
                    </table>
                </div>
            </div> -->
            <!-- i need js to display the report arouund here ! -->



            <!-- JavaScript for Tabs and Search -->
            <!-- <script>
                // Function to open selected tab and add 'active' class to the button
                function openTab(evt, tabName) {
                    const tabcontent = document.getElementsByClassName("tabcontent");
                    for (let i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }

                    const tablinks = document.getElementsByClassName("tablinks");
                    for (let i = 0; i < tablinks.length; i++) {
                        tablinks[i].classList.remove("active");
                    }

                    document.getElementById(tabName).style.display = "block";
                    evt.currentTarget.classList.add("active");
                }

                // Click on default tab to open it
                document.getElementById("sold_defaultOpen").click();

                // Placeholder functions for search and pagination
                function searchByDate() {
                    const searchDate = document.getElementById("sold_searchDate").value;
                    alert("this is search on sold item value")
                }

                function goToPreviousPage() {
                    alert("this is on constraction")
                }

                function goToNextPage() {
                    alert("this is on constraction");
                }
            </script> -->
        <!-- </div>
    </section> -->






   




    <!-- Write Reports Section -->
    <section id="new_inserted_WriteReports" class="container-custom hidden">
        <div class="container">
            <button onclick="goBack('new_inserted_mainContainer')" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <form action="submit_report.php" method="post">
                <div class="row mb-3">
                    <div class="col-md-4 col-12 d-flex align-items-center mb-3 mb-md-0">
                        <label for="new_inserted_report_provider" class="me-2">Report Provider:</label>
                        <input type="text" class="form-control" name="report_provider" id="new_inserted_report_provider"
                            value="<?php echo htmlspecialchars($report_provider_name); ?>" readonly required>
                    </div>
                    <div class="col-md-3 col-12 mb-3 mb-md-0">
                        <select name="report_about" id="new_inserted_report_about" class="form-control" required>
                            <option value="">Select Report About</option>
                            <option value="room">Room Report</option>
                            <option value="halls">Halls Report</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-12 mb-3 mb-md-0">
                        <select name="report_type" id="new_inserted_report_type" class="form-control" required>
                            <option value="">Select Report Type</option>
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12 d-flex align-items-center">
                        <label for="new_inserted_reported_date" class="me-2">Reported Date:</label>
                        <input type="date" class="form-control" name="reported_date" id="new_inserted_reported_date"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <!-- Table for Report Items -->
                <div class="table-responsive">
                    <table id="new_inserted_reportTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>List</th>
                                <th>Measurement</th>
                                <th>Quantity</th>
                                <th>Single Price</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" onclick="addRow()">Add Row</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Styles for Responsiveness -->
    <style>
        .container-custom {
            padding: 20px;
        }

        .btn-custom {
            min-width: 120px;
        }

        /* Ensure form elements are responsive */
        .form-control,
        select {
            width: 100%;
        }

        /* Add spacing to buttons */
        .btn {
            padding: 10px 15px;
        }

        /* Responsive table handling */
        .table-responsive {
            margin-bottom: 20px;
        }

        /* Ensure no overflow on mobile for long content */
        table th,
        table td {
            word-wrap: break-word;
        }

        /* Small adjustments for tablet/mobile screens */
        @media (max-width: 768px) {

            .form-control,
            select {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .container-custom {
                padding: 10px;
            }

            .d-flex {
                flex-direction: column;
            }

            .mb-3 {
                margin-bottom: 15px;
            }
        }
    </style>


    <!-- this is footer imported from other assets -->
    <?php include '../assets/footer.php'; ?>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('reported_date').value = today;
        });

        let rowCount = 0;

        function addRow() {
            rowCount++;
            const tableBody = document.querySelector('#reportTable tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="list[]" class="form-control" required></td>
                <td><input type="text" name="measurement[]" class="form-control" required></td>
                <td><input type="number" name="quantity[]" class="form-control" step="any" required></td>
                <td><input type="number" name="single_price[]" class="form-control" step="any" required></td>
                <td><input type="number" name="total_price[]" class="form-control" step="any" readonly></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            `;
            tableBody.appendChild(newRow);

            // Add event listener to update total price when quantity or single price changes
            newRow.querySelector('input[name="quantity[]"]').addEventListener('input', updateTotalPrice);
            newRow.querySelector('input[name="single_price[]"]').addEventListener('input', updateTotalPrice);
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#reportTable tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
            rowCount = rows.length;
        }

        function updateTotalPrice(event) {
            const row = event.target.closest('tr');
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const singlePrice = parseFloat(row.querySelector('input[name="single_price[]"]').value) || 0;
            const totalPrice = quantity * singlePrice;
            row.querySelector('input[name="total_price[]"]').value = totalPrice.toFixed(2);
        }

        function validateForm() {
            const rows = document.querySelectorAll('#reportTable tbody tr');
            let isValid = true;
            let errorMessages = [];

            rows.forEach((row, index) => {
                const list = row.querySelector('input[name="list[]"]').value.trim();
                const measurement = row.querySelector('input[name="measurement[]"]').value.trim();
                const quantity = row.querySelector('input[name="quantity[]"]').value.trim();
                const singlePrice = row.querySelector('input[name="single_price[]"]').value.trim();
                const totalPrice = row.querySelector('input[name="total_price[]"]').value.trim();

                let missingColumns = [];

                if (!list) missingColumns.push('List');
                if (!measurement) missingColumns.push('Measurement');
                if (!quantity) missingColumns.push('Quantity');
                if (!singlePrice) missingColumns.push('Single Price');
                if (!totalPrice) missingColumns.push('Total Price');

                if (missingColumns.length > 0) {
                    isValid = false;
                    errorMessages.push(`Row ${index + 1}: Missing ${missingColumns.join(', ')}`);
                }
            });

            if (!isValid) {
                alert('Please fill out all required fields:\n' + errorMessages.join('\n'));
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }

        // Attach validateForm function to form's submit event
        document.querySelector('form').addEventListener('submit', function (event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        function showSection(id) {
            document.querySelectorAll('section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(id).classList.remove('hidden');
            document.getElementById('mainContainer').classList.add('hidden');
        }

        function goBack() {
            document.querySelectorAll('section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById('mainContainer').classList.remove('hidden');
        }
    </script>


    <script>
        // Get the current date
        let currentDate = new Date();
        const nextDayButton = document.getElementById('nextDayButton');
        // Function to search for beverages
        function searchBeverage() {
            let searchValue = document.getElementById('searchInput').value;

            fetch('fetch_beverages.php')
                .then(response => response.json())
                .then(data => {
                    let filteredData = data.filter(item =>
                        item.beverage_name.toLowerCase().includes(searchValue.toLowerCase()) ||
                        item.added_at.includes(searchValue)
                    );
                    populateTable(filteredData);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Function to format date as YYYY-MM-DD
        function formatDate(date) {
            let year = date.getFullYear();
            let month = (date.getMonth() + 1).toString().padStart(2, '0');
            let day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Function to load data for the previous day
        function loadPreviousDay() {
            currentDate.setDate(currentDate.getDate() - 1);
            fetchDataForDate(currentDate);
            nextDayButton.disabled = false; // Enable "Next Day" button
        }

        // Function to load data for the next day
        function loadNextDay() {
            currentDate.setDate(currentDate.getDate() + 1);
            let today = new Date();
            if (formatDate(currentDate) >= formatDate(today)) {
                currentDate = today;
                nextDayButton.disabled = true; // Disable "Next Day" button if today is reached
            }
            fetchDataForDate(currentDate);
        }

        // Function to fetch and display data for a specific date
        function fetchDataForDate(date) {
            let formattedDate = formatDate(date);
            console.log("Fetching data for:", formattedDate);
            fetch('fetch_beverages.php')
                .then(response => response.json())
                .then(data => {
                    let filteredData = data.filter(item => item.added_at.includes(formattedDate));
                    if (filteredData.length === 0 && date < new Date()) {
                        // If no data, go to the previous day
                        loadPreviousDay();
                    } else {
                        populateTable(filteredData);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Function to populate the table with data
        function populateTable(data) {
            const tableBody = document.getElementById('beverageTableBody');
            tableBody.innerHTML = ''; // Clear existing rows
            data.forEach(item => {
                let row = `<tr>
                <td>${item.beverage_name}</td>
                <td>${item.beverage_type}</td>
                <td>${item.measurement}</td>
                <td>${item.beverage_quantity}</td>
                <td>${item.added_at}</td>
                <td>${item.added_by}</td>
            </tr>`;
                tableBody.innerHTML += row;
            });
        }
        // Initialize by loading today's data or the most recent available data
        fetchDataForDate(currentDate);
    </script>
<!-- Include the shared footer scripts -->
<?php include 'asset/footer_scripts.php'; ?>
</body>

</html>