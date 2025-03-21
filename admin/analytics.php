<?php
// analytics.php

// Include necessary files for database connection and functions
include_once '../config.php'; // Database configuration
include_once 'functions.php'; // Common functions

// Fetch analytics data
$analyticsData = fetchAnalyticsData();

// Display analytics data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Analytics Dashboard</h1>
        <div class="analytics-summary">
            <h2>Borrowing Statistics (Last 30 Days)</h2>
            <p>Total Borrows: <?php echo $analyticsData['total_borrows']; ?></p>
            <p>Average Borrow Duration: <?php echo round($analyticsData['average_duration'], 2); ?> days</p>
        </div>
        <!-- Additional analytics can be added here -->
    </div>
</body>
</html>
