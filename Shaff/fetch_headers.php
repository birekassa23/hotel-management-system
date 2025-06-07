<?php
header('Content-Type: application/json');
//include database connection
include '../assets/conn.php';

// Fetch all unique reason values from the 'wechi' table
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetchHeaders'])) {
    try {
        $stmt = $pdo->prepare("SELECT DISTINCT item_name FROM wechi WHERE report_type = 'other_expenditure'");
        $stmt->execute();
        $headers = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($headers);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to fetch headers: " . $e->getMessage()]);
    }
    exit();
}
?>
