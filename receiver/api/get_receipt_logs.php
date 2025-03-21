<?php
// Database connection
include_once '../../config.php';

// Function to get receipt logs
function getReceiptLogs($conn) {
    $sql = "SELECT * FROM receipt_logs ORDER BY timestamp DESC";
    $result = $conn->query($sql);

    $logs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
    }
    return $logs;
}

// Fetch logs
$logs = getReceiptLogs($conn);

// Return logs as JSON
header('Content-Type: application/json');
echo json_encode($logs);
?>