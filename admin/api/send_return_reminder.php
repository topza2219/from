<?php
// send_return_reminder.php

// Include database connection
include_once '../../config/database.php';

// Function to send return reminders
function sendReturnReminder($userId) {
    global $conn;

    // Fetch user's borrowed items that are due for return
    $query = "SELECT item_name, due_date FROM borrowed_items WHERE user_id = ? AND return_status = 'pending' AND due_date < NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are items due for return
    if ($result->num_rows > 0) {
        $reminders = [];
        while ($row = $result->fetch_assoc()) {
            $reminders[] = "Item: " . $row['item_name'] . " is due on " . $row['due_date'];
        }
        return $reminders;
    } else {
        return null;
    }
}

// Main logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];

    // Send reminders
    $reminders = sendReturnReminder($userId);
    if ($reminders) {
        echo json_encode(['status' => 'success', 'reminders' => $reminders]);
    } else {
        echo json_encode(['status' => 'no_reminders', 'message' => 'No items due for return.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>