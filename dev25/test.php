<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ประเมินผลงานคุณภาพต่อเนื่อง CQI</title>
  <style>
    /* Global Styles */
    * {
      box-sizing: border-box;
    }
    body {
      background: #f2f2f2;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
    }
    .container {
      max-width: 960px;
      margin: 40px auto;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    h2, h3, h4, label {
      margin-bottom: 15px;
      color: #444;
    }
    h2 {
      font-size: 28px;
      text-align: center;
    }
    h3 {
      font-size: 24px;
      text-align: center;
    }
    h4 {
      font-size: 20px;
      text-align: center;
      color: #666;
    }
    form {
      margin-top: 30px;
    }
    .question-group {
      margin-bottom: 40px;
      padding-bottom: 20px;
      border-bottom: 1px solid #e0e0e0;
    }
    .question-group:last-child {
      border-bottom: none;
    }
    .question-group label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
    }
    .question-item {
      margin-bottom: 15px;
      line-height: 1.6;
      padding-left: 10px;
    }
    .question-item input[type="checkbox"] {
      margin-right: 8px;
      transform: scale(1.1);
      vertical-align: middle;
    }
    .error-message {
      display: none;
      background: #ffdddd;
      color: #d8000c;
      padding: 12px;
      border-radius: 5px;
      margin: 20px 0;
      text-align: center;
    }
    .btn-submit {
      display: block;
      width: 100%;
      max-width: 300px;
      margin: 30px auto 0;
      padding: 15px;
      font-size: 18px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .btn-submit:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>เกณฑ์พิจารณาประเมินผลงานพัฒนาคุณภาพต่อเนื่อง (CQI Project) (ฉบับร่าง)</h2>
    <form id="cqiform" method="post" action="backend.php">
      <!-- รอบที่ 1 -->
      <div class="question-group" data-group="question1">
        <h3>รอบที่ 1</h3>
        <h4>สัดส่วนคะแนน 20 คะแนน</h4>
        <!-- ประเด็น 1.1 -->
        <div class="question-item" data-subgroup="question1_1">
          <label>ประเด็นการประเมิน 1.1 Gap identification (สัดส่วนคะแนน 10 คะแนน)</label>
          <input type="checkbox" name="question1_1[]" value="2"> No Gap Identification <u>ไม่มี</u>การวิเคราะห์ปัญห่าด้วยข้อมูลใดๆ<br>
          <input type="checkbox" name="question1_1[]" value="2"> Basic Gap Identification มีการระบุ gap เบื้องต้น จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง แต่ไม่มีการวิเคราะห์ปัญห่าด้วยข้อมูล<br>
          <input type="checkbox" name="question1_1[]" value="2"> Moderate Gap Identification มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง (Data review) แต่ไม่มีการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือจากแหล่งอื่น<br>
          <input type="checkbox" name="question1_1[]" value="2"> In-depth Gap Identification มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง (Data review) โดยการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือแหล่งอื่น แต่ยังไม่ได้วิเคราะห์หาสาเหตุรากของปัญหา<br>
          <input type="checkbox" name="question1_1[]" value="2"> RCA with management by fact มีการระบุ gap จากการเปรียบเทียบผลลัพธ์ที่ปฏิบัติจริงกับตัวชี้วัดที่คาดหวัง (Data review) โดยการใช้ข้อมูลเปรียบเทียบกับข้อมูลจากในอดีต หรือแหล่งอื่น อย่างครบถ้วนและถูกต้อง ในการวิเคราะห์เพื่อหาสาเหตุรากของปัญหา<br>
        </div>
        <!-- ประเด็น 1.2 -->
        <div class="question-item" data-subgroup="question1_2">
          <label>ประเด็นการประเมิน 1.2 Purpose (สัดส่วนคะแนน 5 คะแนน)</label>
          <input type="checkbox" name="question1_2[]" value="1"> <u><b>ไม่มี</b></u> คุณสมบัติตามเกณฑ์<br>
          <input type="checkbox" name="question1_2[]" value="1"> <u><b>มี</b></u> คุณสมบัติตามเกณฑ์ 1 ข้อ<br>
          <input type="checkbox" name="question1_2[]" value="1"> <u><b>มี</b></u> คุณสมบัติตามเกณฑ์ 2 ข้อ<br>
          <input type="checkbox" name="question1_2[]" value="1"> <u><b>มี</b></u> คุณสมบัติตามเกณฑ์ 3 ข้อ<br>
          <input type="checkbox" name="question1_2[]" value="1"> <u><b>มี</b></u> คุณสมบัติตามเกณฑ์ ครบถ้วน<br>
        </div>
        <!-- ประเด็น 1.3 -->
        <div class="question-item" data-subgroup="question1_3">
          <label>ประเด็นการประเมิน 1.3 Impact of project (สัดส่วนคะแนน 5 คะแนน)</label>
          <input type="checkbox" name="question1_3[]" value="1"> วัตถุประสงค์ <u>ไม่สอดคล้อง</u> กับ pain point หรือ <b>ระบุโดยอ้อม</b> หรือ <b>ระบุไม่ชัดเจน</b><br>
          <input type="checkbox" name="question1_3[]" value="1"> วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับหน่วยงาน</b><br>
          <input type="checkbox" name="question1_3[]" value="1"> วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับกลุ่มงาน</b><br>
          <input type="checkbox" name="question1_3[]" value="1"> วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับระบบงานสำคัญ/PCT</b><br>
          <input type="checkbox" name="question1_3[]" value="1"> วัตถุประสงค์สอดคล้องกับ pain point <b>ระดับระบบงานสำคัญ/PCT</b><br>
        </div>
      </div>

      <!-- รอบที่ 2 -->
      <div class="question-group" data-group="question2">
        <h3>รอบที่ 2</h3>
        <h4>สัดส่วนคะแนน 30 คะแนน</h4>
        <div class="question-item" data-subgroup="question2">
          <label>ประเด็นการประเมิน Process design</label>
          <input type="checkbox" name="question2[]" value="6"> ขั้นตอนดำเนินการ <b>ไม่สอดคล้อง</b> กับวัตถุประสงค์ หรือ สอดคล้องแต่ <b>ไม่ชัดเจน</b><br>
          <input type="checkbox" name="question2[]" value="6"> มีขั้นตอนการดำเนินการเพียง <b>1 ประเด็น</b><br>
          <input type="checkbox" name="question2[]" value="6"> มีขั้นตอนการดำเนินการครอบคลุม <b>2 ประเด็น</b><br>
          <input type="checkbox" name="question2[]" value="6"> มีขั้นตอนการดำเนินการครอบคลุม <b>3 ประเด็น</b><br>
          <input type="checkbox" name="question2[]" value="6"> มีขั้นตอนการดำเนินการ <u><b>ครอบคลุมทุกประเด็น</b></u><br>
        </div>
      </div>

      <!-- รอบที่ 3 -->
      <div class="question-group" data-group="question3">
        <h3>รอบที่ 3</h3>
        <h4>สัดส่วนคะแนน 20 คะแนน</h4>
        <div class="question-item" data-subgroup="question3">
          <label>ประเด็นการประเมิน Performance (ครั้งที่ 1)</label>
          <input type="checkbox" name="question3[]" value="4"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ (process outcome)<br>
          <input type="checkbox" name="question3[]" value="4"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) <u><b>แต่</b></u> ไม่บรรลุเป้าหมาย<br>
          <input type="checkbox" name="question3[]" value="4"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) และมีแนวโน้มดีขึ้น<br>
          <input type="checkbox" name="question3[]" value="4"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) บรรลุเป้าหมาย แต่ยังไม่สามารถเทียบเคียงกับที่อื่นได้<br>
          <input type="checkbox" name="question3[]" value="4"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) <u><b>บรรลุเป้าหมาย</b></u> และมี <u><b>ผลลัพธ์ดีเยี่ยม</b></u><br>
        </div>
      </div>

      <!-- รอบที่ 4 -->
      <div class="question-group" data-group="question4">
        <h3>รอบที่ 4</h3>
        <h4>สัดส่วนคะแนน 30 คะแนน</h4>
        <div class="question-item" data-subgroup="question4">
          <label>ประเด็นการประเมิน Performance (ครั้งที่ 2)</label>
          <input type="checkbox" name="question4[]" value="6"> มีผลลัพธ์การดำเนินการตามตัวชี้วัดขั้นพื้นฐาน เช่น ผลลัพธ์เชิงกระบวนการ (process outcome)<br>
          <input type="checkbox" name="question4[]" value="6"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) <u><b>แต่</b></u> ไม่บรรลุเป้าหมาย<br>
          <input type="checkbox" name="question4[]" value="6"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) และมีแนวโน้มดีขึ้น<br>
          <input type="checkbox" name="question4[]" value="6"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) บรรลุเป้าหมาย แต่ยังไม่สามารถเทียบเคียงกับที่อื่นได้<br>
          <input type="checkbox" name="question4[]" value="6"> มีผลลัพธ์การดำเนินการร่วมกับ มีผลลัพธ์ตามตัวชี้วัด (outcome) <u><b>บรรลุเป้าหมาย</b></u> และมี <u><b>ผลลัพธ์ดีเยี่ยม</b></u><br>
        </div>
      </div>

      <div class="error-message" id="error-message">กรุณาเลือกตัวเลือกอย่างน้อย 1 ตัวในแต่ละหัวข้อ</div>
      <input type="submit" class="btn-submit" value="ส่งข้อมูล">
    </form>
  </div>

  <script>
    // Validate form: ตรวจสอบว่าทุกกลุ่มมีการเลือก checkbox อย่างน้อย 1 ตัว
    document.getElementById("cqiform").addEventListener("submit", function(e) {
      e.preventDefault(); // ป้องกันการส่งฟอร์มทันที
      const groups = document.querySelectorAll(".question-group");
      let isValid = true;
      groups.forEach(group => {
        const checked = group.querySelectorAll("input[type='checkbox']:checked");
        if (checked.length === 0) {
          isValid = false;
        }
      });
      if (!isValid) {
        document.getElementById("error-message").style.display = "block";
        return;
      } else {
        document.getElementById("error-message").style.display = "none";
        this.submit();
      }
    });
  </script>
</body>
</html>
