<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประเมินผลงานคุณภาพต่อเนื่อง CQI</title>

    <style>
        /* การออกแบบการ์ดสำหรับฟอร์ม */
        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 900px;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }

        /* ขอบของการ์ด */
        form h2, form h3, form h4, form label {
            margin-bottom: 20px;
        }

        /* สไตล์ของปุ่มส่งข้อมูล */
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 32px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* ฟอร์มภายในการ์ด */
        form label {
            font-weight: bold;
            font-size: 16px;
            display: block;
            margin: 10px 0;
        }

        /* กรอบรอบๆ ของแต่ละคำถาม */
        form input[type="checkbox"] {
            margin-right: 10px;
        }

        /* เพิ่มระยะห่างระหว่างแต่ละคำถาม */
        form > label, form input[type="checkbox"] {
            margin-bottom: 10px;
        }

        /* การแสดงข้อความเตือน */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }

    </style>

</head>
<body>
    <h2>เกณฑ์พิจารณาประเมินผลงานพัฒนาคุณภาพต่อเนื่อง (CQI Project) (ฉบับร่าง)</h2>
    <form method="post" action="cqiphp.php" onsubmit="showModal(event)">
        <h3>รอบที่ 1 </h3><h4>สัดส่วนคะแนน 20 คะแนน</h4>
        <label>ประเด็นการประเมิน 1.1 Gap identification (สัดส่วนคะแนน 10 คะแนน)</label><br>
        <input type="checkbox" name="question1_1[]" value="1"> No Gap Identification <u>ไม่มี</u>การวิเคราะห์ปัญห่าด้วยข้อมูลใดๆ<br>
        <input type="checkbox" name="question1_1[]" value="2"> Basic Gap Identification มีการระบุ gap เบื้องต้น จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง แต่ไม่มีการวิเคราะห์ปัญห่าด้วยข้อมูล<br>
        <input type="checkbox" name="question1_1[]" value="3"> Moderate Gap Identification มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง(Data review) แต่ไม่มีการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือจากแหล่งอื่น<br>
        <input type="checkbox" name="question1_1[]" value="4"> In-depth Gap Identification มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง(Data review) โดยการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือแหล่งอื่น แต่ยังไม่ได้วิเคราะห์หาสาเหตุรากของปัญหา<br>
        <input type="checkbox" name="question1_1[]" value="5"> RCA with management by fact มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง(Data review) โดยการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือแหล่งอื่น อย่างครบถ้วนและถูกต้อง ในการวิเคราะห์เพื่อหาสาเหตุรากของปัญหา<br><br>
        
        <label>ประเด็นการประเมิน 1.2 Purpose (สัดส่วนคะแนน 5 คะแนน)</label><br>
        <input type="checkbox" name="question1_2[]" value="1"> <u><b>ไม่มี</b></u>คุณสมบัติตามเกณฑ์ ดังนี้<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)<br>
        <input type="checkbox" name="question1_2[]" value="2"> <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ 1 ข้อ<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)<br>
        <input type="checkbox" name="question1_2[]" value="3"> <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ 2 ข้อ<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)<br>
        <input type="checkbox" name="question1_2[]" value="4"> <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ 3 ข้อ<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)<br>
        <input type="checkbox" name="question1_2[]" value="5"> <u><b>มี</b></u>คุณสมบัติตามเกณฑ์ ครบถ้วน<br>1. มีความชัดเจนว่าจะทำเพื่ออะไร(ปรับปรุง/พัฒนา)<br>2. มีความสอดคล้องกับปัญหาที่ทบทวน/วิเคราะห์(gap)<br>3. สามารถวัดผลได้(เพิ่มขึ้น/น้อยลง/%) ระบุตัวชี้วัดของผลลัพธ์<br>4. สะท้อนมิติด้านคุณภาพ(เช่น Accessibility, Appropriateness, Efficiency Effectiveness, Competency, Continuity, Safety)<br><br>

        <label>ประเด็นการประเมิน 1.3 Impact of project (สัดส่วนคะแนน 5 คะแนน)</label><br>
        <input type="checkbox" name="question1_3[]" value="1"> วัตถุประสงค์ <u>ไม่สอดคล้อง</u> กับ pain point หรือ <b>ระบุโดยอ้อม</b> หรือ <b>ระบุไม่ชัดเจน</b><br>
        <input type="checkbox" name="question1_3[]" value="2"> วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับหน่วยงาน</b><br>
        <input type="checkbox" name="question1_3[]" value="3"> วัตถุประสงค์สอดคล้องหกับ pain point <b>ระดับกลุ่มงาน</b><br>
        <input type="checkbox" name="question1_3[]" value="4"> วัตถุประสงค์สอดคล้องหกับ pain point <b>ระดับระบบงานสำคัญ/PCT</b><br>
        <input type="checkbox" name="question1_3[]" value="5"> วัตถุประสงค์สอดคล้องหกับ pain point <b>ระดับระบบงานสำคัญ/PCT</b><br>

        <h3>รอบที่ 2</h3><h4>สัดส่วนคะแนน 30 คะแนน</h4>
        <label>ประเด็นการประเมิน Process design</label><br>
        <input type="checkbox" name="question2[]" value="1"> ขั้นตอนดำเนินการ <b>ไม่สอดคล้อง</b> กับวัตถุประสงค์ หรือ สอดคล้องแต่<b>ไม่ชัดเจน</b><br>
        <input type="checkbox" name="question2[]" value="2"> มีขั้นตอนการดำเนินการเพียง <b>1 ประเด็น</b> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process<br>
        <input type="checkbox" name="question2[]" value="3"> มีขั้นตอนการดำเนินการครอบคลุม <b>2 ประเด็น</b> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process<br>
        <input type="checkbox" name="question2[]" value="4"> มีขั้นตอนการดำเนินการครอบคลุม <b>3 ประเด็น</b> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process<br>
        <input type="checkbox" name="question2[]" value="5"> มีขั้นตอนการดำเนินการ<u><b>ครอบคลุมทุกประเด็น</b></u> ดังนี้<br>1. process flow related with objective<br>2. data review for process flow<br>3. team cooperation<br>4. reliable process<br><br>

        <h3>รอบที่ 3</h3><h4>สัดส่วนคะแนน 20 คะแนน</h4>
        <label>ประเด็นการประเมิน Performance(ครั้งที่ 1)</label><br>
        <input type="checkbox" name="question3[]" value="1"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome)<br>
        <input type="checkbox" name="question3[]" value="2"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>แต่</b></u>ไม่บรรลุเป้าหมาย<br>
        <input type="checkbox" name="question3[]" value="3"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) และมี แนวโน้มดีขึ้น<br><b>หมายเหตุ:</b> แม้ไม่บรรลุเป้าหมาย แต่มีหลักฐานการทบทวน วิเคราะห์ GAP และ นำไปสู่การปรับปรุงกระบวนการใหม่ (re-design)</b></b><br>
        <input type="checkbox" name="question3[]" value="4"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) บรรลุเป้าหมาย แต่ยังไม่สามารถเทียบเคียงกับที่อื่นๆได้<br>
        <input type="checkbox" name="question3[]" value="5"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>บรรลุเป้าหมาย</b></u> และมี <u><b>ผลลัพธ์ดีเยี่ยม</b></u> เทียบเคียงแล้วดีกว่าโรงพยาบาลระดับเดียวกัน/ระดับเขต/ระดับประเทศ<br><b>หมายเหตุ: </b>ต้องมีข้อมูลเชิงประจักษ์<br>

        <h3>รอบที่ 4</h3><h4>สัดส่วนคะแนน 30 คะแนน</h4>
        <label>ประเด็นการประเมิน Performance(ครั้งที่ 2)</label><br>
        <input type="checkbox" name="question4[]" value="1"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome)<br>
        <input type="checkbox" name="question4[]" value="2"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>แต่</b></u>ไม่บรรลุเป้าหมาย<br>
        <input type="checkbox" name="question4[]" value="3"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) และมี แนวโน้มดีขึ้น<br><b>หมายเหตุ:</b>แต่มีหลักฐานการทบทวน วิเคราะห์ GAP และ CQI<br>
        <input type="checkbox" name="question4[]" value="4"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) บรรลุเป้าหมาย แต่ยังไม่สามารถเทียบเคียงกับที่อื่นๆได้<br>
        <input type="checkbox" name="question4[]" value="5"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ(process outcome) ร่วมกับ มีผลลัพธ์การดำเนินการตามตัวชี้วัด(outcome) <u><b>บรรลุเป้าหมาย</b></u> และมี <u><b>ผลลัพธ์ดีเยี่ยม</b></u> เทียบเคียงแล้วดีกว่าโรงพยาบาลระดับเดียวกัน/ระดับเขต/ระดับประเทศ<br><b>หมายเหตุ: </b>ต้องมีข้อมูลเชิงประจักษ์<br>

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
