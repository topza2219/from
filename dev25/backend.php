<?php


// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'fromtest';
$username = 'root';
$password = '';

try {
    // สร้างการเชื่อมต่อด้วย PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // ตั้งค่า error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $e->getMessage());
}

// แก้ไขฟังก์ชัน getHighestScore เป็นฟังก์ชันคำนวณผลรวมคะแนน
function calculateTotalScore($selectedOptions) {
    if (empty($selectedOptions)) {
        return 0;
    }
    // รวมคะแนนจากทุกตัวเลือกที่เลือก
    return array_sum($selectedOptions);
}

// รับข้อมูลจาก POST (แต่ละคำถามเป็น array เนื่องจากเป็น checkbox หลายตัว)
$question1_1 = isset($_POST['question1_1']) ? $_POST['question1_1'] : [];
$question1_2 = isset($_POST['question1_2']) ? $_POST['question1_2'] : [];
$question1_3 = isset($_POST['question1_3']) ? $_POST['question1_3'] : [];
$question2    = isset($_POST['question2'])    ? $_POST['question2']    : [];
$question3    = isset($_POST['question3'])    ? $_POST['question3']    : [];
$question4    = isset($_POST['question4'])    ? $_POST['question4']    : [];

// สำหรับการเลือกตัวเลือก (รายละเอียด) สามารถเก็บเป็น JSON ใน field ของฐานข้อมูล
$selected_options1_1 = json_encode($question1_1);
$selected_options1_2 = json_encode($question1_2);
$selected_options1_3 = json_encode($question1_3);
$selected_options2    = json_encode($question2);
$selected_options3    = json_encode($question3);
$selected_options4    = json_encode($question4);

// เปลี่ยนการคำนวณคะแนนในแต่ละหัวข้อ
$score1_1 = calculateTotalScore($question1_1);
$score1_2 = calculateTotalScore($question1_2);
$score1_3 = calculateTotalScore($question1_3);
$score2 = calculateTotalScore($question2);
$score3 = calculateTotalScore($question3);
$score4 = calculateTotalScore($question4);

// คำนวณคะแนนในแต่ละรอบ (หัวใหญ่)
// รอบที่ 1: รวมคะแนน 1.1, 1.2, 1.3
$round1Score = $score1_1 + $score1_2 + $score1_3;
// รอบที่ 2: score2
$round2Score = $score2;
// รอบที่ 3: score3
$round3Score = $score3;
// รอบที่ 4: score4
$round4Score = $score4;

// คำนวณคะแนนรวมทั้งหมด
$totalScore = $round1Score + $round2Score + $round3Score + $round4Score;

try {
    // เตรียม statement สำหรับ insert ข้อมูลลงในตาราง (ในตัวอย่างชื่อ 'scores')
    $stmt = $pdo->prepare("INSERT INTO scores 
        (score1_1, score1_2, score1_3, score2, score3, score4, 
         selected_options1_1, selected_options1_2, selected_options1_3, 
         selected_options2, selected_options3, selected_options4, totalScore)
        VALUES
        (:score1_1, :score1_2, :score1_3, :score2, :score3, :score4, 
         :selected_options1_1, :selected_options1_2, :selected_options1_3, 
         :selected_options2, :selected_options3, :selected_options4, :totalScore)");

    // ผูกค่าตัวแปรกับ parameter ใน statement
    $stmt->bindParam(':score1_1', $score1_1, PDO::PARAM_INT);
    $stmt->bindParam(':score1_2', $score1_2, PDO::PARAM_INT);
    $stmt->bindParam(':score1_3', $score1_3, PDO::PARAM_INT);
    $stmt->bindParam(':score2', $score2, PDO::PARAM_INT);
    $stmt->bindParam(':score3', $score3, PDO::PARAM_INT);
    $stmt->bindParam(':score4', $score4, PDO::PARAM_INT);
    $stmt->bindParam(':selected_options1_1', $selected_options1_1, PDO::PARAM_STR);
    $stmt->bindParam(':selected_options1_2', $selected_options1_2, PDO::PARAM_STR);
    $stmt->bindParam(':selected_options1_3', $selected_options1_3, PDO::PARAM_STR);
    $stmt->bindParam(':selected_options2', $selected_options2, PDO::PARAM_STR);
    $stmt->bindParam(':selected_options3', $selected_options3, PDO::PARAM_STR);
    $stmt->bindParam(':selected_options4', $selected_options4, PDO::PARAM_STR);
    $stmt->bindParam(':totalScore', $totalScore, PDO::PARAM_INT);

    // ทำการ execute statement
    $stmt->execute();

    // แสดงผลคะแนนในแต่ละหัวข้อและคะแนนรวม
    echo '<div class="evaluation-results">';
    echo "<h3>ผลการประเมิน</h3>";
    echo "<p>คะแนนย่อย 1.1: <strong>$score1_1</strong></p>";
    echo "<p>คะแนนย่อย 1.2: <strong>$score1_2</strong></p>";
    echo "<p>คะแนนย่อย 1.3: <strong>$score1_3</strong></p>";
    echo "<p>รอบที่ 1 (ประเด็น 1.1 + 1.2 + 1.3): <strong>$round1Score</strong></p>";
    echo "<p>รอบที่ 2 (Process design): <strong>$round2Score</strong></p>";
    echo "<p>รอบที่ 3 (Performance ครั้งที่ 1): <strong>$round3Score</strong></p>";
    echo "<p>รอบที่ 4 (Performance ครั้งที่ 2): <strong>$round4Score</strong></p>";
    echo "<hr>";
    echo "<h3 class='total-score'>คะแนนรวมทั้งหมด: <strong>$totalScore</strong></h3>";
    echo '</div>';

} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        <style>
        .p
        {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
    .evaluation-results {
        width: 80%;
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    .evaluation-results h3 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .evaluation-results p {
        margin: 10px 0;
        font-size: 16px;
        color: #555;
    }

    .evaluation-results strong {
        font-weight: bold;
        color: #007bff;
    }

    .evaluation-results hr {
        border: 1px solid #ddd;
        margin: 20px 0;
    }

    .evaluation-results .total-score {
        text-align: center;
        font-size: 20px;
        color: #28a745;
        margin-top: 20px;
    }
</style>
        </style>
</body>
</html>