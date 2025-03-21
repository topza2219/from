<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

try {
    // Validate input
    $request_id = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
    $receipt_notes = filter_input(INPUT_POST, 'receipt_notes', FILTER_SANITIZE_STRING);

    if (!$request_id) {
        throw new Exception('ไม่พบรหัสคำขอ');
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    // Update request status
    $update_query = "UPDATE borrow_requests 
                    SET status = 'borrowed',
                        received_at = NOW(),
                        receipt_notes = ?,
                        updated_at = NOW()
                    WHERE request_id = ? 
                    AND borrower_id = ?
                    AND status = 'approved'";

    $stmt = mysqli_prepare($conn, $update_query);
    if (!$stmt) {
        throw new Exception('Database error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, 'sii', $receipt_notes, $request_id, $_SESSION['user_id']);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('ไม่สามารถอัพเดทสถานะได้');
    }

    if (mysqli_affected_rows($conn) === 0) {
        throw new Exception('ไม่พบคำขอหรือคุณไม่มีสิทธิ์ในการยืนยันการรับ');
    }

    // Log the action
    $log_query = "INSERT INTO request_logs 
                  (request_id, action, action_by, action_at, notes)
                  VALUES (?, 'borrowed', ?, NOW(), ?)";
    
    $log_stmt = mysqli_prepare($conn, $log_query);
    mysqli_stmt_bind_param($log_stmt, 'iis', $request_id, $_SESSION['user_id'], $receipt_notes);
    mysqli_stmt_execute($log_stmt);

    // Commit transaction
    mysqli_commit($conn);

    $_SESSION['success'] = 'ยืนยันการรับอุปกรณ์เรียบร้อยแล้ว กรุณาคืนตามกำหนดเวลา';
    header('Location: return_items.php');
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error'] = $e->getMessage();
    header('Location: approved_requests.php');
    exit();
}