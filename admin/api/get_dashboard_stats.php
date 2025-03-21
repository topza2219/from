<?php
require_once __DIR__ . '/../../config.php';

function getDashboardStats($start_date = null, $end_date = null) {
    global $conn;
    
    try {
        $stats = [
            'total_borrows' => 0,
            'pending_approvals' => 0,
            'total_items' => 0,
            'total_users' => 0,
            'recent_activities' => [],
            'months' => [],
            'borrows_per_month' => [],
            'user_roles' => [],
            'user_counts' => []
        ];

        // Add date conditions if provided
        $date_condition = "";
        if ($start_date && $end_date) {
            $date_condition = " AND DATE(created_at) BETWEEN ? AND ?";
        }

        // Get total borrows
        $result = $conn->query("SELECT COUNT(*) as total FROM borrow_requests");
        $stats['total_borrows'] = $result->fetch_assoc()['total'];

        // Get pending approvals
        $result = $conn->query("SELECT COUNT(*) as pending FROM borrow_requests WHERE status = 'pending'");
        $stats['pending_approvals'] = $result->fetch_assoc()['pending'];

        // Get total items
        $result = $conn->query("SELECT COUNT(*) as total FROM items");
        $stats['total_items'] = $result->fetch_assoc()['total'];

        // Get total users
        $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
        $stats['total_users'] = $result->fetch_assoc()['total'];

        // Get recent activities
        $sql = "SELECT 
                al.created_at as date,
                u.full_name as user,
                al.action,
                al.description,
                CASE 
                    WHEN al.action LIKE '%completed%' THEN 'completed'
                    WHEN al.action LIKE '%rejected%' THEN 'rejected'
                    ELSE 'pending'
                END as status
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.user_id
                ORDER BY al.created_at DESC
                LIMIT 5";
                
        $result = $conn->query($sql);
        if ($result) {
            $stats['recent_activities'] = $result->fetch_all(MYSQLI_ASSOC);
        }

        // Get monthly borrow statistics
        $sql = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as count
                FROM borrow_requests
                WHERE 1=1" . $date_condition . "
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";

        $stmt = $conn->prepare($sql);
        if ($start_date && $end_date) {
            $stmt->bind_param("ss", $start_date, $end_date);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $stats['months'][] = $row['month'];
            $stats['borrows_per_month'][] = $row['count'];
        }

        // Get user role distribution
        $sql = "SELECT role, COUNT(*) as count 
                FROM users 
                GROUP BY role";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $stats['user_roles'][] = $row['role'];
            $stats['user_counts'][] = $row['count'];
        }

        return $stats;
        
    } catch (Exception $e) {
        error_log("Error fetching dashboard stats: " . $e->getMessage());
        return $stats;
    }
}