<?php
require_once __DIR__ . '/../../config.php';

function getBorrowerStats($borrower_id) {
    global $conn;
    
    $stats = [
        'pending' => 0,
        'approved' => 0,
        'rejected' => 0
    ];
    
    $query = "SELECT status, COUNT(*) as count 
              FROM borrow_requests 
              WHERE borrower_id = ? 
              GROUP BY status";
              
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $borrower_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($stats[$row['status']])) {
                $stats[$row['status']] = $row['count'];
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    return $stats;
}