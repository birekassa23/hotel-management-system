<?php
// Include database connection
include '../assets/conn.php';

// Fetch data from `table_beverages`
$beverages_query = "SELECT `item_name`, `category`, `quantity`, `purchase_price`, `price`, `created_at` FROM `table_beverages`";
$beverages_result = $conn->query($beverages_query);

// Fetch data from `wechi`
$wechi_query = "SELECT `report_provider`, `report_type`, `item_name`, `measurement`, `quantity`, `single_price`, `total_price`, `reported_date` FROM `wechi`";
$wechi_result = $conn->query($wechi_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Items</title>
    <?php include 'asset/bootstrap_links.php'; ?>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

        .table-container {
            margin-top: 30px;
        }

        .table-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .table-container h3 {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .no-data {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2rem;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <?php include 'asset/nav-bar.php'; ?> <!-- Include Navbar -->

        <div class="container">
            <!-- Beverages Table -->
            <div class="table-container">
                <h3 class="table-title">Beverages</h3>
                <?php if ($beverages_result && $beverages_result->num_rows > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            <?php while ($row = $beverages_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo ++$count; ?></td>
                                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo number_format($row['purchase_price'], 2); ?></td>
                                    <td><?php echo number_format($row['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">No beverages found.</p>
                <?php endif; ?>
            </div>

            <!-- Wechi Table -->
            <div class="table-container">
                <h3 class="table-title">Wechi Reports</h3>
                <?php if ($wechi_result && $wechi_result->num_rows > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Report Provider</th>
                                <th>Report Type</th>
                                <th>Item Name</th>
                                <th>Measurement</th>
                                <th>Quantity</th>
                                <th>Single Price</th>
                                <th>Total Price</th>
                                <th>Reported Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; ?>
                            <?php while ($row = $wechi_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo ++$count; ?></td>
                                    <td><?php echo htmlspecialchars($row['report_provider']); ?></td>
                                    <td><?php echo htmlspecialchars($row['report_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['measurement']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo number_format($row['single_price'], 2); ?></td>
                                    <td><?php echo number_format($row['total_price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['reported_date']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">No reports found.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php include 'asset/footer.php'; ?> <!-- Include footer -->
    </div>
</body>

</html>
