<?php
//include database connection
include '../assets/conn.php';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the date from the query string
    $date = $_GET['date'] ?? null;

    // Prepare the SQL statement
    if ($date) {
        $stmt = $pdo->prepare("SELECT report_provider, report_type, item_name, measurement, quantity, single_price, total_price, reported_date FROM wechi WHERE reported_date = :reported_date");
        $stmt->bindParam(':reported_date', $date);
    } else {
        // If no date is provided, you can decide to fetch all or return an empty array
        echo json_encode([]);
        exit();
    }

    // Execute the statement
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($reports);
} catch (Exception $e) {
    // Return error message as JSON
    echo json_encode(["error" => "Failed to fetch reports: " . $e->getMessage()]);
} finally {
    // Close the connection
    $pdo = null;
}
?>
