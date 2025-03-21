<?php
require_once __DIR__ . '/../../config.php';

function getItems() {
    global $conn;
    
    try {
        $sql = "SELECT i.*, 
                       c.name as category_name
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                ORDER BY i.id DESC";
                
        $result = $conn->query($sql);
        
        if (!$result) {
            throw new Exception("Error fetching items: " . $conn->error);
        }
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            // Format image URL
            $row['image_url'] = $row['image'] ? "../uploads/items/" . $row['image'] : null;
            
            // Add to items array
            $items[] = $row;
        }
        
        return $items;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

// Return the results
return getItems();