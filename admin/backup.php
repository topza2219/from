<?php
// backup.php

// Start session
session_start();

// Include database connection
include_once '../../config.php';

// Function to backup the database
function backupDatabase($conn) {
    $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --opt -h " . DB_HOST . " -u " . DB_USER . " -p" . DB_PASS . " " . DB_NAME . " > " . $backupFile;

    system($command, $output);

    if ($output === 0) {
        return $backupFile;
    } else {
        return false;
    }
}

// Check if the user is an admin
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

// Handle backup request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $backupFile = backupDatabase($conn);
    
    if ($backupFile) {
        echo "Backup successful! File created: " . $backupFile;
    } else {
        echo "Backup failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Database</title>
</head>
<body>
    <h1>Backup Database</h1>
    <form method="POST">
        <button type="submit">Backup Now</button>
    </form>
</body>
</html>