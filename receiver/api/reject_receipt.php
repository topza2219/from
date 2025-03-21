<?php
// reject_receipt.php

// Include database connection
include '../../config/db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the receipt ID and rejection reason from the request
    $receipt_id = $_POST['receipt_id'];
    $reason = $_POST['reason'];

    // Validate input
    if (empty($receipt_id) || empty($reason)) {
        echo json_encode(['status' => 'error', 'message' => 'Receipt ID and reason are required.']);
        exit;
    }

    // Prepare SQL statement to reject the receipt
    $stmt = $conn->prepare("UPDATE receipts SET status = 'rejected', rejection_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $receipt_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Receipt rejected successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to reject the receipt.']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>