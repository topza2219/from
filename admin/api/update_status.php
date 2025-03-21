<?php
// update_status.php

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // replace with your database username
$password = "root"; // replace with your database password
$dbname = "borrow_system"; // replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get data from request
$data = json_decode(file_get_contents("php://input"), true);
$borrow_id = $data['borrow_id'];
$new_status = $data['status'];

// Update status query
$sql = "UPDATE borrow_requests SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_status, $borrow_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Status updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error updating status: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>