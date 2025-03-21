<?php
// Include database connection
include_once '../../config/database.php';

// Get user ID from request
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

// Prepare the SQL query to fetch return requests by user
$query = "SELECT * FROM return_requests WHERE user_id = :user_id";
$stmt = $database->prepare($query);
$stmt->bindParam(':user_id', $user_id);

// Execute the query
if($stmt->execute()){
    $return_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($return_requests);
} else {
    echo json_encode(array("message" => "Unable to fetch return requests."));
}
?>