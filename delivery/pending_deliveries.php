<?php
// pending_deliveries.php

// Include database connection
include '../config.php';

// Fetch pending deliveries from the database
$query = "SELECT * FROM deliveries WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

// Check if there are any pending deliveries
if (mysqli_num_rows($result) > 0) {
    echo "<h1>Pending Deliveries</h1>";
    echo "<table>";
    echo "<tr><th>Delivery ID</th><th>Item</th><th>Receiver</th><th>Delivery Date</th><th>Status</th></tr>";

    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['delivery_id'] . "</td>";
        echo "<td>" . $row['item'] . "</td>";
        echo "<td>" . $row['receiver'] . "</td>";
        echo "<td>" . $row['delivery_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h1>No Pending Deliveries</h1>";
}

// Close database connection
mysqli_close($conn);
?>