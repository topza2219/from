<?php include 'navbar.php';
$host = "localhost"; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$user = "root"; // ชื่อผู้ใช้ MySQL
$pass = ""; // รหัสผ่าน MySQL
$dbname = "cqiscoretest"; // ชื่อฐานข้อมูล

$conn = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

$userData = null;
$selectedOptions = [];

// โครงสร้างรอบ & ข้อ
$sections = [
    "รอบที่ 1" => [
        "question1_1" => "1.1 Gap Identification (ข้อละ 2 คะแนน)",
        "question1_2" => "1.2 Purpose (ข้อละ 1 คะแนน)",
        "question1_3" => "1.3 Impact of project (ข้อละ 1 คะแนน)"
    ],
    "รอบที่ 2" => [
        "question2" => "2. Process design (ข้อละ 6 คะแนน)"
    ],
    "รอบที่ 3" => [
        "question3" => "3. Performance(ครั้งที่ 1) (ข้อละ 4 คะแนน)"
    ],
    "รอบที่ 4" => [
        "question4" => "4. Performance(ครั้งที่ 2) (ข้อละ 6 คะแนน)"
    ]
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    if (!empty($id) && is_numeric($id)) {
        $stmt = $conn->prepare("SELECT * FROM cqi_scores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            
            // ดึงค่าที่เลือกไว้จากฐานข้อมูล
            foreach ($sections as $round => $questions) {
                foreach ($questions as $key => $label) {
                    $dbField = str_replace("question", "selected_options", $key);
                    $selectedOptions[$key] = !empty($userData[$dbField]) ? explode(",", $userData[$dbField]) : [];
                }
            }
        } else {
            $error = "ไม่พบข้อมูลสำหรับ ID นี้";
        }

        $stmt->close();
    } else {
        $error = "กรุณาป้อน ID ที่ถูกต้อง";
    }
}

// ฟังก์ชันสร้าง Checkbox
function renderCheckboxGroup($name, $label, $selectedOptions) {
    echo '<div class="section">';
    echo "<label>$label</label>";
    echo '<div class="form-group"><div class="column-container">';

    echo '<div class="column" style="background-color:rgb(183, 218, 255);">';
    $margins = ["0px", "0px", "0px", "0px", "0px"];
    // ทำให้ทุก checkbox ถูกติ๊ก (checked) และ disabled
    for ($i = 1; $i <= 5; $i++):
        $isChecked = in_array($i, $selectedOptions) ? 'checked' : ''; // ตรวจสอบว่าเลือกแล้วหรือไม่
        $isDisabled = 'disabled'; // ทำให้ทุก checkbox disabled
        echo "<input style='margin-bottom: {$margins[$i-1]};' type='checkbox' name='{$name}[]' value='{$i}' $isChecked $isDisabled>";
    endfor;
    echo '</div>';

    echo '<div class="column">';
    echo '<label>- เกณฑ์การประเมินข้อที่ 1</label>';
    echo '<label>- เกณฑ์การประเมินข้อที่ 2</label>';
    echo '<label>- เกณฑ์การประเมินข้อที่ 3</label>';
    echo '<label>- เกณฑ์การประเมินข้อที่ 4</label>';
    echo '<label>- เกณฑ์การประเมินข้อที่ 5</label>';
    echo '</div>';

    echo '</div></div></div>';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาการประเมิน</title>
    <link rel="stylesheet" href="">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap');

/* Navbar */
nav {
    width: 100%;
    background-color: #007bff;
    padding: 10px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
    gap: 20px;
}

nav ul li {
    display: inline;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    font-weight: 600;
    padding: 10px 20px;
    transition: background 0.3s;
}

nav ul li a:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

body {
    font-family: 'Prompt', sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2, h3, h4 {
    text-align: center;
    color: #007bff;
}

form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 450px;
    margin-bottom: 20px;
}

.section {
    margin-bottom: 40px;
    padding: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    width: 100%;
    max-width: 600px;
}

/* Align checkbox and label properly in columns */
.form-group {
    display: flex;
    justify-content: space-between; /* จัดให้ label กับ checkbox อยู่ในแถวเดียวกัน */
    align-items: center; /* จัดให้ checkbox อยู่ในระดับเดียวกับ label */
    gap: 10px;
}

/* For responsiveness */
.column-container {
    display: flex;
    flex-wrap: wrap; /* Allows columns to stack on smaller screens */
    gap: 15px;
    align-items: center;  /* Aligns items vertically to the center */
}

/* Each column style */
.column {
    display: flex;
    flex-direction: column;
    gap: 15px;
    flex: 1;  /* Allow columns to grow and shrink equally */
    width:auto;
    margin-bottom: 1px;
}

.column:first-child {
    max-height: 900px;
    max-width: 30px;
    gap: 10px;
}

.column:last-child {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    height: 100%;
    width: 900px;
    gap: 16px;
    margin-top:9px;
}

/* Style the checkbox */
input[type="checkbox"] {
    width: 20px;
    height: 20px;
    vertical-align: middle;
    margin-top: 10px;
}

/* Button style */
button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 20px;
}

button:hover {
    background-color: #0056b3;
}

/* Media Queries for responsive layout */
@media (max-width: 768px) {
    /* Adjust the layout for tablets and smaller screens */
    .column-container {
        grid-template-columns: 1fr;  /* Stack the columns on top of each other */
    }

    .form-group {
        flex-direction: column;
    }

    .section {
        width: 100%;
        padding: 20px;
    }
}

@media (max-width: 480px) {
    /* Adjust the layout for mobile screens */
    .form-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .column-container {
        grid-template-columns: 1fr;  /* Stack columns in a single column */
    }

    .section {
        width: 100%;
        padding: 15px;
    }

    h2, h3, h4 {
        font-size: 18px;
    }

    button {
        padding: 10px;
    }
}
    </style>
</head>
<body>

    <h2>ค้นหาการประเมินตาม ID</h2>

    <form method="post">
        <label for="id">ป้อน ID:</label>
        <input type="text" name="id" required>
        <button type="submit">ค้นหา</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

<?php if ($userData): ?>
    <?php foreach ($sections as $round => $questions): ?>
        <h3><?php echo $round; ?></h3>

        <?php 
            // เพิ่มคะแนนตามแต่ละรอบ
            if ($round == "รอบที่ 1") echo "<h4>สัดส่วนคะแนน 20 คะแนน</h4>";
            if ($round == "รอบที่ 2") echo "<h4>สัดส่วนคะแนน 30 คะแนน</h4>";
            if ($round == "รอบที่ 3") echo "<h4>สัดส่วนคะแนน 20 คะแนน</h4>";
            if ($round == "รอบที่ 4") echo "<h4>สัดส่วนคะแนน 30 คะแนน</h4>";
        ?>

        <?php foreach ($questions as $key => $label): ?>
            <?php renderCheckboxGroup($key, $label, $selectedOptions[$key]); ?>
        <?php endforeach; ?>

    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>