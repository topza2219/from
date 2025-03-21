<?php

// functions.php

// Function to establish a database connection
function getDbConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "borrow_system";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// functions.php

// Function to fetch analytics data
function fetchAnalyticsData() {
    $conn = getDbConnection(); // Get the database connection

    // Example query to get borrowing statistics
    $query = "SELECT COUNT(*) as total_borrows, 
                     AVG(TIMESTAMPDIFF(DAY, borrow_date, return_date)) as average_duration 
              FROM borrow_history 
              WHERE borrow_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    
    $result = $conn->query($query);
    $data = $result->fetch_assoc();

    // Check if average_duration is NULL and set it to 0 if true
    if (is_null($data['average_duration'])) {
        $data['average_duration'] = 0;
    }

    $conn->close(); // Close the database connection

    return $data;
}


?>