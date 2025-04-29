<?php
// get_comments.php

header('Content-Type: application/json');

//include database connection
include '../assets/conn.php';

// Query to select all columns from the comments table
$sql = "SELECT fromUserName, Date, theComment FROM comment";
$result = $conn->query($sql);

$comments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $comments[] = [
            'fromUserName' => $row['fromUserName'],
            'Date' => $row['Date'],
            'theComment' => $row['theComment']
        ];
    }
}

$conn->close();

// Return the comments as JSON
echo json_encode($comments);
?>
