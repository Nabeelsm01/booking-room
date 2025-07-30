<?php 
    session_start();
    include('../connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account Provider</title>
    <link rel="stylesheet" href="/project end/css/form_addroom.css">
</head>
<body>
    <div class="container">
        <h2 class="text-content">เพิ่มข้อมูลคอร์สติวของคุณ</h2>
        <form action="add_coursedb.php" method="post" enctype="multipart/form-data">
            <?php if (isset($_SESSION['errors'])) : ?>
                <div class="errors">
                    <h3>
                        <?php 
                            echo $_SESSION['errors'];
                            unset($_SESSION['errors']);
                        ?>
                    </h3>
                </div>    
            <?php endif ?>
            <div class="form__group">
            <input type="text" name="name_course" class="form__input" placeholder="Name Course" required="" />
            <label for="name" class="form__label">Name Course</label>
            </div>

            <div class="form__group">
            <textarea name="detail_course" class="form__input" placeholder="Detail Course" required=""></textarea>
            <label for="name" class="form__label">Detail Course</label>
            </div>
            
            <div class="twogroup">
                <div class="form__group">
                <input type="date" id="date_course" name="date_course" class="form__input" placeholder="Date Course" required="" />
                <label for="name" class="form__label">Date Course</label>
                </div>
                <div class="form__group">
                <input type="date" id="date_course_end" name="date_course_end" class="form__input" placeholder="Date Course End" required="" />
                <label for="name" class="form__label">Date End</label>
                </div>
            </div>    
            <div class="form__group">
                <input type="number" id="day_course" name="day_course" class="form__input" placeholder="Durations(days)" readonly>
                <label for="name" class="form__label">Durations(days)</label>
            </div>

            <div class="twogroup">
                <div class="form__group">
                    <input type="time" id="start_time" name="start_time" class="form__input" placeholder="Start Time" required />
                    <label for="start_time" class="form__label">Start Time</label>
                </div>
                <div class="form__group">
                    <input type="time" id="end_time" name="end_time" class="form__input" placeholder="End Time" required />
                    <label for="end_time" class="form__label">End Time</label>
                </div>
            </div>
            <div class="form__group">
                <input type="text" id="duration_display" name="duration_hour" class="form__input" placeholder="Duration(hours)" readonly>
                <label for="duration_display" class="form__label">Duration(hours)</label>
            </div>


            <div class="form__group">
            <input type="number" name="price_course" class="form__input" placeholder="Price" required="" />
            <label for="name" class="form__label">Price</label>
            </div>
            
            <div class="form__group">
                <select name="meeting_type" class="form__select" required>
                    <option value="" abled selected>Select Meeting Type</option>
                    <option value="online">Online</option>
                    <option value="onsite">Onsite</option>
                </select>
                <label for="name" class="form__label">Select Meeting Type</label>
            </div>

            <div class="form__group">
            <input type="text" name="room_course" class="form__input" placeholder="Room Course" required="" />
            <label for="name" class="form__label">Room Course</label>
            </div>
            
            <div class="form__group">
            <img id="imagePreview" class="preview" src="#" alt="Image Preview" style="display: none;" /><br>
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

            <button type="submit" class="submit" name="form_tutor">Submit</button>
            <button type="reset" class="cancel" onclick="window.location.href='tutor.php'">Cancel</></button>
        </form>
       
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
