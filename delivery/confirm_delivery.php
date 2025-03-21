<?php
// confirm_delivery.php

session_start();
include '../config.php'; // Include database connection

// Check if the user is logged in and has the correct role (delivery)
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery') {
  //  header("Location: ../login.php");
    //exit();
//}

// Confirm delivery if POST request is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_id = $_POST['delivery_id'];
    $status = 'confirmed';

    // Update delivery status in the database
    $stmt = $conn->prepare("UPDATE deliveries SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $delivery_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Delivery confirmed successfully.";
    } else {
        $_SESSION['error'] = "Error confirming delivery.";
    }

    $stmt->close();
    header("Location: pending_deliveries.php");
    exit();
}

// Fetch delivery details for confirmation
$delivery_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM deliveries WHERE id = ?");
$stmt->bind_param("i", $delivery_id);
$stmt->execute();
$result = $stmt->get_result();
$delivery = $result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delivery</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Confirm Delivery</h2>
        <form method="POST" action="confirm_delivery.php">
            <input type="hidden" name="delivery_id" value="<?php echo $delivery['id']; ?>">
            <p>Are you sure you want to confirm the delivery of item: <?php echo $delivery['item_name']; ?>?</p>
            <button type="submit">Confirm</button>
            <a href="pending_deliveries.php">Cancel</a>
        </form>
    </div>
</body>
</html>
