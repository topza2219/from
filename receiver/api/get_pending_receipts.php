<?php
// Database connection
include_once '../../config.php';

// Function to get pending receipts
function getPendingReceipts($conn) {
    $sql = "SELECT * FROM receipts WHERE status = 'pending'";
    $result = $conn->query($sql);

    $receipts = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $receipts[] = $row;
        }
    }
    return $receipts;
}

// Fetch pending receipts
$pendingReceipts = getPendingReceipts($conn);

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($pendingReceipts);
?>