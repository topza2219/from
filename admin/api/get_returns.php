<?php
// This API retrieves return information
header('Content-Type: application/json');

// Include database configuration
require_once __DIR__ . '/../../config.php';

class ReturnService {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getReturnRequests() {
        try {
            $sql = "SELECT 
                    r.return_id as id,
                    u.full_name as borrower_name,
                    i.item_name as item,
                    r.return_date as request_date,
                    r.status,
                    r.condition_notes
                    FROM returns r
                    LEFT JOIN borrow_requests br ON r.request_id = br.request_id
                    LEFT JOIN users u ON br.borrower_id = u.user_id
                    LEFT JOIN items i ON br.item_id = i.item_id
                    ORDER BY r.return_date DESC";
                    
            $result = $this->conn->query($sql);
            
            if ($result) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return !empty($data) ? $data : []; // Ensure non-empty data
            }
            
            return [];
            
        } catch (Exception $e) {
            error_log("Error fetching return requests: " . $e->getMessage());
            return ['error' => 'Error: ' . $e->getMessage()]; // Return error message in API response
        }
    }
}

// Check if this is a direct API call
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    try {
        if (!$conn) {
            throw new Exception('Database connection failed');
        }

        $returnService = new ReturnService($conn);
        $return_data = $returnService->getReturnRequests();
        echo json_encode($return_data);

    } catch (Exception $e) {
        echo json_encode([
            'error' => 'Error: ' . $e->getMessage()
        ]);
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
} else {
    // For included usage
    function getReturnRequests() {
        global $conn;
        
        try {
            $sql = "SELECT r.*, i.name as item_name, u.name as borrower_name,
                           DATE_FORMAT(r.return_date, '%d/%m/%Y') as formatted_return_date
                    FROM returns r
                    JOIN items i ON r.item_id = i.id
                    JOIN users u ON r.user_id = u.id
                    ORDER BY r.created_at DESC";
                    
            $result = $conn->query($sql);
            
            if (!$result) {
                throw new Exception($conn->error);
            }
            
            $returns = [];
            while ($row = $result->fetch_assoc()) {
                $returns[] = $row;
            }
            
            return $returns;
            
        } catch (Exception $e) {
            error_log("Error fetching returns: " . $e->getMessage());
            return [];
        }
    }

    // Return the results instead of outputting directly
    return getReturnRequests();
}
?>