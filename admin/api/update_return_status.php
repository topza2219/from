<?php
// update_return_status.php

header('Content-Type: application/json');

// Include database connection
include_once '../../config/database.php';

// Get the database connection
$database = new Database();
$db = $database->getConnection();

// Get the posted data
$data = json_decode(file_get_contents("php://input"));

// Check if the required fields are present
if(isset($data->return_id) && isset($data->status)) {
    $return_id = $data->return_id;
    $status = $data->status;

    // Update the return status in the database
    $query = "UPDATE returns SET status = :status WHERE id = :return_id";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':return_id', $return_id);

    // Execute the query
    if($stmt->execute()) {
        echo json_encode(array("message" => "Return status updated successfully."));
    } else {
        echo json_encode(array("message" => "Unable to update return status."));
    }
} else {
    echo json_encode(array("message" => "Invalid input."));
}
?>