<?php
session_start();
include('../connect.php');

// หากผู้ใช้เป็นติวเตอร์ จะสามารถเข้าถึงเนื้อหาด้านล่างได้

// กำหนดตัวแปร $user_name
$user_name = '';

if (isset($_SESSION['name_lastname'])) {
    $user_name = $_SESSION['name_lastname'];
} elseif (isset($_SESSION['name_lastname_tutor'])) {
    $user_name = $_SESSION['name_lastname_tutor'];
} elseif (isset($_SESSION['name_provider'])) {
    $user_name = $_SESSION['name_provider'];
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['image_room'] = $_POST['image_room'];
    $_SESSION['room_id'] = $_POST['room_id'];
    $_SESSION['room_name'] = $_POST['room_name'];
    $_SESSION['room_details'] = $_POST['room_details'];
    $_SESSION['room_price'] = $_POST['room_price']; 
    $_SESSION['room_type'] = $_POST['room_type']; 
    $_SESSION['opacity_room'] = $_POST['opacity_room']; 
    $_SESSION['address_room'] = $_POST['address_room']; 
    $_SESSION['id_provider'] = $_POST['id_provider']; 

}
// นำข้อมูลจากเซสชันมาแสดง
$image_room = isset($_SESSION['image_room']) ? $_SESSION['image_room'] : '';
$room_id = isset($_SESSION['room_id']) ? $_SESSION['room_id'] : '';
$room_name = isset($_SESSION['room_name']) ? $_SESSION['room_name'] : '';
$room_details = isset($_SESSION['room_details']) ? $_SESSION['room_details'] : '';
$room_price = isset($_SESSION['room_price']) ? $_SESSION['room_price'] : '';
$room_type = isset($_SESSION['room_type']) ? $_SESSION['room_type'] : '';
$opacity_room = isset($_SESSION['opacity_room']) ? $_SESSION['opacity_room'] : '';
$address_room = isset($_SESSION['address_room']) ? $_SESSION['address_room'] : '';
$id_provider = isset($_SESSION['id_provider']) ? $_SESSION['id_provider'] : '';

//     // Query เพื่อนำวันจองที่มีอยู่ในระบบ
//     $sql = "
//     SELECT roombooking.date_start, roombooking.date_end 
//     FROM details_roombooking 
//     JOIN roombooking ON details_roombooking.id_bookingroom = roombooking.id_bookingroom 
//     WHERE details_roombooking.id_room = ?
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $room_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $reserved_dates = [];
//     while ($row = $result->fetch_assoc()) {
//     // บันทึกวันที่เริ่มและสิ้นสุดลงในอาร์เรย์
//     $reserved_dates[] = [
//         'start' => $row['date_start'],
//         'end' => $row['date_end']
//     ];
//     }

//     // ส่งคืนวันที่จองที่มีอยู่ในรูปแบบ JSON
//     echo json_encode($reserved_dates);
// 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/project end/css/booking_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet" />

    <style>
        /* .flatpickr-input {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            background: #f9f9f9;
            padding: 10px;
            font-size: 16px;
        } */

        .btn-custom {
            background: #007bff;
            color: #fff;
        }

        .btn-custom:hover {
            background: #0056b3;
            color: #fff;
        } .stepper {
            display: flex;
            justify-content: space-around; 
            margin-bottom: 10px;
            margin-top: 10px; /* ขยับลงมานิดนึง */
            margin-left: 30%; /* ขยับไปทางขวามือ */
            position: relative;

        }
        
        .step {
            text-align: center;
            flex-basis: 250px; /* เพิ่มความกว้าง */
            position: relative;
            z-index: 1;
        }
        
        .step::before, .step::after {
            content: '';
            position: absolute;
            top: 40%;
            transform: translateY(-50%);
            height: 4px;
            background-color: #e9ecef;
            z-index: -1;
        }
        
        .step::before {
            left: -20px;
            right: 50%;
        }
        
        .step::after {
            right: -20px;
            left: 50%;
        }
        
        .step:first-child::before {
            content: none;
        }
        
        .step:last-child::after {
            content: none;
        }
        
        .step-circle {
            width: 30px;
            height: 30px;
            line-height: 30px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        .step.active .step-circle {
            background-color: #28a745;
            transform: scale(1.1);
        }

        .step.active::before, .step.active::after {
            background-color: #28a745; /* สีเส้นเมื่อ active */
        }
        
        .step-title {
            margin-top: 5px;
            font-weight: bold;
            font-size: 0.9rem;
            color: #333;
        }

        .step.active .step-title {
            color: #28a745;
        }
    /* ตั้งค่าพื้นหลังและสไตล์โดยรวมของปฏิทิน */
.fc {
    background-color: #ffffff; /* พื้นหลังขาวสะอาด */
    border-radius: 10px; /* มุมโค้ง */
    padding: 15px; /* ช่องว่างรอบปฏิทิน */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* เงา */
    overflow: hidden; /* หรือ auto */
}

/* ตั้งค่าสไตล์ของแต่ละวัน */
.fc-daygrid-day {
    border: 1px solid #e0e0e0; /* ขอบสีเทา */
    border-radius: 5px; /* มุมโค้ง */
    transition: background-color 0.3s, border 0.3s; /* เพิ่มความนุ่มนวล */
}

/* สีพื้นหลังและสีข้อความสำหรับวันที่เลือก */
.fc-daygrid-day.fc-day-selected {
    background-color: #007bff; /* สีน้ำเงิน */
    color: white; /* ข้อความขาว */
}

/* ตั้งค่าสไตล์สำหรับวันที่เริ่มจอง */
.fc .start-booking {
    background-color: #4CAF50; /* สีเขียวสำหรับเริ่มจอง */
    color: white; /* ข้อความขาว */
    border: 1px solid #388E3C; /* สีเขียวเข้มสำหรับกรอบ */
    border-radius: 5px; /* มุมโค้ง */
}

/* ตั้งค่าสไตล์สำหรับวันที่สิ้นสุดจอง */
.fc .end-booking {
    background-color: #F44336; /* สีแดงสำหรับวันสิ้นสุด */
    color: white; /* ข้อความขาว */
    border: 1px solid #D32F2F; /* สีแดงเข้มสำหรับกรอบ */
    border-radius: 5px; /* มุมโค้ง */
}

/* ตั้งค่าสไตล์สำหรับระยะเวลาการจอง */
.fc .booking-duration {
    background-color: #2196F3; /* สีน้ำเงินสำหรับระยะเวลาการจอง */
    color: white; /* ข้อความขาว */
    border: 1px solid #1976D2; /* สีน้ำเงินเข้มสำหรับกรอบ */
    border-radius: 5px; /* มุมโค้ง */
}

/* ปรับสไตล์ข้อความใน header */
.fc-toolbar-title {
    font-size: 1.5em; /* ขนาดตัวอักษร */
    font-weight: bold; /* ตัวหนา */
    color: #333; /* สีข้อความ */
    margin-bottom: 10px; /* ช่องว่างด้านล่าง */
}

/* ตั้งค่าสไตล์สำหรับปุ่มนavigating (prev, next) */
.fc-button {
    background-color: #007bff; /* สีน้ำเงิน */
    color: white; /* ข้อความขาว */
    border: none; /* ไม่มีกรอบ */
    border-radius: 5px; /* มุมโค้ง */
    padding: 5px 10px; /* ช่องว่างภายใน */
    transition: background-color 0.3s; /* เพิ่มความนุ่มนวล */
}

.fc-button:hover {
    background-color: #0056b3; /* สีเข้มเมื่อชี้ */
}

/* ปรับขนาดของปฏิทิน */
.calendar {
    width: 80%; /* ความกว้าง */
    margin:20px 0 ; /* จัดกึ่งกลาง */
    overflow: hidden; /* หรือ auto */
    text-decoration:none;
}
.fc-daygrid {
    height: auto; /* ปรับความสูงตามต้องการ */
}
.booked {
    color: white; /* สีข้อความให้เด่น */
    font-weight: bold; /* ทำให้ข้อความตัวหนา */
}
 </style>

</head>
<body>
<a href="all_meetingroom.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">จองห้องประชุม</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 50px; margin-top: 32px;"  aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_meetingroom.php" style="text-decoration:none;">ห้องประชุม</a></li>
                    <li class="breadcrumb-item active" aria-current="page">รายละเอียดการจอง</li>
                </ol>
                </nav>
                <div class="stepper">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <div class="step-title">รายละเอียดการจอง</div>
                    </div>
                    <div class="step ">
                        <div class="step-circle">2</div>
                        <div class="step-title">เลือกอาหาร</div>
                    </div>
                    <div class="step ">
                        <div class="step-circle">3</div>
                        <div class="step-title">ยืนยันการจอง</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">4</div>
                        <div class="step-title">ชำระเงิน</div>
                    </div>
                    <div class="step ">
                        <div class="step-circle">5</div>
                        <div class="step-title">เสร็จสิ้น</div>
                    </div>
                </div>
        </div>     
        <form action="booking_steptwo_db.php" method="POST" id="bookingForm" onsubmit="return validateForm();">
            <div class="blockcenter_3">
                <div class="p-detail">
                <img class="image_room" src="<?php echo htmlspecialchars($image_room); ?>" alt="รูปห้องประชุม">
                <p><strong>ชื่อห้องประชุม:</strong> <?php echo htmlspecialchars($room_name); ?></p>
                <p><strong>รหัสห้อง:</strong> <?php echo htmlspecialchars($room_id); ?></p> 
                <p><strong>ความจุห้อง:</strong> <?php echo htmlspecialchars($opacity_room); ?> คน</p>
                <p><strong>ประเภท:</strong> <?php echo htmlspecialchars($room_type); ?> </p>
                <p><strong>ราคา:</strong> ฿<?php echo htmlspecialchars($room_price); ?> บาท/วัน</p> 
                <p><strong>ที่ตั้ง:</strong> <?php echo htmlspecialchars($address_room); ?> </p> 
                </div>
                <div class="line-height"></div>
                <div class="input-detail">
                    
                    <div class="input">
                        <input type="hidden" name="image_room" value="<?php echo htmlspecialchars($image_room); ?>">
                        <input type="hidden" name="room_name" value="<?php echo htmlspecialchars($room_name); ?>">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>">
                        <input type="hidden" name="opacity_room" value="<?php echo htmlspecialchars($opacity_room); ?>">
                        <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($room_type); ?>">
                        <input type="hidden" name="room_price" value="<?php echo htmlspecialchars($room_price); ?>">
                        <input type="hidden" name="address_room" value="<?php echo htmlspecialchars($address_room); ?>">

                        <input type="hidden" name="id_provider" value="<?php echo htmlspecialchars($id_provider); ?>">

                        <!-- Other input fields -->
                         <div class="text-head-con">
                        <h class="text-head"><strong>ผู้จองห้องประชุม</strong></h></div>

                        <div class="form__group">
                            <input type="text" name="name" class="form__input" placeholder="Name Lastname" required="" value="<?php echo htmlspecialchars($user_name); ?>" style="background-color: #e8e8e8;"/>
                            <label for="name" class="form__label">Name Lastname</label>
                        </div>
                        <div class="form__group">
                            <input type="email" name="email" class="form__input" placeholder="Email" required="" value="<?php echo htmlspecialchars($email); ?>" style="background-color: #e8e8e8;"/>
                            <label for="email" class="form__label">Email</label>
                        </div>
                        <div class="form__group">
                            <input type="number" name="number" class="form__input" placeholder="Number Phone" required=""/>
                            <label for="number" class="form__label">Number Phone</label>
                        </div>

                        <div id="calendar" class="calendar"></div>

                        <div class="form__group">
                            <input type="date" id="date-start" name="date_start" class="form__input" placeholder="Start Date" required>
                            <label for="date-start" class="form__label">Start Date</label>
                        </div>
                        <div class="form__group">
                            <input type="date" id="date-end" name="date_end" class="form__input" placeholder="End Date" required>
                            <label for="date-end" class="form__label">End Date</label>
                        </div>
                        <div class="form__group">
                            <input type="text" id="duration" name="day_book" class="form__input" placeholder="Duration (Days)" readonly required>
                            <label for="duration" class="form__label">Duration (Days)</label>
                        </div>
                        <button class="btn-submit" type="submit">จอง</button>
                        <button class="btn-cancel" type="button" onclick="location.href='all_meetingroom.php'">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
         <!-- ลิงก์ JS ของ FullCalendar -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


        <script>
       
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var roomId = '<?php echo $room_id; ?>';

        var calendar = new FullCalendar.Calendar(calendarEl, {
            selectable: true,
            events: 'fetch_bookings.php?room_id=' + roomId,

            dateClick: function(info) {
                let startDateInput = document.getElementById('date-start');
                let endDateInput = document.getElementById('date-end');
                let durationInput = document.getElementById('duration');

                // ตรวจสอบวันปัจจุบัน
                let today = new Date();
                let selectedDate = new Date(info.dateStr);
                selectedDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    alert("ไม่สามารถเลือกวันที่ก่อนวันปัจจุบันได้");
                    return;
                }

                // ตรวจสอบว่ามีการจองในวันที่เลือกหรือไม่
                if (isDateBooked(selectedDate)) {
                    alert("วันที่ " + selectedDate.toLocaleDateString('th-TH') + " ไม่สามารถจองได้ เนื่องจากมีการจองแล้ว");
                    return;
                }

                // ฟังก์ชันล้างการมาร์กทั้งหมด
                function clearAllMarks() {
                    calendar.getEvents().forEach(event => {
                        if (['เริ่มจอง', 'วันสิ้นสุด', 'ระยะเวลาการจอง'].includes(event.title)) {
                            event.remove();
                        }
                    });
                    startDateInput.value = '';
                    endDateInput.value = '';
                    durationInput.value = '';
                }

                // กรณีที่ 1: ยังไม่มีการเลือกวันใด
                if (!startDateInput.value) {
                    startDateInput.value = info.dateStr;
                    calendar.addEvent({
                        title: 'เริ่มจอง',
                        start: info.dateStr,
                        allDay: true,
                        classNames: ['start-booking']
                    });
                    return;
                }

                // กรณีที่ 2: มีการเลือกวันเริ่มต้นแล้ว แต่ยังไม่มีวันสิ้นสุด
                if (startDateInput.value && !endDateInput.value) {
                    // ถ้าคลิกวันเดิม = เลือกจองวันเดียว
                    if (info.dateStr === startDateInput.value) {
                        endDateInput.value = info.dateStr;
                        durationInput.value = '1 วัน';
                        calendar.addEvent({
                            title: 'วันสิ้นสุด',
                            start: info.dateStr,
                            allDay: true,
                            classNames: ['end-booking']
                        });
                        return;
                    }

                    // ถ้าคลิกวันอื่น = เลือกช่วงวัน
                    let startDate = new Date(startDateInput.value);
                    let endDate = new Date(info.dateStr);

                    // ตรวจสอบว่าวันสิ้นสุดไม่มาก่อนวันเริ่มต้น
                    if (endDate < startDate) {
                        alert("วันสิ้นสุดต้องไม่มาก่อนวันเริ่มต้น");
                        return;
                    }

                    // ตรวจสอบว่ามีวันที่ไม่ว่างในช่วงที่เลือกหรือไม่
                    if (hasUnavailableDatesInRange(startDate, endDate)) {
                        alert("ไม่สามารถจองช่วงวันนี้ได้ เนื่องจากมีบางวันที่ถูกจองไปแล้ว");
                        return;
                    }

                    endDateInput.value = info.dateStr;
                    markBookingDuration(startDateInput.value, endDateInput.value, calendar);
                    return;
                }

                // กรณีที่ 3: มีการเลือกทั้งวันเริ่มต้นและวันสิ้นสุดแล้ว = ยกเลิกการเลือกทั้งหมด
                clearAllMarks();
            }
        });

        calendar.render();

        // ฟังก์ชันตรวจสอบว่าวันที่ถูกจองแล้วหรือไม่
        function isDateBooked(date) {
            return calendar.getEvents().some(event => {
                let eventDate = new Date(event.start);
                return eventDate.toDateString() === date.toDateString() && event.title === 'ไม่ว่าง';
            });
        }

        // ฟังก์ชันตรวจสอบว่ามีวันที่ไม่ว่างในช่วงที่เลือกหรือไม่
        function hasUnavailableDatesInRange(startDate, endDate) {
            let currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                if (isDateBooked(currentDate)) {
                    return true;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
            return false;
        }

        function markBookingDuration(startDate, endDate, calendar) {
            let start = new Date(startDate);
            let end = new Date(endDate);
            
            // คำนวณจำนวนวัน
            const dayDiff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('duration').value = dayDiff + ' วัน';

            // ล้างการมาร์กระยะเวลาเก่า
            calendar.getEvents().forEach(event => {
                if (['เริ่มจอง', 'วันสิ้นสุด', 'ระยะเวลาการจอง'].includes(event.title)) {
                    event.remove();
                }
            });

            // มาร์กวันเริ่มต้น
            calendar.addEvent({
                title: 'เริ่มจอง',
                start: startDate,
                allDay: true,
                classNames: ['start-booking']
            });

            // มาร์กวันสิ้นสุด
            calendar.addEvent({
                title: 'วันสิ้นสุด',
                start: endDate,
                allDay: true,
                classNames: ['end-booking']
            });

            // มาร์กวันระหว่างการจอง
            let currentDate = new Date(start);
            while (currentDate <= end) {
                if (currentDate > start && currentDate < end) {
                    calendar.addEvent({
                        title: 'ระยะเวลาการจอง',
                        start: new Date(currentDate),
                        allDay: true,
                        classNames: ['booking-duration']
                    });
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
        }
    });

    function validateForm() {
        const startDate = document.getElementById('date-start').value;
        const endDate = document.getElementById('date-end').value;
        const duration = document.getElementById('duration').value;
        
        if (!startDate || !endDate || !duration) {
            alert("กรุณาเลือกวันที่เริ่มต้นและวันที่สิ้นสุดก่อนส่งฟอร์ม");
            return false;
        }
        return true;
    }
    </script>

</body>
</html>

