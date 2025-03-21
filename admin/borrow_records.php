<?php
// borrow_history.php

// Include database connection
include_once '../config.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่าคอลัมน์ `item_name` มีอยู่ในตาราง `items` หรือไม่
$column_check_query = "SHOW COLUMNS FROM items LIKE 'item_name'";
$column_result = mysqli_query($conn, $column_check_query);
$item_column_exists = mysqli_num_rows($column_result) > 0;

// ปรับ Query ตามโครงสร้างตาราง
$query = "SELECT bh.id AS record_id, 
                 u.full_name AS borrower_name, 
                 " . ($item_column_exists ? "i.item_name" : "i.name") . " AS item, 
                 bh.borrow_date, 
                 bh.return_date, 
                 bh.status 
          FROM borrow_history bh
          JOIN users u ON bh.user_id = u.id
          JOIN items i ON bh.item_id = i.id
          ORDER BY bh.borrow_date DESC";

$result = mysqli_query($conn, $query);

// ตรวจสอบ Query ว่าทำงานสำเร็จหรือไม่
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Borrow and Return Records</h2>";
    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Borrower Name</th>
                    <th>Item</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['record_id']}</td>
                <td>{$row['borrower_name']}</td>
                <td>{$row['item']}</td>
                <td>{$row['borrow_date']}</td>
                <td>" . (!empty($row['return_date']) ? $row['return_date'] : '<span style="color:red;">Not Returned</span>') . "</td>
                <td>" . ucfirst($row['status']) . "</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No records found.</p>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
