<?php
//include database connection
include '../assets/conn.php';

// Get the search term from the request
$searchTerm = $_GET['emp_id'];

// Prepare the SQL statement
$sql = $conn->prepare("SELECT id, f_name, l_name, sex, email, phone_no, position,is_present FROM employees WHERE id LIKE ?");
$searchTerm = "%$searchTerm%";
$sql->bind_param("s", $searchTerm);
$sql->execute();
$result = $sql->get_result();

// Fetch results as an associative array
$employees = array();
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

// Return results as JSON
echo json_encode($employees);

$conn->close();
?>
