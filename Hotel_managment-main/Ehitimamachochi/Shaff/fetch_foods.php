<?php
header('Content-Type: application/json');
//include database connection
include '../assets/conn.php';

$category = $_GET['category'] ?? '';

if ($category) {
    $stmt = $pdo->prepare("SELECT item_name FROM table_foods WHERE category = ?");
    $stmt->execute([$category]);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($foods);
} else {
    echo json_encode([]);
}
?>
