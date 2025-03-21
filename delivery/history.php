<?php
// history.php - Displays delivery history for the delivery team

// Start session to check user role
session_start();

// Include database connection
include('../config.php');

// Check if the user is logged in and has the 'delivery' role
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery') {
  //  header("Location: ../login.php");
   // exit();
//}

// Fetch delivery history from the database
$query = "SELECT * FROM deliveries ORDER BY delivery_date DESC";
$result = mysqli_query($conn, $query);

// Check if there are results
if (mysqli_num_rows($result) > 0) {
    echo "<h1>Delivery History</h1>";
    echo "<table>";
    echo "<tr><th>Delivery ID</th><th>Item</th><th>Delivered To</th><th>Delivery Date</th><th>Status</th></tr>";
    
    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['delivery_id'] . "</td>";
        echo "<td>" . $row['item_name'] . "</td>";
        echo "<td>" . $row['delivered_to'] . "</td>";
        echo "<td>" . $row['delivery_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No delivery history found.";
}

// Close database connection
mysqli_close($conn);
?>
