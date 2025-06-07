<?php
header('Content-Type: application/json');
$response = ['success' => false, 'error' => ''];
//include database connection
include '../assets/conn.php';
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the deletion query
    $stmt = $pdo->prepare("DELETE FROM comment");
    $stmt->execute();

    $response['success'] = true;
} catch (PDOException $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>
