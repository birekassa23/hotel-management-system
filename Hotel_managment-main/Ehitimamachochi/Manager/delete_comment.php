<?php
header('Content-Type: application/json');
$response = ['success' => false, 'error' => '', 'message' => ''];

//include database connection
include '../assets/conn.php';
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fromUserName = $_POST['from_user_name'];
    $theComment = $_POST['the_comment'];
    $commentDate = $_POST['comment_date'];

    $stmt = $pdo->prepare("DELETE FROM comment WHERE fromUserName = :fromUserName AND theComment = :theComment AND Date = :commentDate");
    $stmt->execute([
        ':fromUserName' => $fromUserName,
        ':theComment' => $theComment,
        ':commentDate' => $commentDate
    ]);

    $response['success'] = true;
    $response['message'] = 'Comment deleted successfully.';
} catch (PDOException $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>
