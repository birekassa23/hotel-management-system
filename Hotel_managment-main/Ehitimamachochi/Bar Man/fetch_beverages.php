<?php
// Include database connection
include '../assets/conn.php';

// Fetch data from the database
$query = "SELECT `beverage_name`, `beverage_type`, `measurement`, `beverage_quantity`, `added_by`, `added_at` FROM `beverage_in_bar_man`";
$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return the data as JSON
echo json_encode($data);
?>
