<?php
// This file manages receipt notifications for the receiver.
session_start();

// Function to fetch notifications for the receiver
function getNotifications($receiverId) {
    // Placeholder for database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch notifications
    $sql = "SELECT * FROM notifications WHERE receiver_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $receiverId);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $notifications;
}

// Fetch notifications for the logged-in receiver
$receiverId = $_SESSION['receiver_id']; // Assuming receiver ID is stored in session
$notifications = getNotifications($receiverId);

// Display notifications
foreach ($notifications as $notification) {
    echo "<div class='notification'>";
    echo "<p>" . htmlspecialchars($notification['message']) . "</p>";
    echo "<small>" . htmlspecialchars($notification['created_at']) . "</small>";
    echo "</div>";
}
?>