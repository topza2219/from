<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "username"; // replace with your database username
$password = "password"; // replace with your database password
$dbname = "borrow_system"; // replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch receivers
$sql = "SELECT * FROM receivers"; // Assuming you have a table named 'receivers'
$result = $conn->query($sql);

$receivers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $receivers[] = $row;
    }
}

// Return JSON response
echo json_encode($receivers);

$conn->close();
?>