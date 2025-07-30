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
    $_SESSION['image_course'] = $_POST['image_course'];
    $_SESSION['id_course'] = $_POST['id_course'];
    $_SESSION['name_course'] = $_POST['name_course'];
    $_SESSION['detail_course'] = $_POST['detail_course'];
    $_SESSION['price_course'] = $_POST['price_course']; 
    $_SESSION['meeting_type'] = $_POST['meeting_type']; 
    $_SESSION['room_course'] = $_POST['room_course']; 
    $_SESSION['day_course'] = $_POST['day_course']; 
    $_SESSION['date_course'] = $_POST['date_course'];   
    $_SESSION['date_course_end'] = $_POST['date_course_end'];
    $_SESSION['start_time'] = $_POST['start_time'];
    $_SESSION['end_time'] = $_POST['end_time'];
    $_SESSION['duration_hour'] = $_POST['duration_hour'];
    $_SESSION['id_tutor'] = $_POST['id_tutor']; 

}
$image_course = isset($_SESSION['image_course']) ? $_SESSION['image_course'] : '';
$id_course = isset($_SESSION['id_course']) ? $_SESSION['id_course'] : 0;
$name_course = isset($_SESSION['name_course']) ? $_SESSION['name_course'] : '';
$detail_course = isset($_SESSION['detail_course']) ? $_SESSION['detail_course'] : '';
$price_course = isset($_SESSION['price_course']) ? intval($_SESSION['price_course']) : 0;
$meeting_type = isset($_SESSION['meeting_type']) ? $_SESSION['meeting_type'] : '';
$room_course = isset($_SESSION['room_course']) ? $_SESSION['room_course'] : '';
$day_course = isset($_SESSION['day_course']) ? $_SESSION['day_course'] : '';
$date_course = isset($_SESSION['date_course']) ? $_SESSION['date_course'] : '';
$date_course_end = isset($_SESSION['date_course_end']) ? $_SESSION['date_course_end'] : '';
$start_time = isset($_SESSION['start_time']) ? $_SESSION['start_time'] : '';
$end_time = isset($_SESSION['end_time']) ? $_SESSION['end_time'] : '';
$duration_hour = isset($_SESSION['duration_hour']) ? $_SESSION['duration_hour'] : '';
$id_tutor = isset($_SESSION['id_tutor']) ? $_SESSION['id_tutor'] : '';

$sqlSelecttutor = "
    SELECT name_lastname_tutor
    FROM course 
    JOIN tutor ON course.id_tutor = tutor.id_tutor 
    WHERE tutor.id_tutor = ? ";

    $stmt = $conn->prepare($sqlSelecttutor);
    $stmt->bind_param('i', $id_tutor);
    $stmt->execute();

    // ดึงผลลัพธ์
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name_tutor = $row['name_lastname_tutor'];
 
    } else {
    echo "No found.";
    }
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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
            margin-left: 35.6%; /* ขยับไปทางขวามือ */
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
<a href="all_course.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">ลงทะเบียนคอร์สติว</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 50px; margin-top: 32px;"  aria-label="breadcrumb" >
             <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_course.php" style="text-decoration:none;">คอร์สติว</a></li>
                    <li class="breadcrumb-item" aria-current="page" >ลงทะเบียนและชำระเงิน</li>
                </ol>
                </nav>
                <div class="stepper">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <div class="step-title">คอร์สติว</div>
                    </div>
                    <div class="step active">
                        <div class="step-circle">2</div>
                        <div class="step-title">ลงทะเบียนและชำระเงิน</div>
                    </div>
                    <div class="step ">
                        <div class="step-circle">3</div>
                        <div class="step-title">เสร็จสิ้น</div>
                    </div>
                  
                </div>
        </div>     
        <form id="myForm" action="register_steptwo_db.php" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">
            <div class="blockcenter_3">
                <div class="p-detail">
                <img class="image_room" src="<?php echo htmlspecialchars($image_course); ?>" alt="รูปคอร์ส">
                <p><strong>ชื่อคอร์สติว:</strong> <?php echo htmlspecialchars($name_course); ?></p>
                <p><strong>รหัสคอร์ส:</strong> <?php echo htmlspecialchars($id_course); ?></p> 
                <p><strong>ราคา:</strong> <?php echo htmlspecialchars($price_course); ?> ฿</p>
                <p><strong>ช่องทาง:</strong> <?php echo htmlspecialchars($meeting_type); ?> </p>
                <p><strong>ระยะเวลา:</strong> <?php echo htmlspecialchars($day_course); ?> วัน</p> 
                <p><strong>วันที่เริ่มเรียน:</strong> <?php echo htmlspecialchars($date_course); ?> </p> 
                <p><strong>วันที่สิ้นสุดเรียน:</strong> <?php echo htmlspecialchars($date_course_end); ?> </p> 
                <p><strong>เวลาเรียน:</strong> <?php echo htmlspecialchars($start_time); ?> </p> 
                <p><strong>เวลาสิ้นสุดเรียน:</strong> <?php echo htmlspecialchars($end_time); ?> </p> 
                <p><strong>ชั่วโมงรวม:</strong> <?php echo htmlspecialchars($duration_hour); ?> </p> 
                <p><strong>ติวเตอร์:</strong> <?php echo htmlspecialchars($name_tutor); ?> </p> 
                <!-- <p><strong>ติวเตอร์:</strong> <?php echo htmlspecialchars($id_tutor); ?> </p>  -->
                </div>
                <div class="line-height"></div>
                <div class="input-detail">
                    
                    <div class="input">
                      

                        <!-- Other input fields -->
                         <div class="text-head-con">
                        <h class="text-head"><strong>ลงทะเบียนคอร์ส</strong></h></div>

                        <div class="form__group">
                            <input type="text" name="name_std" class="form__input" placeholder="Name Lastname" required="" value="<?php echo htmlspecialchars($user_name); ?>" style="background-color: #e8e8e8;"/>
                            <label for="name" class="form__label">Name Lastname</label>
                        </div>
                        <div class="form__group">
                            <input type="email" name="email_std" class="form__input" placeholder="Email" required="" value="<?php echo htmlspecialchars($email); ?>" style="background-color: #e8e8e8;"/>
                            <label for="email" class="form__label">Email</label>
                        </div>
                        <div class="form__group" style="margin-bottom:10px;">
                            <input type="number" name="number_std" class="form__input" placeholder="Number Phone" required=""/> 
                            <label for="number" class="form__label">Number Phone</label>
                         </div>

                        <h class="text-head"><strong>จำนวนเงินที่ต้องชำระ: <?php echo number_format(htmlspecialchars($price_course), 2); ?> บาท<br></strong></h>
                         <!-- ตัวเลือกช่องทางการชำระเงิน -->
                        <h class="text-head" style="font-size:20px;">เลือกช่องทางการชำระเงิน</h>
                        <div class="payment-methods">
                            <input type="radio" id="promptpay" name="paymentMethod_course" value="promptpay" checked onclick="showQRCode();">
                            <label for="promptpay">PromptPay</label><br>

                            <input type="radio" id="creditCard" name="paymentMethod_course" value="creditCard" onclick="hideQRCode();">
                            <label for="creditCard">บัตรเครดิต/เดบิต</label><br>

                            <input type="radio" id="bankTransfer" name="paymentMethod_course" value="bankTransfer" onclick="hideQRCode();">
                            <label for="bankTransfer">โอนผ่านธนาคาร</label><br>
                        </div>

                        <div class="container-allrr">
                            <!-- QR Code สำหรับ PromptPay -->
                            <div id="promptpayQRCode" class="qrcode-container" style="display: block;">
                                <p class="text-head">สแกน QR Code เพื่อชำระเงินผ่าน PromptPay:</p>
                                <img src="/project end/img/ercode00.jpg" alt="QR Code สำหรับ PromptPay" class="qrcode-image">
                                <p class="text-allrr">ชื่อบัญชี: นาบีล หะยีสาเมาะ</p>
                                <p class="text-allrr">หมายเลขบัญชี: 924-0-50763-9</p>
                                <p class="text-allrr" >ธนาคารกรุงไทย</p>
                            </div>
        
                            <div class="allrr">
                                <!-- ปุ่มอัปโหลด QR Code -->
                                <div>
                                    <p class="text-head" style="color:#FF5722;"><strong>อัปโหลด QR Code ของคุณ</strong></p>
                                    <label for="qrCodeUpload" class="upload-btn">
                                        <i class="fa-solid fa-upload"></i> อัปโหลด QR Code 
                                        <input type="file" id="qrCodeUpload" name="qrCodeUpload_course" accept="image/*" onchange="handleFileUpload(event)" require>
                                    </label>
                                    <p id="fileStatus" class="file-status">*กรุณาอัปโหลดไฟล์</p>
                                </div>
                                <!-- แสดงภาพ QR Code ที่อัปโหลด -->
                                <div class="qrcode-preview-container">
                                    <img id="qrCodePreview" src="#" alt="QR Code ที่อัปโหลด" style="display:none;">
                                </div>
                            </div>
                        </div> <!--/container-allrr -->

                        <input type="hidden" name="id_course" value="<?php echo htmlspecialchars($id_course); ?>">

                        <button class="btn-submit" type="submit" id="alertButton" >ลงทะเบียน</button>  
                        <button class="btn-cancel" type="button" onclick="location.href='all_course.php'">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
         <!-- ลิงก์ JS ของ FullCalendar -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script>
        // แสดง QR Code เมื่อเลือก PromptPay
function showQRCode() {
    document.getElementById('promptpayQRCode').style.display = 'block';
}

// ซ่อน QR Code เมื่อเลือกช่องทางอื่น
function hideQRCode() {
    document.getElementById('promptpayQRCode').style.display = 'none';
}

// ฟังก์ชันจัดการการอัปโหลดไฟล์
function handleFileUpload(event) {
    const fileInput = event.target;
    const fileStatus = document.getElementById('fileStatus');
    const qrCodePreview = document.getElementById('qrCodePreview');
    const promptpayQRCode = document.getElementById('promptpayQRCode'); // เพิ่มตัวแปรเพื่อดึง QR Code เดิม
    
    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        fileStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> ไฟล์ถูกอัปโหลดเรียบร้อย';
        fileStatus.style.color = '#4CAF50'; // เปลี่ยนสีข้อความเมื่อมีการอัปโหลด

        // แสดงภาพพรีวิว
        const reader = new FileReader();
        reader.onload = function(e) {
            qrCodePreview.src = e.target.result;
            qrCodePreview.style.display = 'block'; // แสดงภาพพรีวิว
              
            // ซ่อน QR Code ที่แสดงอยู่บนหน้า
            promptpayQRCode.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        fileStatus.innerHTML = '*กรุณาอัปโหลดไฟล์';
        fileStatus.style.color = '#FF5722'; // สีข้อความเตือน
        qrCodePreview.style.display = 'none'; // ซ่อนภาพพรีวิว

        // แสดง QR Code อีกครั้งเมื่อไม่มีการอัปโหลดไฟล์
        promptpayQRCode.style.display = 'block';
    }
}

// การตั้งค่าเริ่มต้นเมื่อโหลดหน้า
document.addEventListener('DOMContentLoaded', function() {
    // คำนวณยอดรวมเริ่มต้น
    calculateTotal(); 

    // ตั้งค่าการตรวจจับเหตุการณ์เมื่อเลือกตัวเลือกการชำระเงิน
    document.querySelectorAll('input[name="paymentOption"]').forEach(function(radio) {
        radio.addEventListener('change', calculateTotal);
    });

    // ตั้งค่าการตรวจจับเหตุการณ์สำหรับการอัปโหลดไฟล์
    document.getElementById('fileInput').addEventListener('change', handleFileUpload);

});

</script>
<script>
    function validateForm() {
    const fileInput = document.getElementById('qrCodeUpload');
    const fileStatus = document.getElementById('fileStatus');
    
    if (!fileInput.files.length) {
        alert("กรุณาอัปโหลดไฟล์ QR Code ก่อนส่งฟอร์ม."); // เพิ่ม alert หากไม่มีการอัปโหลดไฟล์
        return false; // ป้องกันการส่งฟอร์ม
    }
    
    return true; // อนุญาตให้ส่งฟอร์ม
}

    document.getElementById('alertButton').addEventListener('click', function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มทันที

        // ตรวจสอบการกรอกข้อมูลก่อนการแสดง SweetAlert
    if (!validateForm()) {
        return; // หากไม่ผ่านการตรวจสอบให้หยุดการทำงาน
    }

    event.preventDefault(); // ป้องกันการส่งฟอร์มทันที

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success btn-lg",
                cancelButton: "btn btn-danger btn-lg"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "ยืนยันการลงทะเบียนคอร์ส?",
            text: "คุณจะไม่สามารถย้อนกลับได้!",
            icon: "info",
            showCancelButton: true,
            cancelButtonText: "ยกเลิก",
            confirmButtonText: "บันทึก",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // แสดงการแจ้งเตือนสำเร็จ
                swalWithBootstrapButtons.fire({
                    title: "บันทึกแล้ว!",
                    text: "คอร์สติวของคุณลงทะเบียนสำเร็จ.",
                    icon: "success"
                }).then(() => {
                    // ส่งฟอร์มหลังจากยืนยันเสร็จ
                    document.getElementById("myForm").submit();
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "กลับมาแก้ไข",
                    icon: "error"
                });
            }
        });
    });
</script>
</body>
</html>

