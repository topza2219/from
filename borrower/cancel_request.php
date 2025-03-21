<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Validate request ID
    $request_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$request_id) {
        throw new Exception('รหัสคำขอไม่ถูกต้อง');
    }

    // Check if request exists and belongs to current user
    $check_query = "SELECT br.*, i.item_name, i.quantity as available_quantity 
                   FROM borrow_requests br
                   LEFT JOIN items i ON br.item_id = i.item_id
                   WHERE br.request_id = ? 
                   AND br.borrower_id = ? 
                   AND br.status = 'pending'
                   LIMIT 1";

    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, 'ii', $request_id, $_SESSION['user_id']);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $request = mysqli_fetch_assoc($result);

    if (!$request) {
        throw new Exception('ไม่พบคำขอที่ต้องการยกเลิก หรือไม่มีสิทธิ์ในการยกเลิก');
    }

    // Update request status
    $update_request = "UPDATE borrow_requests 
                      SET status = 'cancelled',
                          updated_at = NOW(),
                          cancel_reason = 'ยกเลิกโดยผู้ขอ'
                      WHERE request_id = ?";

    $update_stmt = mysqli_prepare($conn, $update_request);
    mysqli_stmt_bind_param($update_stmt, 'i', $request_id);
    
    if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception('ไม่สามารถอัพเดทสถานะคำขอได้');
    }

    // Return item quantity
    $update_item = "UPDATE items 
                   SET quantity = quantity + ?,
                       updated_at = NOW()
                   WHERE item_id = ?";

    $update_item_stmt = mysqli_prepare($conn, $update_item);
    mysqli_stmt_bind_param($update_item_stmt, 'ii', $request['quantity'], $request['item_id']);
    
    if (!mysqli_stmt_execute($update_item_stmt)) {
        throw new Exception('ไม่สามารถอัพเดทจำนวนอุปกรณ์ได้');
    }

    // Log the cancellation
    $log_query = "INSERT INTO request_logs 
                  (request_id, action, action_by, action_at, notes)
                  VALUES (?, 'cancelled', ?, NOW(), ?)";
    
    $log_stmt = mysqli_prepare($conn, $log_query);
    $notes = "ยกเลิกการยืม {$request['item_name']} จำนวน {$request['quantity']} ชิ้น";
    mysqli_stmt_bind_param($log_stmt, 'iis', $request_id, $_SESSION['user_id'], $notes);
    mysqli_stmt_execute($log_stmt);

    // Commit transaction
    mysqli_commit($conn);

    $_SESSION['success'] = "ยกเลิกคำขอยืมเรียบร้อยแล้ว";
    header('Location: pending_requests.php');
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    
    error_log("Cancel request error: " . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    header('Location: pending_requests.php');
    exit();
}