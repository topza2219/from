<?php
// confirm_return_rejection.php

// Include database connection
include_once '../../config/database.php';

// Get the input data
$data = json_decode(file_get_contents("php://input"));

// Check if the required data is provided
if(isset($data->return_id) && isset($data->user_id)) {
    $return_id = $data->return_id;
    $user_id = $data->user_id;

    // Prepare the SQL statement to confirm return rejection
    $query = "UPDATE returns SET status = 'rejected' WHERE id = :return_id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':return_id', $return_id);
    $stmt->bindParam(':user_id', $user_id);

    // Execute the query
    if($stmt->execute()) {
        echo json_encode(array("message" => "Return rejection confirmed."));
    } else {
        echo json_encode(array("message" => "Unable to confirm return rejection."));
    }
} else {
    echo json_encode(array("message" => "Invalid input data."));
}
?>