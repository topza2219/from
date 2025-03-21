<?php
// backup_db.php

// Database configuration
$host = 'localhost'; // Database host
$db_name = 'borrow_system'; // Database name
$username = 'root'; // Database username
$password = 'root'; // Database password

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Specify the backup file path
$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';

// Command to create a backup
$command = "mysqldump --opt -h $host -u $username -p$password $db_name > $backup_file";

// Execute the command
system($command, $output);

// Check if the backup was successful
if ($output === 0) {
    echo json_encode(["status" => "success", "message" => "Database backup created successfully.", "file" => $backup_file]);
} else {
    echo json_encode(["status" => "error", "message" => "Database backup failed."]);
}

// Close the database connection
$conn->close();
?>