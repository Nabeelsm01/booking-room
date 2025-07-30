<?php
session_start();
include('../connect.php');

$user_name = '';

if (isset($_SESSION['name_lastname'])) {
    $user_name = $_SESSION['name_lastname'];
} elseif (isset($_SESSION['name_lastname_tutor'])) {
    $user_name = $_SESSION['name_lastname_tutor'];
} elseif (isset($_SESSION['name_provider'])) {
    $user_name = $_SESSION['name_provider'];
}

$email = $_SESSION['email'];

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
} elseif (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
} elseif (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
}
?>
<?php
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
} elseif (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
    header("Location: /project end/php/home.php");
} elseif (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
    header("Location: /project end/php/home.php");
    exit();
}

    $id = $_SESSION['id']; 
    $sql = "SELECT *
    FROM register_course 
    JOIN course ON register_course.id_course = course.id_course
    JOIN tutor ON course.id_tutor = tutor.id_tutor
    JOIN user ON register_course.id = user.id
    WHERE register_course.id = ?
    ORDER BY register_course.id_register_course DESC"; // เรียงตาม id_sum_room ใหม่ไปเก่า

    // เตรียม query และ bind ตัวแปร
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
   

        .btn-custom {
            background: #007bff;
            color: #fff;
        }

        .btn-custom:hover {
            background: #0056b3;
            color: #fff;
        } 
        .stepper {
            display: flex;
            justify-content: space-around; 
            margin-bottom: 10px;
            margin-top: 10px; /* ขยับลงมานิดนึง */
            margin-left: 30.15%; /* ขยับไปทางขวามือ */
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            
        }
        th {
            background-color: #b6c3ca;
            color: #ffffff;

        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .summary {
            font-weight: bold;
            text-align: right;

        }
        .summary strong {
            color: #4a54f1;
            font-size:18px;
        }
        .trtr{
            background-color:  red;
            border:1px solid red;
        }
    </style>

</head>
<body>
<a href="all_course.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">ลงทะเบียนเสร็จสิ้น</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 60px; margin-top: 32px;"  aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_course.php" style="text-decoration:none;">คอร์สติว</a></li>
                    <li class="breadcrumb-item active" ><a href="#.php" style="text-decoration:none;">ลงทะเบียนและชำระเงิน</a></li>
                    <li class="breadcrumb-item active" aria-current="page">เสร็จสิ้น</li>
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
                    <div class="step active">
                        <div class="step-circle">3</div>
                        <div class="step-title">เสร็จสิ้น</div>
                    </div>
                  
                </div>
        </div>     
            <div class="blockcenter_4">
            <div class="conf-wrapper-superb">
                <h1 class="conf-title-victory">ขอบคุณที่ทำการลงทะเบียนคอร์สติว</h1>
                <?php
                 if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $email_tutor = $row['email_tutor'];
                        $status_register = $row['status_register'];

                        if (!empty($status_register)) {  
                            if ($status_register == 'ชำระไม่สำเร็จ') {
                                echo '<p class="conf-message-complete cst-conf-pending">ชำระไม่สำเร็จ <i class="bi bi-hourglass-split"></i></p>';
                            }else if ($status_register == 'รอดำเนินการ') {
                                echo '<p class="conf-message-complete cst-conf-pending">รอตววจสอบการชำระเงิน! <i class="bi bi-hourglass-split"></i></p>';
                            } else if ($status_register == 'ชำระแล้ว') {
                                echo '<p class="conf-message-complete cst-conf-paid">ชำระเงินแล้ว ลงทะเบียนสำเร็จ! <i class="bi bi-check-circle-fill"></i></p>';
                            }
                    }
                    ?>
                <p class="conf-info-label"><strong>ข้อมูลการจอง:</strong></p>
                <ul class="conf-details-list-extra"> 
                    
                    <li class="conf-detail-item">รหัสคอร์ส: <?php echo htmlspecialchars($row['id_course']); ?></li>
                    <li class="conf-detail-item">ผู้ลงทะเบียน: <?php echo htmlspecialchars($row['name_std']) ; ?></li>
                    <li class="conf-detail-item">ชื่อคอร์ส: <?php echo htmlspecialchars($row['name_course']); ?></li>
                    <li class="conf-detail-item">วันที่เริ่มเรียน: <?php echo htmlspecialchars($row['date_course']); ?></li>
                    <li class="conf-detail-item">วันที่สิ้นสุดการเรียน: <?php echo htmlspecialchars($row['date_course_end']); ?></li>
                    <li class="conf-detail-item">ระยะเวลา: <?php echo htmlspecialchars($row['day_course']); ?></li>
                    <li class="conf-detail-item">เวลาเริ่มเรียน: <?php echo htmlspecialchars($row['start_time']); ?> น.</li>
                    <li class="conf-detail-item">เวลาสิ้นสุดการเรียน: <?php echo htmlspecialchars($row['end_time']); ?> น.</li>
                    <li class="conf-detail-item">ระยะเวลา: <?php echo htmlspecialchars($row['duration_hour']); ?></li>
                    <li class="conf-detail-item">ช่องทางการเรียน: <?php echo htmlspecialchars($row['meeting_type']); ?></li>
                    <li class="conf-detail-item">รหัสการเชิญ(code): <?php echo htmlspecialchars($row['room_course']); ?></li>
                    <li class="conf-detail-item">ติวเตอร์: <?php echo htmlspecialchars($row['name_lastname_tutor']); ?></li>
                    <li class="conf-detail-item" style="font-size:20px;" ><strong>ชำระแล้ว: <?php echo htmlspecialchars($row['price_course']); ?> บาท</strong></li>
                </ul>
    
                <?php      
                
                }else {
                    echo "No regist course.";
                }
                ?>
                <p class="conf-contact-info" >หากคุณมีข้อสงสัยเพิ่มเติม โปรดติดต่อติวเตอร์ <?php echo $email_tutor; ?></p>
                <a href="/project end/php/tutor/register_course_summary.php" class="btn-submit-other" type="submit" style="width:100%; text-align:center; text-decoration:none;">ไปหน้าการลงทะเบียนของคุณ</a>
                <a href="/project end/php/home.php" class="btn-submit" type="submit" style="width:100%; text-align:center; text-decoration:none;">กลับสู่หน้าหลัก</a>
            </div>
  
    </div>
    </div>     
           
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      
</body>
</html>
