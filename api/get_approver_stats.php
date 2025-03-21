<?php
require_once '../../config.php';
function getApproverStats($approver_id) {
    global $conn;

    // Validate approver ID
    if (!is_numeric($approver_id)) {
        error_log('Invalid approver ID: ' . $approver_id);
        return ['error' => 'Invalid approver ID'];
    }

    // Fetch counts for pending, approved, and rejected requests
    $stats_query = "
        SELECT 
            (SELECT COUNT(*) FROM borrow_requests WHERE status = 'pending') AS pending_count,
            (SELECT COUNT(*) FROM borrow_requests WHERE status = 'approved') AS approved_count,
            (SELECT COUNT(*) FROM borrow_requests WHERE status = 'rejected') AS rejected_count
    ";
    $stats_result = mysqli_query($conn, $stats_query);
    if (!$stats_result) {
        error_log('Error fetching stats: ' . mysqli_error($conn));
        return ['error' => 'Failed to fetch stats'];
    }
    $stats = mysqli_fetch_assoc($stats_result);

    // Fetch recent pending requests
    $recent_requests_query = "
        SELECT br.request_id AS id, u.full_name AS borrower_name, i.item_name, br.created_at AS request_date, br.status
        FROM borrow_requests br
        JOIN users u ON br.borrower_id = u.user_id
        JOIN items i ON br.item_id = i.item_id
        WHERE br.status = 'pending'
        ORDER BY br.created_at DESC
        LIMIT 5
    ";
    $recent_requests_result = mysqli_query($conn, $recent_requests_query);
    if (!$recent_requests_result) {
        error_log('Error fetching recent requests: ' . mysqli_error($conn));
        return ['error' => 'Failed to fetch recent requests'];
    }
    $recent_requests = mysqli_fetch_all($recent_requests_result, MYSQLI_ASSOC);

    // Ensure default values if no data is found
    if (!$stats) {
        $stats = [
            'pending_count' => 0,
            'approved_count' => 0,
            'rejected_count' => 0,
            'recent_requests' => []
        ];
    }

    $stats['recent_requests'] = $recent_requests;

    return $stats;
}

?>