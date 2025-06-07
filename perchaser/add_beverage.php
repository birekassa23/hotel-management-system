<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Beverages</title>
    <?php include 'asset/bootstrap_links.php'; ?>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f7f9fc;
        }

        h2 {
            color: #4a69bd;
        }

        .table th {
            background-color: #000000;
            color: white;
        }

        .table td input {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .btn-primary {
            background-color: #4a69bd;
            border-color: #4a69bd;
        }

        .btn-primary:hover {
            background-color: #3b5998;
        }

        .btn-danger {
            background-color: #e55039;
            border-color: #e55039;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-success {
            background-color: #38ada9;
            border-color: #38ada9;
        }

        .btn-success:hover {
            background-color: #218c74;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <?php include 'asset/nav-bar.php'; ?>
        <div class="container mt-5">
            <div class="card shadow-sm p-4">
                <h2 class="text-center mb-4">Add New Beverages</h2>
                <form action="instock_process.php" method="post">
                    <table class="table table-bordered" id="beverageTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" onclick="addRow()">+ Add Row</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'asset/footer.php'; ?>

    <script>
        let rowCount = 0;

        function addRow() {
            rowCount++;
            const tableBody = document.querySelector('#beverageTable tbody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="item_name[]" class="form-control" placeholder="Item Name" required></td>
                <td><input type="text" name="category[]" class="form-control" placeholder="Category" required></td>
                <td><input type="number" name="quantity[]" class="form-control" placeholder="Quantity" step="any" required></td>
                <td><input type="number" name="purchase_price[]" class="form-control" placeholder="Purchase Price" step="any" required></td>
                <td><input type="number" name="price[]" class="form-control" placeholder="Selling Price" step="any" required></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
            `;
            tableBody.appendChild(newRow);
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#beverageTable tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
            rowCount = rows.length;
        }
    </script>
</body>

</html>
