<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่ง `id` มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลห้องประชุมตาม `id`
    $sql = "SELECT * FROM course WHERE id_course = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลคอร์สติวนี้";
        exit();
    }
} else {
    echo "ไม่พบ ID ของคอร์สติว";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>form edit course</title>
    <link rel="stylesheet" href="/project end/css/form_addroom.css">
    <link rel="stylesheet" href="/project end/css/modal.css">


</head>
<body>
    <div class="container">
        <h2 class="text-content">แก้ไขข้อมูลคอร์สติวของคุณ</h2>
        <form action="update_course.php" method="post" enctype="multipart/form-data">
        <!-- ฟิลด์ที่มีอยู่แล้ว -->
        <input type="hidden" name="id_course" value="<?php echo $course['id_course']; ?>">

        <div class="form__group">
            <input type="text" name="name_course" class="form__input" placeholder="Name Course" value="<?php echo $course['name_course']; ?>" required="" />
            <label for="name" class="form__label">Name Course</label>
            </div>

            <div class="form__group">
            <textarea name="detail_course" class="form__input" placeholder="Detail Course" value="" required=""><?php echo $course['detail_course'];  ?></textarea>
            <label for="name" class="form__label">Detail Course</label>
            </div>
            
            <!-- <div class="twogroup">
                <div class="form__group">
                <input type="date" name="date_course" class="form__input" placeholder="Date Course"  value="<?php echo $course['date_course']; ?>" required="" />
                <label for="name" class="form__label">Date Course</label>
                </div>
                <div class="form__group">
                <input type="number" name="day_course" class="form__input" placeholder="How many days"  value="<?php echo $course['day_course']; ?>" readonly>
                <label for="name" class="form__label">How many days</label>
                </div>
            </div>     -->

            <div class="twogroup">
                <div class="form__group">
                <input type="date" id="date_course" name="date_course" class="form__input" placeholder="Date Course" value="<?php echo $course['date_course']; ?>" required="" />
                <label for="name" class="form__label">Date Course</label>
                </div>
                <div class="form__group">
                <input type="date" id="date_course_end" name="date_course_end" class="form__input" placeholder="Date Course End" value="<?php echo $course['date_course_end']; ?>" required="" />
                <label for="name" class="form__label">Date End</label>
                </div>
            </div>    
            <div class="form__group">
                <input type="number" id="day_course" name="day_course" class="form__input" placeholder="Durations(days)" value="<?php echo $course['day_course']; ?>" readonly>
                <label for="name" class="form__label">Durations(days)</label>
            </div>

            <div class="twogroup">
                <div class="form__group">
                    <input type="time" id="start_time" name="start_time" class="form__input" placeholder="Start Time" value="<?php echo $course['start_time']; ?>" required="" />
                    <label for="start_time" class="form__label">Start Time</label>
                </div>
                <div class="form__group">
                    <input type="time" id="end_time" name="end_time" class="form__input" placeholder="End Time" value="<?php echo $course['end_time']; ?>" required="" />
                    <label for="end_time" class="form__label">End Time</label>
                </div>
            </div>
            <div class="form__group">
                <input type="text" id="duration_display" name="duration_hour" class="form__input" placeholder="Duration(hours)" value="<?php echo $course['duration_hour']; ?>" readonly>
                <label for="duration_display" class="form__label">Duration(hours)</label>
            </div>

            <div class="form__group">
            <input type="number" name="price_course" class="form__input" placeholder="Price"  value="<?php echo $course['price_course']; ?>" required="" />
            <label for="name" class="form__label">Price</label>
            </div>
            
            <div class="form__group">
                <select name="meeting_type" class="form__select" required="">
                    <option value="" disabled selected>Select Meeting Type</option>
                    <option value="online" <?php echo ($course['meeting_type'] == 'online') ? 'selected' : ''; ?>>Online</option>
                    <option value="onsite" <?php echo ($course['meeting_type'] == 'onsite') ? 'selected' : ''; ?>>Onsite</option>
                </select>
                <label for="name" class="form__label">Select Meeting Type</label>
            </div>

            <div class="form__group">
            <input type="text" name="room_course" class="form__input" placeholder="Room Course"  value="<?php echo $course['room_course']; ?>" required="" />
            <label for="name" class="form__label">Room Course</label>
            </div>
            
            <div class="form__group">
            <img id="imagePreview" class="preview"src="<?php echo $course['image_course']; ?>" alt="Image Preview" /><br>
            <input type="file" name="image_course" class="form__input" accept="/image/*" id="imageInput" required="" />
            <label for="name" class="form__label">prevew</label>
            </div>

            <div class="form__group">
            <textarea name="label_doc" class="form__input" placeholder="Label Document" required=""></textarea>
            <label for="name" class="form__label">Document</label>
            </div>

            <div class="form__group">
            <img  class="flie_doc"  alt="Document" style="display: none;" /><br>
            <input type="file" name="flie_doc" class="form__input" accept=""  required="" />
            <label for="name" class="form__label">Document (pdf, doc, docx, txt)</label>
            </div>

            <!-- <button type="submit" class="submit">Submit</button> -->
            <button type="button" class="submit" data-toggle="modal" data-target="#confirmModal">Submit</button>
            <button type="reset" class="cancel" onclick="window.location.href='edit_course.php'">Cancel</button>
    </form>
    </div>
    <!-- Modal Confirmation -->
    <div class="modal" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ยืนยันการดำเนินการ</h5>
                    <button id="closeModal" class="close">&times;</button>
                </div>
                <div class="modal-body">
                    คุณต้องการแก้ไขข้อมูลนี้ใช่ไหม?
                </div>
                <div class="modal-footer">
                    <button id="cancel" class="btn-secondary">ยกเลิก</button>
                    <button id="confirm" class="btn-primary">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    document.getElementById('imageInput').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : 'ไม่มีไฟล์ที่เลือก';
    document.querySelector('.form__label').textContent = fileName;
    });
</script>
<script>
        const modal = document.getElementById('confirmModal');
        const form = document.querySelector('form');

        document.querySelector('.submit').onclick = function() {
            modal.classList.add('show'); // เพิ่มคลาส show เพื่อแสดง modal
        }

        document.getElementById('closeModal').onclick = function() {
            modal.classList.remove('show'); // ลบคลาส show เพื่อปิด modal
        }

        document.getElementById('cancel').onclick = function() {
            modal.classList.remove('show'); // ลบคลาส show เพื่อปิด modal
        }

        document.getElementById('confirm').onclick = function() {
            form.submit(); // ส่งฟอร์ม
        }
    </script>
    <script>
  const dateCourseInput = document.getElementById('date_course');
  const dateCourseEndInput = document.getElementById('date_course_end');
  const dayCourseInput = document.getElementById('day_course');
  const startTimeInput = document.getElementById('start_time');
  const endTimeInput = document.getElementById('end_time');
  const durationDisplayInput = document.getElementById('duration_display');

  function calculateDuration() {
    const startDate = new Date(dateCourseInput.value);
    const endDate = new Date(dateCourseEndInput.value);

    if (startDate && endDate && startDate <= endDate) {
      const diffTime = Math.abs(endDate - startDate);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24) + 1); // บวก 1 เพราะนับวันที่เริ่มต้นด้วย
      dayCourseInput.value = diffDays;
      calculateTotalDuration(); // คำนวณชั่วโมงทั้งหมดหลังจากได้จำนวนวันแล้ว
    } else {
      dayCourseInput.value = 0;
      durationDisplayInput.value = '';
    }
  }

  function calculateTimeDuration() {
    const startTime = startTimeInput.value;
    const endTime = endTimeInput.value;

    if (startTime && endTime) {
      const [startHours, startMinutes] = startTime.split(':').map(Number);
      const [endHours, endMinutes] = endTime.split(':').map(Number);

      const startTotalMinutes = startHours * 60 + startMinutes;
      const endTotalMinutes = endHours * 60 + endMinutes;

      let durationMinutes = endTotalMinutes - startTotalMinutes;

      if (durationMinutes < 0) {
        durationMinutes += 24 * 60; // กรณีข้ามวัน
      }

      const hours = Math.floor(durationMinutes / 60);
      const minutes = durationMinutes % 60;

      return { hours, minutes }; // คืนค่าเป็น object ชั่วโมงและนาที
    }
    return null;
  }

  function calculateTotalDuration() {
    const timeDuration = calculateTimeDuration();

    if (timeDuration) {
      const dayCount = parseInt(dayCourseInput.value, 10);
      const totalHours = dayCount * (timeDuration.hours + (timeDuration.minutes / 60));
      durationDisplayInput.value = `${Math.floor(totalHours)} ชม.`; // แสดงผลชั่วโมงรวมทั้งหมด
    } else {
      durationDisplayInput.value = '';
    }
  }

  dateCourseInput.addEventListener('change', calculateDuration);
  dateCourseEndInput.addEventListener('change', calculateDuration);
  startTimeInput.addEventListener('change', calculateTotalDuration);
  endTimeInput.addEventListener('change', calculateTotalDuration);
</script>
</body>
</html>
