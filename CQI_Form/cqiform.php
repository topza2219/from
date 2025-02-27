<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประเมินผลงานคุณภาพต่อเนื่อง CQI</title>

    
    <style>
/* ใช้ Google Font */
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
    width: 90%;
    max-width: 800px;
}

.section {
    margin-bottom: 40px;
    padding: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}

label {
    font-weight: 400;
}

.form-group {
    display: flex;
    align-items: center;
    gap: 5px;
}

.form-group input {
    margin-right: 10px;
}

.column {
    display: flex;
    flex-direction: column;
    gap: 10px; /* ปรับค่าตามต้องการ */
}

.column-container {
    display: grid;
    grid-template-columns: 50px auto;
    gap: 10px;
}

/* .column:first-child {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
} */

.column:last-child {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    height: 100%;
    gap: 15px; /* ปรับระยะห่างระหว่าง label */

}

input[type="checkbox"] {
    width: 20px;
    height: 20px;
    vertical-align: middle;
    margin-top: 10px;
}

input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.error-message {
    color: red;
    text-align: center;
    display: none;
    margin-top: 10px;
}

    </style>

</head>
<body>
    <h2>เกณฑ์พิจารณาประเมินผลงานพัฒนาคุณภาพต่อเนื่อง (CQI Project) (ฉบับร่าง)</h2>
    <form method="post" action="cqiphp.php" onsubmit="showModal(event)">
        <h3>รอบที่ 1 </h3><h4>สัดส่วนคะแนน 20 คะแนน</h4>

        <div class="section">
            <label>ประเด็นการประเมิน 1.1 Gap identification (ข้อละ 2 คะแนน)</label>
            <div class="form-group">
                <div class="column-container">
                    <div class="column" style="background-color:rgb(183, 218, 255);">
                        <input style="margin-bottom: 2px;" type="checkbox" name="question1_1[]" value="1" >
                        <input style="margin-bottom: 15px;" type="checkbox" name="question1_1[]" value="2" >
                        <input style="margin-bottom: 20px;" type="checkbox" name="question1_1[]" value="3" >
                        <input style="margin-bottom: 46px;"type="checkbox" name="question1_1[]" value="4" >
                        <input type="checkbox" name="question1_1[]" value="5" >
                    </div>
                    <div class="column">
                        <label>- No Gap Identification <u>ไม่มี</u>การวิเคราะห์ปัญหาด้วยข้อมูลใดๆ</label>
                        <label>- Basic Gap Identification มีการระบุ gap เบื้องต้น จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง แต่ไม่มีการวิเคราะห์ปัญหาด้วยข้อมูล</label>
                        <label>- Moderate Gap Identification มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง(Data review) แต่ไม่มีการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือจากแหล่งอื่น</label>
                        <label>- In-depth Gap Identification มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง(Data review) โดยการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือแหล่งอื่น แต่ยังไม่ได้วิเคราะห์หาสาเหตุรากของปัญหา</label>
                        <label>- RCA with management by fact มีการระบุ gap โดยใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต และวิเคราะห์เพื่อหาสาเหตุรากของปัญหา</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <label>ประเด็นการประเมิน 1.2 Purpose (ข้อละ 1 คะแนน)</label>
            <div class="form-group">
                <div class="column-container">
                    <div class="column" style="background-color:rgb(183, 218, 255);">
                        <input style="margin-bottom: 120px;" type="checkbox" name="question1_2[]" value="1">
                        <input style="margin-bottom: 119px;" type="checkbox" name="question1_2[]" value="2">
                        <input style="margin-bottom: 120px;" type="checkbox" name="question1_2[]" value="3">
                        <input style="margin-bottom: 120px;"type="checkbox" name="question1_2[]" value="4">
                        <input style="margin-bottom: 10px;" type="checkbox" name="question1_2[]" value="5">
                    </div>
                    <div class="column">
                        <label>- <u><b>ไม่มี</b></u>คุณสมบัติตามเกณฑ์ ดังนี้<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)</label>
                        <label>- <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ 1 ข้อ<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)</label>
                        <label>- <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ 2 ข้อ<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)</label>
                        <label>- <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ 3 ข้อ<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)</label>
                        <label>- <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ครบถ้วน<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <label>ประเด็นการประเมิน 1.3 Impact of project (ข้อละ 1 คะแนน)</label>
            <div class="form-group">
                <div class="column-container">
                    <div class="column" style="background-color:rgb(183, 218, 255);">
                        <input type="checkbox" name="question1_3[]" value="1">
                        <input type="checkbox" name="question1_3[]" value="2">
                        <input type="checkbox" name="question1_3[]" value="3">
                        <input type="checkbox" name="question1_3[]" value="4">
                        <input type="checkbox" name="question1_3[]" value="5">
                    </div>
                    <div class="column">
                        <label>- วัตถุประสงค์ <u>ไม่สอดคล้อง</u> กับ pain point หรือ <b>ระบุโดยอ้อม</b> หรือ <b>ระบุไม่ชัดเจน</b></label>
                        <label>- วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับหน่วยงาน</b></label>
                        <label>- วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับกลุ่มงาน</b></label>
                        <label>- วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับระบบงานสำคัญ/PCT</b></label>
                        <label>- วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับระบบงานสำคัญ/PCT</b></label>
                    </div>
                </div>
            </div>
        </div>

        <h3>รอบที่ 2 </h3><h4>สัดส่วนคะแนน 30 คะแนน</h4>
        <div class="section">
            <label>ประเด็นการประเมิน Process design (ข้อละ 6 คะแนน)</label>
            <div class="form-group">
                <div class="column-container">
                    <div class="column" style="background-color:rgb(183, 218, 255);">
                        <input type="checkbox" name="question2[]" value="1">
                        <input style="margin-bottom: 90px;" type="checkbox" name="question2[]" value="2">
                        <input style="margin-bottom: 93px;" type="checkbox" name="question2[]" value="3">
                        <input style="margin-bottom: 97px;" type="checkbox" name="question2[]" value="4">
                        <input type="checkbox" name="question2[]" value="5">
                    </div>
                    <div class="column">
                        <label>- ขั้นตอนดำเนินการ <b>ไม่สอดคล้อง</b> กับวัตถุประสงค์ หรือ สอดคล้องแต่<b>ไม่ชัดเจน</b></label>
                        <label>- มีขั้นตอนการดำเนินการเพียง <b>1 ประเด็น</b> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process</label>
                        <label>- มีขั้นตอนการดำเนินการครอบคลุม <b>2 ประเด็น</b> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process</label>
                        <label>- มีขั้นตอนการดำเนินการครอบคลุม <b>3 ประเด็น</b> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process</label>
                        <label>- มีขั้นตอนการดำเนินการ<u><b>ครอบคลุมทุกประเด็น</b></u> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process</label>
                    </div>
                </div>
            </div>
        </div>

        <h3>รอบที่ 3 </h3><h4>สัดส่วนคะแนน 20 คะแนน</h4>
        <div class="section">
            <label>ประเด็นการประเมิน Performance(ครั้งที่ 1) (ข้อละ 4 คะแนน)</label>
            <div class="form-group">
                <div class="column-container">
                    <div class="column" style="background-color:rgb(183, 218, 255);">
                        <input type="checkbox" name="question3[]" value="1">
                        <input style="margin-bottom: 25px;" type="checkbox" name="question3[]" value="2">
                        <input style="margin-bottom: 66px;" type="checkbox" name="question3[]" value="3">
                        <input style="margin-bottom: 42px;" type="checkbox" name="question3[]" value="4">
                        <input type="checkbox" name="question3[]" value="5">
                    </div>
                    <div class="column">
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome)</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>แต่</b></u>ไม่บรรลุเป้าหมาย</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) และมี แนวโน้มดีขึ้น<br><b>หมายเหตุ:</b> แม้ไม่บรรลุเป้าหมาย แต่มีหลักฐานการทบทวน วิเคราะห์ GAP และ นำไปสู่การปรับปรุงกระบวนการใหม่ (re-design)</b></label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) บรรลุเป้าหมาย แต่ยังไม่สามารถเทียบเคียงกับที่อื่นๆได้</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>บรรลุเป้าหมาย</b></u> และมี <u><b>ผลลัพธ์ดีเยี่ยม</b></u> เทียบเคียงแล้วดีกว่าโรงพยาบาลระดับเดียวกัน/ระดับเขต/ระดับประเทศ<br><b>หมายเหตุ: </b>ต้องมีข้อมูลเชิงประจักษ์</label>
                    </div>
                </div>
            </div>
        </div>

        <h3>รอบที่ 4 </h3><h4>สัดส่วนคะแนน 30 คะแนน</h4>
        <div class="section">
            <label>ประเด็นการประเมิน Performance(ครั้งที่ 2) (ข้อละ 6 คะแนน)</label>
            <div class="form-group">
                <div class="column-container">
                    <div class="column" style="background-color:rgb(183, 218, 255);">
                        <input type="checkbox" name="question4[]" value="1">
                        <input style="margin-bottom: 19px;" type="checkbox" name="question4[]" value="2">
                        <input style="margin-bottom: 45px;" type="checkbox" name="question4[]" value="3">
                        <input style="margin-bottom: 49px;" type="checkbox" name="question4[]" value="4">
                        <input type="checkbox" name="question4[]" value="5">
                    </div>
                    <div class="column">
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome)</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>แต่</b></u>ไม่บรรลุเป้าหมาย</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) และมี แนวโน้มดีขึ้น<br><b>หมายเหตุ:</b>แต่มีหลักฐานการทบทวน วิเคราะห์ GAP และ CQI</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) บรรลุเป้าหมาย แต่ยังไม่สามารถเทียบเคียงกับที่อื่นๆได้</label>
                        <label>- มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>บรรลุเป้าหมาย</b></u> และมี <u><b>ผลลัพธ์ดีเยี่ยม</b></u> เทียบเคียงแล้วดีกว่าโรงพยาบาลระดับเดียวกัน/ระดับเขต/ระดับประเทศ<br><b>หมายเหตุ: </b>ต้องมีข้อมูลเชิงประจักษ์</label>
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" value="ส่งข้อมูล">
        <div class="error-message" id="error-message">ไม่สามารถบันทึกข้อมูลได้ เนื่องจากแบบฟอร์มไม่สมบูรณ์</div>
    </form>
    <script>
            // เช็คฟอร์มก่อนการส่งข้อมูล
    document.querySelector("form").addEventListener("submit", function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มในกรณีที่มีข้อผิดพลาด

        let formComplete = true;
        let errorMessage = document.getElementById("error-message");
        
        // ตรวจสอบแต่ละกลุ่ม checkbox ว่ามีการเลือกหรือไม่
        const score1_1 = document.querySelectorAll("input[name='question1_1[]']:checked");
        const score1_2 = document.querySelectorAll("input[name='question1_2[]']:checked");
        const score1_3 = document.querySelectorAll("input[name='question1_3[]']:checked");
        const score2 = document.querySelectorAll("input[name='question2[]']:checked");
        const score3 = document.querySelectorAll("input[name='question3[]']:checked");
        const score4 = document.querySelectorAll("input[name='question4[]']:checked");
        
        // ตรวจสอบว่าแต่ละรอบมีการเลือกคะแนนหรือไม่
        if (score1_1.length === 0 || score1_2.length === 0 || score1_3.length === 0 || score2.length === 0 || score3.length === 0 || score4.length === 0) {
            formComplete = false;
        }

        // ถ้าฟอร์มไม่สมบูรณ์ แสดงข้อความแจ้งเตือน
        if (formComplete) {
            errorMessage.style.display = "none";
            // ถ้าฟอร์มถูกต้อง ส่งข้อมูล
            document.querySelector("form").submit();
        } else {
            errorMessage.style.display = "block";
        }
    });
        // เช็คฟอร์มก่อนการส่งข้อมูล
        document.getElementById("cqiform").addEventListener("submit", function(event) {
            event.preventDefault(); // ป้องกันการส่งฟอร์มในกรณีที่มีข้อผิดพลาด

            let formComplete = true;
            let errorMessage = document.getElementById("error-message");
            const checkboxes = document.querySelectorAll("input[type='checkbox']");
            
            // ตรวจสอบว่าแต่ละ checkbox มีการเลือกหรือไม่
            checkboxes.forEach(function(checkbox) {
                if (!checkbox.checked) {
                    formComplete = false;
                }
            });

            // ถ้าฟอร์มไม่สมบูรณ์ แสดงข้อความแจ้งเตือน
            if (formComplete) {
                errorMessage.style.display = "none";
                // ถ้าฟอร์มถูกต้อง ส่งข้อมูล
                document.getElementById("cqiform").submit();
            } else {
                errorMessage.style.display = "block";
            }
        });
    </script>



</body>
</html>
