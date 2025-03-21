<?php
require_once __DIR__ . '/../../config.php';

function getDeliveries() {
    global $conn;
    
    try {
        $sql = "SELECT 
                d.delivery_id as id,
                u.full_name as name,
                u.email as contact,
                br.borrow_date,
                br.expected_return_date,
                i.item_name,
                d.status,
                d.notes
                FROM deliveries d
                LEFT JOIN users u ON d.delivery_staff_id = u.user_id
                LEFT JOIN borrow_requests br ON d.request_id = br.request_id
                LEFT JOIN items i ON br.item_id = i.item_id
                ORDER BY d.created_at DESC";
                
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
        
    } catch (Exception $e) {
        error_log("Error fetching deliveries: " . $e->getMessage());
        return [];
    }
}

// Only set headers and return JSON if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Content-Type: application/json');
    echo json_encode(getDeliveries());
}
?>