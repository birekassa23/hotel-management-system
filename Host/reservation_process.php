<?php
//include database connection
include '../assets/conn.php';

try {
    // Create connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle reservation request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $item_name = $input['item_name'];
        $quantity = $input['quantity'];
        $price = $input['price'];
        $total_price = $price * $quantity;
        $reported_date = $input['reported_date']; // Provided reported_date from JavaScript

        try {
            // Check if the item is already reported
            $stmt = $pdo->prepare("SELECT * FROM host_transaction WHERE item_name = ? AND reported_date = ?");
            $stmt->execute([$item_name, $reported_date]);

            if ($stmt->rowCount() > 0) {
                // Item is already reported, update the quantity
                $stmt = $pdo->prepare("UPDATE host_transaction SET item_quantity = item_quantity + ?, Total_price = Total_price + ? WHERE item_name = ? AND reported_date = ?");
                $stmt->execute([$quantity, $total_price, $item_name, $reported_date]);
            } else {
                // Item is not reported, insert a new record
                $stmt = $pdo->prepare("INSERT INTO host_transaction (host_name, item_type, item_name, item_quantity, item_price, Total_price, reported_date) VALUES (?, 'food', ?, ?, ?, ?, ?)");
                $stmt->execute(['myhost', $item_name, $quantity, $price, $total_price, $reported_date]);
            }

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
