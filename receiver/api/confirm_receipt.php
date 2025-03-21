<?php
// confirm_receipt.php

// Include database connection
include_once '../../config/database.php';

// Get the receipt ID from the request
$receipt_id = isset($_POST['receipt_id']) ? $_POST['receipt_id'] : die();

// Prepare the SQL statement to update the receipt status
$query = "UPDATE receipts SET status = 'confirmed' WHERE id = :receipt_id";
$stmt = $conn->prepare($query);

// Bind the receipt ID to the statement
$stmt->bindParam(':receipt_id', $receipt_id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(array("message" => "Receipt confirmed successfully."));
} else {
    echo json_encode(array("message" => "Unable to confirm receipt."));
}
?>