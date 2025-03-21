<?php
// restore_db.php

// Include database connection
include_once '../../config/database.php';

// Function to restore the database
function restoreDatabase($backupFile) {
    global $conn;

    // Check if the backup file exists
    if (!file_exists($backupFile)) {
        http_response_code(404);
        echo json_encode(array("message" => "Backup file not found."));
        return;
    }

    // Execute the SQL commands from the backup file
    $sql = file_get_contents($backupFile);
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        http_response_code(200);
        echo json_encode(array("message" => "Database restored successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Error restoring database: " . $conn->error));
    }
}

// Get the backup file path from the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $backupFile = $_POST['backup_file'];

    // Call the restore function
    restoreDatabase($backupFile);
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>