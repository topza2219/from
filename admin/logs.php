<?php
// logs.php - Logs system activities

// Include database connection
include_once '../config.php';

// Fetch logs from the database
$query = "SELECT * FROM logs ORDER BY timestamp DESC";
$result = $conn->query($query);

// Check if there are logs
if ($result->num_rows > 0) {
    echo "<h1>System Logs</h1>";
    echo "<table class='table table-striped'>
<thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Action</th>
                <th>User</th>
            </tr>
            </thead>
            <tbody>";
    
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["timestamp"] . "</td>
                <td>" . $row["action"] . "</td>
                <td>" . $row["user"] . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No logs found.</p>";
}

// Close the database connection
$conn->close();
?>