<?php
// reject_return.php

header('Content-Type: application/json');

// Include database connection
include_once '../../config/database.php';

// Get the input data
$data = json_decode(file_get_contents("php://input"));

// Check if the required data is provided
if(isset($data->return_id) && isset($data->reason)) {
    $return_id = $data->return_id;
    $reason = $data->reason;

    // Create a new database connection
    $database = new Database();
    $db = $database->getConnection();

    // Prepare the SQL statement
    $query = "UPDATE returns SET status = 'rejected', rejection_reason = :reason WHERE id = :return_id";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(':return_id', $return_id);
    $stmt->bindParam(':reason', $reason);

    // Execute the query
    if($stmt->execute()) {
        echo json_encode(array("message" => "Return request rejected successfully."));
    } else {
        echo json_encode(array("message" => "Unable to reject the return request."));
    }
} else {
    echo json_encode(array("message" => "Invalid input data."));
}
?>