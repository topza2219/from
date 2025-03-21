<?php
// pending_receipts.php

// Start session and include necessary files
session_start();
include '../config.php'; // Database connection
include '../header.php'; // Header file
include '../access_control.php'; // Access control for receivers

// Fetch pending receipts from the database
$query = "SELECT * FROM receipts WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Display pending receipts
?>

<div class="container">
    <h2>Pending Receipts</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Receipt ID</th>
                <th>Item Name</th>
                <th>Requested By</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['receipt_id']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['requested_by']; ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td>
                        <a href="confirm_receipt.php?id=<?php echo $row['receipt_id']; ?>" class="btn btn-success">Confirm</a>
                        <a href="reject_receipt.php?id=<?php echo $row['receipt_id']; ?>" class="btn btn-danger">Reject</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
include '../footer.php'; // Footer file
?>