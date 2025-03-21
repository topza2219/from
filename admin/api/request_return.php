<?php
// request_return.php

header('Content-Type: application/json');
include_once '../db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrower_id = $_POST['borrower_id'];
    $item_id = $_POST['item_id'];
    $return_date = $_POST['return_date'];

    if (empty($borrower_id) || empty($item_id) || empty($return_date)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Prepare SQL statement to insert return request
    $stmt = $conn->prepare("INSERT INTO return_requests (borrower_id, item_id, return_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $borrower_id, $item_id, $return_date);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Return request submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit return request.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>