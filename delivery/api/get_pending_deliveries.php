<?php
// Database connection
$host = 'localhost'; // Database host
$db_name = 'borrow_system'; // Database name
$username = 'root'; // Database username
$password = 'root'; // Database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch pending deliveries
    $stmt = $conn->prepare("SELECT * FROM deliveries WHERE status = 'pending'");
    $stmt->execute();

    $pending_deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode($pending_deliveries);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>