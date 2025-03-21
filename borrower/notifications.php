<?php
// notifications.php - This file manages status notifications for borrowers

session_start();

// Function to fetch notifications for the borrower
function getNotifications($userId) {
    // Placeholder for database connection
    $conn = new mysqli("localhost", "username", "password", "database");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch notifications
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $userId);
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

// Check if the user is logged in and has a valid session
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $notifications = getNotifications($userId);
} else {
    $notifications = [];
}

// Display notifications
if (empty($notifications)) {
    echo "<p>No notifications.</p>";
} else {
    foreach ($notifications as $notification) {
        echo "<div class='notification'>";
        echo "<p>" . htmlspecialchars($notification['message']) . "</p>";
        echo "<small>" . htmlspecialchars($notification['created_at']) . "</small>";
        echo "</div>";
    }
}
?>
