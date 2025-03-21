<?php
// confirm_receipt.php

// Start session
session_start();

// Include database connection
include_once '../config/db.php';

// Check if the user is logged in and has the right permissions
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receiver') {
    header("Location: ../login.php");
    exit();
}

// Get the receipt ID from the request
if (isset($_POST['receipt_id'])) {
    $receipt_id = $_POST['receipt_id'];

    // Prepare the SQL statement to confirm the receipt
    $stmt = $conn->prepare("UPDATE receipts SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $receipt_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the pending receipts page with a success message
        $_SESSION['message'] = "Receipt confirmed successfully.";
        header("Location: pending_receipts.php");
    } else {
        // Redirect to the pending receipts page with an error message
        $_SESSION['message'] = "Error confirming receipt. Please try again.";
        header("Location: pending_receipts.php");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the pending receipts page if no receipt ID is provided
    header("Location: pending_receipts.php");
}

// Close the database connection
$conn->close();
?>