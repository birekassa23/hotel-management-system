<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>perchaser Page - Ehototmamachochi Hotel</title>
    <?php include 'asset/bootstrap_links.php'; ?>
    <style>
        .nav-item {
            font-size: 16px;
        }

        .nav-item:hover {
            border-bottom: 1px solid blue;
        }

        /* Additional styling for the report section */
        .report-section {
            margin-top: 20px;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', Times, serif;">

    <div class="d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <?php include 'asset/nav-bar.php'; ?>

        <!-- Write Reports Section -->
        <section id="WriteReports" class="container-fluid report-section">
            <h2 style="text-align:center; margin-bottom :20px;"> የ እቃ መመዝገብያ ቅጽ</h2>
            <form action="instock_pocess.php" method="post">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <label for="report_provider" class="me-2">Your Name:</label>
                        <input type="text" class="form-control" name="report_provider" id="report_provider"
                            value="<?php echo htmlspecialchars($report_provider_name); ?>" readonly required>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="report_type" class="me-2">Item Type:</label>
                        <select name="report_type" id="report_type" required aria-label="Report Type"
                            style="padding: 10px;">
                            <option value="" selected>Select report type</option>
                            <option value="beverages">Beverages</option>
                            <option value="other_expenditure">Other Expenditure</option>
                        </select>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="reported_date" class="me-2">Date:</label>
                        <input type="date" class="form-control" name="reported_date" id="reported_date" value=""
                            required readonly>
                    </div>

                    <script>
                        // Set the input value to the current date in YYYY-MM-DD format
                        document.getElementById('reported_date').value = new Date().toISOString().split('T')[0];
                    </script>
                </div>
                <table id="reportTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
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
                <button type="button" class="btn btn-primary" onclick="addRow()">Add Row</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </section>

         <?php include 'asset/footer.php'; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

            const reportType = document.querySelector('#report_type').value;
            if (!reportType) {
                isValid = false;
                errorMessages.push('Please select a report type.');
            }

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

        function goBack() {
            // Implement the go back functionality
            window.history.back();
        }

        // Call addRow 4 times when the page loads
        window.onload = function () {
            for (let i = 0; i < 4; i++) {
                addRow();
            }
        };
    </script>
</body>

</html>