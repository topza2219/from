<?php
// Include database connection
include_once '../../config/database.php';

// Create a database connection
$database = new Database();
$db = $database->getConnection();

// Prepare the SQL query to get return summary
$query = "SELECT 
            COUNT(*) AS total_returns,
            SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) AS successful_returns,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_returns,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected_returns
          FROM returns";

// Execute the query
$stmt = $db->prepare($query);
$stmt->execute();

// Fetch the result
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the summary as JSON
header('Content-Type: application/json');
echo json_encode($row);
?>