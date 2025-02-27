<?php
session_start(); // เริ่มต้น Session

if (!isset($_SESSION['last_score'])) {
    ?>
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ไม่มีคะแนนที่บันทึก</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f7fc;
                margin: 0;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .container {
                background-color: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 30px;
                text-align: center;
                max-width: 400px;
            }

            h1 {
                color: #e74c3c;
                font-size: 22px;
                margin-bottom: 10px;
            }

            p {
                font-size: 18px;
                color: #34495e;
            }

            .btn {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #e74c3c;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-size: 16px;
                transition: background 0.3s ease-in-out;
            }

            .btn:hover {
                background-color: #c0392b;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>ไม่มีคะแนนที่เพิ่งบันทึก</h1>
            <p>กรุณากรอกข้อมูลและบันทึกคะแนนก่อน</p>
            <a href="cqiform.php" class="btn">กลับไปหน้าแรก</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

$lastScore = $_SESSION['last_score']; // ดึงค่าคะแนนจาก session
unset($_SESSION['last_score']); // ล้างค่าเพื่อลดภาระเซิร์ฟเวอร์
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคะแนน</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            max-width: 400px;
        }

        h1 {
            color: #2c3e50;
        }

        p {
            font-size: 18px;
            color: #34495e;
        }

        .score {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: background 0.3s ease-in-out;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>บันทึกคะแนนสำเร็จ!</h1>
        <p>คะแนนที่ได้: <span class="score"><?php echo $lastScore; ?>/100</span></p>
        <a href="cqiform.php" class="btn">เสร็จสิ้น</a>
    </div>
</body>
</html>