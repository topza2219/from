<?php
// get_return_details.php

header('Content-Type: application/json');

// Database connection
$host = 'localhost'; // Database host
$dbname = 'borrow_system'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get return ID from request
$return_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($return_id > 0) {
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT * FROM returns WHERE id = :id");
    $stmt->bindParam(':id', $return_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $return_details = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($return_details) {
            echo json_encode($return_details);
        } else {
            echo json_encode(['error' => 'Return not found.']);
        }
    } else {
        echo json_encode(['error' => 'Query execution failed.']);
    }
} else {
    echo json_encode(['error' => 'Invalid return ID.']);
}
?>