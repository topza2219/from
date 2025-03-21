<?php
require_once __DIR__ . '/../../config.php';

class BorrowService {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getBorrowRequests() {
        try {
            $sql = "SELECT 
                    br.request_id as id,
                    u.full_name as borrower_name,
                    i.item_name as item,
                    br.borrow_date as request_date,
                    br.status
                    FROM borrow_requests br
                    LEFT JOIN users u ON br.borrower_id = u.user_id
                    LEFT JOIN items i ON br.item_id = i.item_id
                    ORDER BY br.borrow_date DESC";
                    
            $result = $this->conn->query($sql);
            
            if ($result) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            
            return [];
            
        } catch (Exception $e) {
            error_log("Error fetching borrow requests: " . $e->getMessage());
            return [];
        }
    }
}

// For included usage
function getBorrowers() {
    global $conn;
    $service = new BorrowService($conn);
    return $service->getBorrowRequests();
}

function get_borrowers() {
    global $conn;
    $borrowers = [];
    
    try {
        $sql = "SELECT 
                u.user_id as id,
                u.full_name as name,
                u.email,
                u.phone,
                u.status,
                COUNT(br.request_id) as total_borrows
                FROM users u
                LEFT JOIN borrow_requests br ON u.user_id = br.borrower_id
                WHERE u.role = 'borrower'
                GROUP BY u.user_id
                ORDER BY u.full_name";

        $result = $conn->query($sql);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];

    } catch (Exception $e) {
        error_log("Error fetching borrowers: " . $e->getMessage());
        return [];
    }
}

// For direct API calls
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    try {
        if (!$conn) {
            throw new Exception('Database connection failed');
        }

        $service = new BorrowService($conn);
        $borrow_data = $service->getBorrowRequests();
        header('Content-Type: application/json');
        echo json_encode($borrow_data);

    } catch (Exception $e) {
        echo json_encode([
            'error' => 'Error: ' . $e->getMessage()
        ]);
    }
}
?>