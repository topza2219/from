<?php
session_start(); // เริ่มต้น Session

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cqiscoretest";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ฟังก์ชันช่วยในการดึงค่าที่เลือกมาใช้
    function getSelectedOptions($key) {
        return isset($_POST[$key]) ? implode(',', $_POST[$key]) : '';
    }

    // ฟังก์ชันช่วยในการนับจำนวนตัวเลือกที่ถูกเลือก
    function getScore($key, $multiplier) {
        return isset($_POST[$key]) ? count($_POST[$key]) * intval($multiplier) : 0;
    }

    // รับค่าคะแนนจากฟอร์ม
    $score1_1_total = getScore('question1_1', 2);
    $score1_2_total = getScore('question1_2', 1);
    $score1_3_total = getScore('question1_3', 1);
    $score2_total = getScore('question2', 6);
    $score3_total = getScore('question3', 4);
    $score4_total = getScore('question4', 6);

    // คำนวณคะแนนรวม
    $totalScore = $score1_1_total + $score1_2_total + $score1_3_total + $score2_total + $score3_total + $score4_total;

    // แปลงตัวเลือกที่ถูกเลือกเป็นข้อความ
    $selected_options1_1 = getSelectedOptions('question1_1');
    $selected_options1_2 = getSelectedOptions('question1_2');
    $selected_options1_3 = getSelectedOptions('question1_3');
    $selected_options2 = getSelectedOptions('question2');
    $selected_options3 = getSelectedOptions('question3');
    $selected_options4 = getSelectedOptions('question4');

    // เตรียม SQL เพื่อบันทึกข้อมูลลงฐานข้อมูล
    $sql = "INSERT INTO cqi_scores 
            (score1_1, score1_2, score1_3, score2, score3, score4, 
             selected_options1_1, selected_options1_2, selected_options1_3, 
             selected_options2, selected_options3, selected_options4, totalScore) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // ใช้ Prepared Statements เพื่อความปลอดภัย
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiisssssssi", 
                      $score1_1_total, $score1_2_total, $score1_3_total, 
                      $score2_total, $score3_total, $score4_total, 
                      $selected_options1_1, $selected_options1_2, $selected_options1_3, 
                      $selected_options2, $selected_options3, $selected_options4, 
                      $totalScore);

    if ($stmt->execute()) {
        $_SESSION['last_score'] = $totalScore;

        // บันทึกสำเร็จ ให้เปลี่ยนไปยังหน้าผลลัพธ์
        header('Location: success_page.php');
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();

} else {
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
}
?>
