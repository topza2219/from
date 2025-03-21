<?php
require_once __DIR__ . '/../../config.php';

function getDeliveryStats($delivery_id) {
    global $conn;
    
    $stats = [
        'pending_count' => 0,
        'in_progress_count' => 0,
        'completed_count' => 0,
        'deliveries' => []
    ];
    
    try {
        // Get counts
        $sql = "SELECT 
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM deliveries
                WHERE delivery_staff_id = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $delivery_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $stats['pending_count'] = $row['pending'];
            $stats['in_progress_count'] = $row['in_progress'];
            $stats['completed_count'] = $row['completed'];
        }
        
        // Get deliveries
        $sql = "SELECT 
                d.delivery_id as id,
                i.item_name,
                u.full_name as recipient_name,
                u.address,
                d.status
                FROM deliveries d
                LEFT JOIN borrow_requests br ON d.request_id = br.request_id
                LEFT JOIN items i ON br.item_id = i.item_id
                LEFT JOIN users u ON br.borrower_id = u.user_id
                WHERE d.delivery_staff_id = ?
                ORDER BY 
                    CASE d.status
                        WHEN 'pending' THEN 1
                        WHEN 'in_progress' THEN 2
                        ELSE 3
                    END,
                    d.created_at DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $delivery_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stats['deliveries'] = $result->fetch_all(MYSQLI_ASSOC);
        
        return $stats;
        
    } catch (Exception $e) {
        error_log("Error fetching delivery stats: " . $e->getMessage());
        return $stats;
    }
}