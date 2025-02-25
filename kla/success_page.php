<?php
session_start(); // เริ่มต้น Session

if (!isset($_SESSION['last_score'])) {
    echo "ไม่มีคะแนนที่เพิ่งบันทึก";?>
    <a href="cqiform.php">กลับไปหน้าแรก</a>
<?php
    exit();
}

$lastScore = $_SESSION['last_score']; // ดึงค่าคะแนนจาก session

// ล้าง session เพื่อลดภาระเซิร์ฟเวอร์ (optional)
unset($_SESSION['last_score']);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคะแนน</title>
</head>
<body>

<h1>บันทึกคะแนนสำเร็จ!</h1>
<p>คะแนนของคุณคือ: <strong><?php echo $lastScore; ?></strong></p>

    <br>
    <a href="cqiform.php">กลับไปหน้าแรก</a>

</body>
</html>
