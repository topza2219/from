<?php
// This file generates reports for returned items.

header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'borrow_system';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get returned items report
    $stmt = $pdo->prepare("SELECT r.id, r.item_name, r.return_date, u.name AS borrower_name 
                            FROM returns r 
                            JOIN users u ON r.borrower_id = u.id 
                            WHERE r.status = 'returned'");
    $stmt->execute();

    $returnedItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $returnedItems
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>