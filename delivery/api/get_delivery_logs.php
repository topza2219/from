<?php
// Database connection
$host = 'localhost';
$db_name = 'borrow_system';
$username = 'root';
$password = 'root';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch delivery logs
    $stmt = $conn->prepare("SELECT * FROM delivery_logs ORDER BY delivery_date DESC");
    $stmt->execute();

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return logs as JSON
    header('Content-Type: application/json');
    echo json_encode($logs);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>