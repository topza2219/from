<?php
function getBorrowerNotifications($user_id) {
    global $conn;
    
    $counts = [
        'pending' => 0,
        'approved' => 0,
        'rejected' => 0,
        'return' => 0
    ];
    
    // Count pending requests
    $sql = "SELECT COUNT(*) as count FROM borrow_requests 
            WHERE borrower_id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $counts['pending'] = mysqli_fetch_assoc($result)['count'];

    // Count approved requests
    $sql = "SELECT COUNT(*) as count FROM borrow_requests 
            WHERE borrower_id = ? AND status = 'approved' AND is_read = 0";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $counts['approved'] = mysqli_fetch_assoc($result)['count'];

    // Count rejected requests
    $sql = "SELECT COUNT(*) as count FROM borrow_requests 
            WHERE borrower_id = ? AND status = 'rejected' AND is_read = 0";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $counts['rejected'] = mysqli_fetch_assoc($result)['count'];

    // Count items to return
    $sql = "SELECT COUNT(*) as count FROM borrow_requests 
            WHERE borrower_id = ? AND status = 'borrowed' 
            AND expected_return_date <= CURDATE()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $counts['return'] = mysqli_fetch_assoc($result)['count'];

    return $counts;
}