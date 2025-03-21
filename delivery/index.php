<?php
// delivery/index.php

session_start();

// ตรวจสอบสิทธิ์การเข้าถึง
//if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'delivery') {
  //  header("Location: ../login.php");
    //exit();
//}

// รวมไฟล์สำหรับเชื่อมต่อฐานข้อมูล
include('../config.php');

// ดึงข้อมูลการส่งสินค้าที่รอการยืนยัน
$query = "SELECT * FROM deliveries WHERE status = 'pending' ORDER BY delivery_date ASC";
$result = mysqli_query($conn, $query);

$pendingDeliveries = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pendingDeliveries[] = $row;
    }
}

mysqli_close($conn);
?>

<div class="container">
    <h1>Delivery Dashboard</h1>
    <h2>Pending Deliveries</h2>
    <table>
        <thead>
            <tr>
                <th>Delivery ID</th>
                <th>Item</th>
                <th>Recipient</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pendingDeliveries)): ?>
                <tr>
                    <td colspan="5">No pending deliveries.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pendingDeliveries as $delivery): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($delivery['id']); ?></td>
                        <td><?php echo htmlspecialchars($delivery['item']); ?></td>
                        <td><?php echo htmlspecialchars($delivery['recipient']); ?></td>
                        <td><?php echo htmlspecialchars($delivery['status']); ?></td>
                        <td>
                            <a href="confirm_delivery.php?id=<?php echo htmlspecialchars($delivery['id']); ?>">Confirm</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
