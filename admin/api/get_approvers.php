<?php
// Include database configuration
require_once __DIR__ . '/../../config.php';

function getApprovers() {
    global $conn;
    
    // Verify connection exists
    if (!$conn) {
        error_log("Database connection not established");
        return [];
    }
    
    try {
        $sql = "SELECT 
                user_id as id, 
                full_name as name, 
                email 
                FROM users 
                WHERE role = 'approver' 
                AND status = 'active'";
                
        $result = $conn->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
        
    } catch (Exception $e) {
        error_log("Error fetching approvers: " . $e->getMessage());
        return [];
    }
}