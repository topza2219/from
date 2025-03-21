<?php
session_start();
require_once __DIR__ . '/../access_control.php';
requireAccess('admin');
require_once __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed');
}

function updateReturnStatus($return_id, $status) {
    global $conn;
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Update return status
        $stmt = $conn->prepare("UPDATE returns SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $return_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update return status");
        }
        
        // If approved, update item availability
        if ($status === 'approved') {
            $stmt = $conn->prepare("
                UPDATE items i 
                JOIN returns r ON r.item_id = i.id 
                SET i.status = 'available' 
                WHERE r.id = ?
            ");
            $stmt->bind_param("i", $return_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update item status");
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['success'] = "อัพเดทสถานะการคืนเรียบร้อยแล้ว";
        
    } catch (Exception $e) {
        // Rollback on error
        if ($conn->inTransaction()) {
            $conn->rollback();
        }
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        error_log("Error updating return status: " . $e->getMessage());
    }
    
    // Redirect back
    header("Location: ../manage_returns.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_id = $_POST['return_id'] ?? null;
    $status = $_POST['status'] ?? null;
    
    if ($return_id && $status) {
        updateReturnStatus($return_id, $status);
    }
}