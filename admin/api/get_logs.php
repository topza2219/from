<?php
// Include database connection
include_once '../../config/database.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Prepare the query to get logs
$query = "SELECT * FROM logs ORDER BY timestamp DESC";
$stmt = $db->prepare($query);
$stmt->execute();

// Fetch all logs
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return logs as JSON
header('Content-Type: application/json');
echo json_encode($logs);
?>