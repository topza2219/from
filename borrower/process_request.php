<?php
require_once dirname(__FILE__) . '/auth_check.php';
require_once dirname(__FILE__) . '/../config.php';

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Validate form data
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $borrow_date = filter_input(INPUT_POST, 'borrow_date', FILTER_SANITIZE_STRING);
    $return_date = filter_input(INPUT_POST, 'return_date', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

    if (!$item_id || !$quantity || !$borrow_date || !$return_date) {
        throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
    }

    // Check if dates are valid
    if (strtotime($borrow_date) < strtotime(date('Y-m-d'))) {
        throw new Exception('วันที่ยืมต้องไม่เป็นวันที่ผ่านมาแล้ว');
    }

    if (strtotime($return_date) <= strtotime($borrow_date)) {
        throw new Exception('วันที่คืนต้องมากกว่าวันที่ยืม');
    }

    // Check item availability
    $check_query = "SELECT quantity, item_name FROM items WHERE item_id = ? AND status = 'available'";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, 'i', $item_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $item = mysqli_fetch_assoc($result);

    if (!$item) {
        throw new Exception('ไม่พบอุปกรณ์ที่ต้องการยืม');
    }

    if ($quantity > $item['quantity']) {
        throw new Exception("จำนวนที่ขอยืมมากกว่าจำนวนที่มี ({$item['quantity']})");
    }

    // Insert borrow request
    $insert_query = "INSERT INTO borrow_requests (
        borrower_id, item_id, quantity, borrow_date, 
        expected_return_date, notes, status, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param(
        $insert_stmt, 
        'iiisss',
        $_SESSION['user_id'],
        $item_id,
        $quantity,
        $borrow_date,
        $return_date,
        $notes
    );

    if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
    }

    // Update item quantity
    $update_query = "UPDATE items SET 
                    quantity = quantity - ?,
                    updated_at = NOW()
                    WHERE item_id = ?";
    
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, 'ii', $quantity, $item_id);
    
    if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception('เกิดข้อผิดพลาดในการอัพเดทจำนวนอุปกรณ์');
    }

    // Commit transaction
    mysqli_commit($conn);

    $_SESSION['success'] = "ส่งคำขอยืม {$item['item_name']} จำนวน {$quantity} ชิ้นเรียบร้อยแล้ว";
    header('Location: index.php');
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    
    $_SESSION['error'] = $e->getMessage();
    header('Location: new_request.php');
    exit();
}