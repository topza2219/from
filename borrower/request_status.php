<?php
session_start();

// Check if the borrower_id is set in session, indicating that the user is logged in
//if (!isset($_SESSION['borrower_id'])) {
    // Redirect to the login page if the user is not logged in
  //  header("Location: login.php");
    //exit();
//}

// Include database connection
include_once '../config.php';

// Fetch the borrower's request status
$borrower_id = $_SESSION['borrower_id'];
$query = "SELECT * FROM borrow_requests WHERE borrower_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $borrower_id);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Status</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h1>Request Status</h1>
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
