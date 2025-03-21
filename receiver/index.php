<?php
// receiver/index.php

session_start();

// Check if the user is logged in and has the right permissions
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'receiver') {
    header("Location: ../login.php");
    exit();
}

// Include necessary files
include '../templates/header.php';
include '../templates/sidebar.php';

// Fetch pending receipts from the database (this is a placeholder, implement your own logic)
$pendingReceipts = []; // Fetch from database

?>

<div class="container">
    <h1>Receiver Dashboard</h1>
    <h2>Pending Receipts</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Borrower</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pendingReceipts)): ?>
                <tr>
                    <td colspan="4">No pending receipts.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pendingReceipts as $receipt): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($receipt['item']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['borrower']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['status']); ?></td>
                        <td>
                            <a href="confirm_receipt.php?id=<?php echo $receipt['id']; ?>">Confirm</a>
                            <a href="reject_receipt.php?id=<?php echo $receipt['id']; ?>">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include '../templates/footer.php';
?>