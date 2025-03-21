<?php
session_start();

// Check if the user is logged in
//if (!isset($_SESSION['borrower_id'])) {
  //  header("Location: login.php"); // Redirect to login page
    //exit();
//}

// Include database connection
include_once '../config.php';

// Fetch the borrower's requests from the database
$borrower_id = $_SESSION['borrower_id'];
$query = "SELECT * FROM borrow_requests WHERE borrower_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $borrower_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all requests
$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h1>My Borrow Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="4">No requests found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['id']); ?></td>
                            <td><?php echo htmlspecialchars($request['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['status']); ?></td>
                            <td><?php echo htmlspecialchars($request['date_requested']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="new_request.php">Make a New Request</a>
    </div>
</body>
</html>
