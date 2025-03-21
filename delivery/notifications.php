<?php

// This file manages delivery notifications.

// Function to fetch notifications for delivery personnel
function getDeliveryNotifications($userId) {
    // Establish database connection (assuming $conn is the database connection)
    include('../config.php');
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to get deliveries that have been approved and are pending delivery
    $query = "SELECT i.name AS item_name, d.id AS delivery_id, r.borrower_id, d.status, d.delivery_date 
              FROM borrow_requests r
              JOIN deliveries d ON r.id = d.request_id
              JOIN items i ON r.item_id = i.id
              WHERE r.status = 'approved' AND d.status = 'pending' 
              ORDER BY r.request_date DESC";  // Use 'request_date' for sorting

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check for errors in query
    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }

    $notifications = [];
    
    // Check if query returned results
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = [
                'id' => $row['delivery_id'],
                'message' => "New delivery request from Borrower ID {$row['borrower_id']} for Item: {$row['item_name']}",
                'timestamp' => $row['delivery_date']
            ];
        }
    }

    // Close the database connection
    mysqli_close($conn);

    return $notifications;
}

// Example usage
$userId = 1; // This would typically come from the session or authentication
$notifications = getDeliveryNotifications($userId);

// Display notifications
if (empty($notifications)) {
    echo "No new notifications.";
} else {
    foreach ($notifications as $notification) {
        echo "<div class='notification'>";
        echo "<p>{$notification['message']}</p>";
        echo "<small>{$notification['timestamp']}</small>";
        echo "</div>";
    }
}
?>
