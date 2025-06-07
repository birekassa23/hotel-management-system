<?php
//include database connection
include '../assets/conn.php';
// Handle AJAX requests for item names and details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['category']) && isset($_POST['type'])) {
        $category = $_POST['category'];
        $type = $_POST['type'];

        $sql = "SELECT item_name AS value, item_name AS text FROM " . $category . " WHERE item_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        echo json_encode($items);
        exit;
    }

    if (isset($_POST['item_name'])) {
        $item_name = $_POST['item_name'];

        // Assume 'foods' and 'beverages' are your table names
        $sql = "
            SELECT quantity, price FROM foods WHERE item_name = ? 
            UNION ALL 
            SELECT quantity, price FROM beverages WHERE item_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $item_name, $item_name);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = $result->fetch_assoc();
        echo json_encode($data);
        exit;
    }
}
?>
