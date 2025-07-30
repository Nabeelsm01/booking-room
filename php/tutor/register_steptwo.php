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

// // ตรวจสอบประเภทของผู้ใช้และดึง id_user จากเซสชัน
// $user_id = '';
// if (isset($_SESSION['id'])) {
//     $user_id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
// } elseif (isset($_SESSION['id_tutor'])) {
//     $user_id = $_SESSION['id_tutor']; // สำหรับติวเตอร์
// } elseif (isset($_SESSION['id_provider'])) {
//     $user_id = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
// }
// ตรวจสอบประเภทของผู้ใช้และดึง id_user จากเซสชัน
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
} elseif (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
} elseif (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
}
// var_dump($user_name);
// var_dump($id);
// var_dump($user_id);
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // เก็บข้อมูลจาก POST ลงในเซสชัน
    $_SESSION['name'] = isset($_POST['name']) ? $_POST['name'] : '';
    $_SESSION['email'] = isset($_POST['email']) ? $_POST['email'] : '';
    $_SESSION['number'] = isset($_POST['number']) ? $_POST['number'] : '';
    $_SESSION['date_start'] = isset($_POST['date_start']) ? $_POST['date_start'] : '';
    $_SESSION['date_end'] = isset($_POST['date_end']) ? $_POST['date_end'] : '';
    $_SESSION['day_book'] = isset($_POST['day_book']) ? $_POST['day_book'] : '';

    $_SESSION['image_room'] = isset($_POST['image_room']) ? $_POST['image_room'] : '';
    $_SESSION['room_id'] = isset($_POST['room_id']) ? $_POST['room_id'] : '';
    $_SESSION['room_name'] = isset($_POST['room_name']) ? $_POST['room_name'] : '';
    $_SESSION['room_price'] = isset($_POST['room_price']) ? $_POST['room_price'] : '';
    $_SESSION['room_type'] = isset($_POST['room_type']) ? $_POST['room_type'] : '';
    $_SESSION['opacity_room'] = isset($_POST['opacity_room']) ? $_POST['opacity_room'] : '';
    $_SESSION['address_room'] = isset($_POST['address_room']) ? $_POST['address_room'] : '';

    $_SESSION['id_provider'] = isset($_POST['id_provider']) ? $_POST['id_provider'] : '';

    // เก็บข้อมูล selectedFoods ลงในตัวแปรและเซสชัน
    $selectedFoods = isset($_POST['selectedFoods']) ? $_POST['selectedFoods'] : '[]';
    $_SESSION['selectedFoods'] = json_decode($selectedFoods, true);
    $selectedFoodsArray = isset($_POST['selectedFoods']) ? json_decode($_POST['selectedFoods'], true) : [];

    $_SESSION['paymentOption'] = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : '';
    $_SESSION['paymentMethod'] = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
    $_SESSION['totalAmount'] = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;

    $_SESSION['totalAmount'] = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;

    $_SESSION['total_pricess'] = isset($_POST['total_pricess']) ? $_POST['total_pricess'] : 0;   
    $total_pricess = isset($_POST['total_pricess']) ? floatval($_POST['total_pricess']) : NULL; 

    $_SESSION['qrCodeUpload'] = isset($_POST['qrCodeUpload']) ? $_POST['qrCodeUpload'] : '';
    $qrCodeUpload = isset($_SESSION['qrCodeUpload']) ? $_SESSION['qrCodeUpload'] : '';
}

// รับค่าจากเซสชัน
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$number = isset($_SESSION['number']) ? $_SESSION['number'] : '';
$date_start = isset($_SESSION['date_start']) ? $_SESSION['date_start'] : '';
$date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : '';
$day_book = isset($_SESSION['day_book']) ? $_SESSION['day_book'] : '';

$image_room = isset($_SESSION['image_room']) ? $_SESSION['image_room'] : '';
$room_id = isset($_SESSION['room_id']) ? $_SESSION['room_id'] : '';
$room_name = isset($_SESSION['room_name']) ? $_SESSION['room_name'] : '';
$room_price = isset($_SESSION['room_price']) ? $_SESSION['room_price'] : ''; 
$room_type = isset($_SESSION['room_type']) ? $_SESSION['room_type'] : ''; 
$opacity_room = isset($_SESSION['opacity_room']) ? $_SESSION['opacity_room'] : ''; 
$address_room = isset($_SESSION['address_room']) ? $_SESSION['address_room'] : ''; 

$id_provider = isset($_SESSION['id_provider']) ? $_SESSION['id_provider'] : ''; 

// เก็บค่าของ selectedFoods ในตัวแปร
$selectedFoodsArray = isset($_SESSION['selectedFoods']) ? $_SESSION['selectedFoods'] : [];


$paymentOption = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : '';
$paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;

$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0; //ราคารวมหลังมัดจำ/เต็ม->

?>
<?php
// // var_dump($room_id);
// $id_details_array = [];
//     // เตรียม SQL SELECT
//     $sql_select = "SELECT id_details FROM details_roombooking 
//                 WHERE id_room = ? AND total_pricess = ? AND (id = ? OR id_tutor = ?)";
//     $stmt = $conn->prepare($sql_select);

//     if ($stmt === false) {
//         die('Prepare failed: ' . htmlspecialchars($conn->error));
//     }

//     // ตรวจสอบค่าและ bind parameter
//     if (!empty($id)) {
//         // ถ้ามีค่า id แต่ไม่มีค่า id_tutor
//         $stmt->bind_param("iiis", $room_id, $total_pricess, $id, $null_value);
//     } else {
//         // ถ้ามีค่า id_tutor แต่ไม่มีค่า id
//         $stmt->bind_param("iiis", $room_id, $total_pricess, $null_value, $id_tutor);
//     }

//     // Execute the query
//     $stmt->execute();

//     // ดึงข้อมูล
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             // var_dump($row['id_details']);
//             $id_details_array[] = $row['id_details']; // เพิ่มค่า id_details ลงใน array
//         }
//     } else {
//         echo "ไม่มีข้อมูลที่ตรงกับเงื่อนไข";
//     }   

//     if (!empty($id_details_array)) {
//         $stmt_insert = $conn->prepare("INSERT INTO roombooking (name, email, phone_number, date_start, date_end, day_book, id_details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
//         if ($stmt_insert === false) {
//             die('Prepare failed: ' . htmlspecialchars($conn->error));
//         }
    
//         foreach ($id_details_array as $id_details) {
//             $stmt_insert->bind_param("ssissii", $name, $email, $number, $date_start, $date_end, $day_book, $id_details);
            
//             if ($stmt_insert->execute() === false) {
//                 echo "Error: " . $stmt_insert->error;
//             }
//         }
    
//         // echo "เพิ่มรายละเอียดห้องประชุมสำเร็จ!";
//         $stmt_insert->close();
//     } else {
//         echo "ไม่มีข้อมูลที่ต้องแทรก";
//     }
$sqlSelectpayment = "
    SELECT * 
    FROM payment_room 
    JOIN roombooking ON payment_room.id_bookingroom = roombooking.id_bookingroom 
    WHERE roombooking.name = ? AND roombooking.date_start = ? AND roombooking.date_end = ? 
    ORDER BY roombooking.id_bookingroom DESC LIMIT 1";

    $stmt = $conn->prepare($sqlSelectpayment);
    $stmt->bind_param('sss', $name, $date_start, $date_end);
    $stmt->execute();

    // ดึงผลลัพธ์
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_booking = $row['id_bookingroom'];
    $total_pricess = $row['total_pricess'];
    $totalAmount = $row['totalAmount'];
    $paymentOption = $row['paymentOption'];
    $paymentMethod = $row['paymentMethod'];
    $status = $row['status'];
    // echo "id_bookingroom: " . $id_booking;
    // echo "total_pricess: " . $total_pricess;
    // echo "totalAmount: " . $totalAmount;
    // echo "id_booking: " . $id_booking;

    } else {
    echo "No booking room found.";
    }

?>
<?php
// ---- select id_provider keepdata -------------
if (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider'];

    $sql = "SELECT email_provider, number_provider FROM provider WHERE id_provider = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_provider);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email_provider = $row['email_provider'];
        $number_provider = $row['number_provider'];
    } else {
        echo "No provider found with this ID.";
    }
} else {
    echo "No id_provider in session.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .flatpickr-input {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            background: #f9f9f9;
            padding: 10px;
            font-size: 16px;
        }

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
            margin-left: 6.4%; /* ขยับไปทางขวามือ */
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
<a href="all_meetingroom.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">จองเสร็จสิ้น</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 50px; margin-top: 32px;"  aria-label="breadcrumb" >
             <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_course.php" style="text-decoration:none;">คอร์สติว</a></li>
                    <li class="breadcrumb-item " ><a href="#.php" style="text-decoration:none;">ลงทะเบียนและชำระเงิน</a></li>
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
            <div class="blockcenter_4">
            <div class="conf-wrapper-superb">
                <h1 class="conf-title-victory">ขอบคุณที่ทำการจอง!</h1>
                <?php
                    if (!empty($status)) {
                        if ($status == 'รอดำเนินการ') {
                            echo '<p class="conf-message-complete cst-conf-pending">รอดำเนินการ <i class="bi bi-hourglass-split"></i></p>';
                        } else if ($status == 'มัดจำแล้ว') {
                            echo '<p class="conf-message-complete cst-conf-deposit">จองเสร็จสิ้น มัดจำแล้ว <i class="bi bi-check-circle-fill"></i></p>';
                        } else if ($status == 'ชำระเงินครบแล้ว') {
                            echo '<p class="conf-message-complete cst-conf-paid">จองเสร็จสิ้น ชำระเงินครบแล้ว <i class="bi bi-check-circle-fill"></i></p>';
                        }
                    }
                    ?>
                <p class="conf-info-label"><strong>ข้อมูลการจอง:</strong></p>
                <ul class="conf-details-list-extra"> 
                    <li class="conf-detail-item">รหัสผู้จอง: <?php echo htmlspecialchars($id_booking); ?></li>
                    <li class="conf-detail-item">ผู้จอง: <?php echo htmlspecialchars($name); ?></li>
                    <li class="conf-detail-item">ชื่อห้องประชุม: <?php echo htmlspecialchars($room_name); ?></li>
                    <li class="conf-detail-item">วันที่เริ่มจอง: <?php echo htmlspecialchars($date_start); ?></li>
                    <li class="conf-detail-item">วันที่สิ้นสุด: <?php echo htmlspecialchars($date_end); ?></li>
                    <li class="conf-detail-item">ระยะเวลา/วัน: <?php echo htmlspecialchars($day_book); ?></li>
                    <li class="conf-detail-item">ตัวเลือกการชำระเงิน: <?php echo htmlspecialchars($paymentOption); ?></li>
                    <li class="conf-detail-item">ช่องทางการชำระเงิน: <?php echo htmlspecialchars($paymentMethod); ?></li>
                    <li class="conf-detail-item">จำนวนเงินที่จ่าย: <?php echo number_format(htmlspecialchars($totalAmount), 2); ?> บาท</li>

                </ul>
        
                <p class="conf-contact-info">หากคุณมีข้อสงสัยเพิ่มเติม โปรดติดต่อ <?php echo $email_provider . " / " . $number_provider; ?></p>
                <a href="/project end/php/home.php" class="btn-submit" type="submit" style="width:100%; text-align:center; text-decoration:none;">กลับสู่หน้าหลัก</a>
            </div>
  
    </div>
    </div>     
           
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      
</body>
</html>
