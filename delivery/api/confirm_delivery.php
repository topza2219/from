<?php
// confirm_delivery.php

header('Content-Type: application/json');

// Include database connection
include_once '../../config/database.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Get delivery ID from request
$data = json_decode(file_get_contents("php://input"));
$delivery_id = isset($data->delivery_id) ? $data->delivery_id : die(json_encode(["message" => "Delivery ID is required."]));

// Update delivery status in the database
$query = "UPDATE deliveries SET status = 'confirmed' WHERE id = :delivery_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':delivery_id', $delivery_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Delivery confirmed successfully."]);
} else {
    echo json_encode(["message" => "Unable to confirm delivery."]);
}
?>