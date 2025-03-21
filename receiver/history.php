<?php
// This file displays the history of receipts for the receiver.

session_start();
include '../config.php'; // Include database connection

// Fetch receipt history from the database
$query = "SELECT * FROM receipts WHERE receiver_id = :receiver_id ORDER BY receipt_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['receiver_id' => $_SESSION['user_id']]);
$receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt History</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Include your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Receipt History</h1>
        <table>
            <thead>
                <tr>
                    <th>Receipt ID</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Receipt Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($receipts) > 0): ?>
                    <?php foreach ($receipts as $receipt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receipt['id']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['receipt_date']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No receipt history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>