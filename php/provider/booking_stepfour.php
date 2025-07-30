<?php
session_start();
include('../connect.php');
$errors = array(); 

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

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
} elseif (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
} elseif (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
}
//  var_dump($user_name);
//  var_dump($id_tutor);

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
    // $_SESSION['room_id'] = isset($_POST['room_id']) ? $_POST['room_id'] : 0;
    $room_id = isset($_POST['room_id']) ?Intval( $_POST['room_id']) : 0;
    $_SESSION['room_name'] = isset($_POST['room_name']) ? $_POST['room_name'] : '';
    $_SESSION['room_price'] = isset($_POST['room_price']) ? $_POST['room_price'] : '';
    $_SESSION['room_type'] = isset($_POST['room_type']) ? $_POST['room_type'] : '';
    $_SESSION['opacity_room'] = isset($_POST['opacity_room']) ? $_POST['opacity_room'] : '';
    $_SESSION['address_room'] = isset($_POST['address_room']) ? $_POST['address_room'] : '';
    $_SESSION['id_provider'] = isset($_POST['id_provider']) ? $_POST['id_provider'] : ''; 

    $_SESSION['totalPricess_full'] = isset($_POST['totalPricess_full']) ? intval($_POST['totalPricess_full']) : 0;

    // เก็บข้อมูล selectedFoods ลงในตัวแปรและเซสชัน
    $selectedFoods = isset($_POST['selectedFoods']) ? $_POST['selectedFoods'] : '[]';
    $_SESSION['selectedFoods'] = json_decode($selectedFoods, true);
    $selectedFoodsArray = isset($_POST['selectedFoods']) ? json_decode($_POST['selectedFoods'], true) : [];
    $_SESSION['total_pricess'] = isset($_POST['total_pricess']) ? $_POST['total_pricess'] : 0;   
    $total_pricess = isset($_POST['total_pricess']) ? floatval($_POST['total_pricess']) : NULL;
    $id_promotion_room = isset($_POST['id_promotion_room']) && $_POST['id_promotion_room'] != 0 ? intval($_POST['id_promotion_room']) : NULL;
    // ตรวจสอบว่าได้รับค่า id_promotion_room หรือไม่
// echo " ราคารวม:";
//   var_dump($total_pricess); 
//   echo " โปร:";
//   var_dump($id_promotion_room); 
//   echo " ห้องประชุม:";
//   var_dump($room_id); 
//    echo " อาหาร:";
// foreach ($selectedFoodsArray as $food) {
//     $id_food = $food['id'];
//      var_dump($id_food); 
// }

}

// รับค่าจากเซสชัน
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$number = isset($_SESSION['number']) ? $_SESSION['number'] : '';
$date_start = isset($_SESSION['date_start']) ? $_SESSION['date_start'] : '';
$date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : '';
$day_book = isset($_SESSION['day_book']) ? $_SESSION['day_book'] : '';

$image_room = isset($_SESSION['image_room']) ? $_SESSION['image_room'] : '';
$room_id = isset($_SESSION['room_id']) ? $_SESSION['room_id'] : 0;
$room_name = isset($_SESSION['room_name']) ? $_SESSION['room_name'] : '';
$room_price = isset($_SESSION['room_price']) ? $_SESSION['room_price'] : ''; 
$room_type = isset($_SESSION['room_type']) ? $_SESSION['room_type'] : ''; 
$opacity_room = isset($_SESSION['opacity_room']) ? $_SESSION['opacity_room'] : ''; 
$address_room = isset($_SESSION['address_room']) ? $_SESSION['address_room'] : ''; 

$id_provider = isset($_SESSION['id_provider']) ? $_SESSION['id_provider'] : ''; 

$totalPricess_full = isset($_SESSION['totalPricess_full']) ? $_SESSION['totalPricess_full'] : 0;

// เก็บค่าของ selectedFoods ในตัวแปร
$selectedFoodsArray = isset($_SESSION['selectedFoods']) ? $_SESSION['selectedFoods'] : [];

$paymentOption = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : '';
$paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0;

$totalPricess = isset($_POST['total_pricess']) ? $_POST['total_pricess'] : 0;
// ตรวจสอบว่า $totalPricess เป็นตัวเลขและแปลงเป็น float หากเป็นตัวเลข
$totalPricess = is_numeric($totalPricess) ? (float)$totalPricess : 0;
// $promotion_id = isset($_SESSION['id_promotion_room']) ? $_SESSION['id_promotion_room'] : '';

    // Select total_pricess จาก details_roombooking โดยเชื่อมกับ roombooking ผ่าน Foreign Key
    $sqlSelect = "
    SELECT * 
    FROM details_roombooking 
    JOIN roombooking ON details_roombooking.id_bookingroom = roombooking.id_bookingroom 
    LEFT JOIN promotion_room ON details_roombooking.id_promotion_room = promotion_room.id_promotion_room 
    WHERE roombooking.name = ? AND roombooking.date_start = ? AND roombooking.date_end = ? 
    ORDER BY roombooking.id_bookingroom DESC, details_roombooking.id_details DESC 
    LIMIT 1";

    $stmt = $conn->prepare($sqlSelect);
    $stmt->bind_param('sss', $name, $date_start, $date_end);
    $stmt->execute();

    // ดึงผลลัพธ์
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalPricess = $row['total_pricess']; 
    $id_booking = $row['id_bookingroom'];
    $promotion_type = $row['promotion_type_room'];
    $promotion_value = $row['discount_value_room'] !== null ? $row['discount_value_room'] : 'ไม่มีโปรโมชั่น'; // ตรวจสอบค่าของ promotion_value
    // echo "Total Price: " . $totalPricess;
    // echo "id_booking: " . $id_booking;

    } else {
    echo "No booking room found.";
    }

    $promotion_text = ''; // ประกาศตัวแปร promotion_text ไว้ก่อน

    if (!empty($promotion_type) && $promotion_type == 'fixed') {
        $promotion_text = 'ส่วนลดราคา ' . $promotion_value . ' บาท';
    } else if (!empty($promotion_type) && $promotion_type == 'percentage') {
        $promotion_text = 'ส่วนลด ' . $promotion_value . ' %';
    } else {
        $promotion_text = 'ไม่มีโปรโมชั่น';
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

    <link rel="stylesheet" href="/project end/css/alert.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
            margin-left: 10.3%; /* ขยับไปทางขวามือ */
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
<a href="booking_stepthree.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">ข้อมูลการจอง</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 50px; margin-top: 32px;"  aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_meetingroom.php" style="text-decoration:none;">ห้องประชุม</a></li>
                    <li class="breadcrumb-item active" ><a href="booking_room.php" style="text-decoration:none;">รายละเอียดการจอง</a></li>
                    <li class="breadcrumb-item active" ><a href="booking_steptwo.php" style="text-decoration:none;">เลือกอาหาร</a></li>
                    <li class="breadcrumb-item active" ><a href="booking_stepthree.php" style="text-decoration:none;">ข้อมูลการจอง</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ชำระเงิน</li>
                </ol>
                </nav>
                <div class="stepper">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <div class="step-title">รายละเอียดการจอง</div>
                    </div>
                    <div class="step active">
                        <div class="step-circle">2</div>
                        <div class="step-title">เลือกอาหาร</div>
                    </div>
                    <div class="step active">
                        <div class="step-circle">3</div>
                        <div class="step-title">ยืนยันการจอง</div>
                    </div>
                    <div class="step active">
                        <div class="step-circle">4</div>
                        <div class="step-title">ชำระเงิน</div>
                    </div>
                    <div class="step ">
                        <div class="step-circle">5</div>
                        <div class="step-title">เสร็จสิ้น</div>
                    </div>
                </div>
        </div>     
        <form id="myForm" action="booking_stepfivedb.php" method="POST" enctype="multipart/form-data">
            <div class="blockcenter_4">
                
            <div class="container_p">
                <h class="text-head"><strong>รายละเอียดผู้จอง</strong></h>
                <div class="container_text">
                    <div class="text_p">
                        <p><strong>ชื่อ:</strong> <?php echo htmlspecialchars($name); ?></p>
                        <p><strong>อีเมล:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($number); ?></p>
                    </div>
                    <div class="text_p">
                        <p><strong>วันที่เริ่มจอง:</strong> <?php echo htmlspecialchars($date_start); ?></p>
                        <p><strong>วันที่สิ้นสุด:</strong> <?php echo htmlspecialchars($date_end); ?></p>
                        <p><strong>วันจอง:</strong> <?php echo htmlspecialchars($day_book); ?></p>
                    </div>
                    </div>
            </div>
            
            <div class="linewidth"></div>
            <h class="text-head"><strong>ชำระเงิน</strong></h>
            <?php


            // คำนวณราคาห้อง
            $room_price_numeric = floatval($room_price); // แปลงเป็นเลขทศนิยม
            $day_book_numeric = intval($day_book); // แปลงเป็นเลขจำนวนเต็ม
            $total_room_price = $room_price_numeric * $day_book_numeric; // คำนวณราคาทั้งหมด

            // ตัวแปรสำหรับเก็บข้อมูลทั้งหมด
            $totalQuantity = 0;
            $totalPrice = 0;
            $menuCount = 0;

            // ตรวจสอบว่ามีการเลือกอาหารหรือไม่
            if (!empty($selectedFoodsArray)) {
                foreach ($selectedFoodsArray as $food) {
                    // บวกจำนวนเมนูและราคาทั้งหมดของแต่ละเมนู
                    $totalQuantity += $food['quantity'];
                    $totalPrice += $food['totalPrice'];
                    $menuCount++;  // นับจำนวนประเภทของเมนู
                }
                
                // คำนวณราคาทั้งหมดรวม
                $grandTotal = $total_room_price + $totalPrice;
            } else {
                $grandTotal = $total_room_price;
            }
            ?>

            <table>
                <thead>
                    <tr>          
                        <th>ประเภท</th> 
                        <th>(รหัสห้อง/จำนวนเมนู)</th>   
                        <th>ชื่อ</th>
                        <th>ราคา</th>
                        <th>(วัน/จำนวน)</th>
                        <th>ราคารวม</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:5px;">
                            <div style="width: 80px; height: 80px; background-color:#b6c3ca; margin:auto; display: flex; justify-content: center; align-items: center;">
                                <i class="fa-solid fa-hotel" style="color:#fff; font-size:40px;"></i>
                            </div>
                        </td>
                        <td><p><?php echo htmlspecialchars($room_id); ?></p></td>
                        <td style="text-align:left;"><?php echo htmlspecialchars($room_name); ?></td>
                        <td><p><?php echo number_format(htmlspecialchars($room_price)); ?> บาท</p></td>
                        <td><p><?php echo htmlspecialchars($day_book); ?></p></td>
                        <td><p><?php echo number_format($total_room_price, 2); ?></p></td>
                    </tr>
                    <?php if (!empty($selectedFoodsArray)): ?>
                        <tr>
                            <td style="padding:5px;">
                                <div style="width: 80px; height: 80px; background-color:#b6c3ca; margin:auto; display: flex; justify-content: center; align-items: center;">
                                    <i class="fa-solid fa-utensils" style="color:#fff; font-size:40px;"></i>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($menuCount); ?> เมนู</td>
                            <td style="text-align:left;">อาหาร/เครื่องดื่ม</td>
                            <td></td>
                            <td><?php echo htmlspecialchars($totalQuantity); ?> </td>
                            <td><?php echo htmlspecialchars($totalPrice, 2); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding:10px; color: #717FA1; font-size:18px;">ไม่ได้เลือกรายการอาหาร</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="summary">
                        <td colspan="4">รวมจำนวน:</td>
                        <td><?php echo $promotion_text; ?></td>
                        <td><strong><?php 
                        if(!empty($totalPricess)){
                        echo number_format(htmlspecialchars($totalPricess),2); 
                        }else{
                        echo number_format(htmlspecialchars($total_room_price),2); 
                        }
                        ?> บาท</strong>
                        <?php echo '<p style="font-size:18px; color: #bcc7c9; text-decoration: line-through;">(ราคาก่อนหักส่วนลด: '. number_format($totalPricess_full, 2) .' บาท)</p>';?>
                        </td>
                    </tr>
                </tfoot>
            </table>


                <!-- ตัวเลือกการชำระเงิน -->
                <h2 class="text-head"><strong>ชำระเงิน</strong></h2>
                <div class="payment-options">
                    <input type="radio" id="fullPayment" name="paymentOption" value="ชำระเต็มจำนวน" checked onclick="calculateTotal();">
                    <label for="fullPayment">ชำระเต็มจำนวน</label><br>
                    
                    <input type="radio" id="depositPayment" name="paymentOption" value="มัดจำ 50%" onclick="calculateTotal();">
                    <label for="depositPayment">มัดจำ 50%</label><br>
                </div>

                <!-- แสดงจำนวนเงินที่ต้องชำระ -->
                <div class="payment-summary">
                    <p class="pay-sum-p">จำนวนเงินที่ต้องชำระ: <strong id="totalAmount"><?php echo number_format(htmlspecialchars($totalPricess), 2); ?> บาท</strong></p>
                </div>

                <!-- ตัวเลือกช่องทางการชำระเงิน -->
                <h class="text-head"><strong>เลือกช่องทางการชำระเงิน</strong></h>
                <div class="payment-methods">
                    <input type="radio" id="promptpay" name="paymentMethod" value="promptpay" checked onclick="showQRCode();">
                    <label for="promptpay">PromptPay</label><br>

                    <input type="radio" id="creditCard" name="paymentMethod" value="creditCard" onclick="hideQRCode();">
                    <label for="creditCard">บัตรเครดิต/เดบิต</label><br>

                    <input type="radio" id="bankTransfer" name="paymentMethod" value="bankTransfer" onclick="hideQRCode();">
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
                            <p class="text-head">อัปโหลด QR Code ของคุณ</p>
                            <label for="qrCodeUpload" class="upload-btn">
                                <i class="fa-solid fa-upload"></i> อัปโหลด QR Code 
                                <input type="file" id="qrCodeUpload" name="qrCodeUpload" accept="image/*" onchange="handleFileUpload(event)" require>
                            </label>
                            <p id="fileStatus" class="file-status">*กรุณาอัปโหลดไฟล์</p>
                        </div>
                        <!-- แสดงภาพ QR Code ที่อัปโหลด -->
                        <div class="qrcode-preview-container">
                            <img id="qrCodePreview" src="#" alt="QR Code ที่อัปโหลด" style="display:none;">
                        </div>
                    </div>
                 </div> <!--/container-allrr -->
       

                <input type="hidden" name="selectedFoods" value='<?php echo json_encode($selectedFoodsArray); ?>'>
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                <input type="hidden" name="number" value="<?php echo htmlspecialchars($_SESSION['number']); ?>">
                <input type="hidden" name="date_start" value="<?php echo htmlspecialchars($_SESSION['date_start']); ?>">
                <input type="hidden" name="date_end" value="<?php echo htmlspecialchars($_SESSION['date_end']); ?>">
                <input type="hidden" name="day_book" value="<?php echo htmlspecialchars($_SESSION['day_book']); ?>">

                <input type="hidden" name="image_room" value="<?php echo htmlspecialchars($_SESSION['image_room']); ?>">
                <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($_SESSION['room_id']); ?>">
                <input type="hidden" name="room_name" value="<?php echo htmlspecialchars($_SESSION['room_name']); ?>">
                <input type="hidden" name="room_price" value="<?php echo htmlspecialchars($_SESSION['room_price']); ?>">
                <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($_SESSION['room_type']); ?>">
                <input type="hidden" name="opacity_room" value="<?php echo htmlspecialchars($_SESSION['opacity_room']); ?>">
                <input type="hidden" name="address_room" value="<?php echo htmlspecialchars($_SESSION['address_room']); ?>">
                <input type="hidden" name="id_provider" value="<?php echo htmlspecialchars($_SESSION['id_provider']); ?>">

                <input type="hidden" name="total_pricess" value="<?php echo htmlspecialchars($total_pricess); ?>">        <!--ราคาทั้งหมดต้องจ่าย-->    
                <input type="hidden" name="totalAmount" value="<?php echo htmlspecialchars($totalAmount); ?>">        <!--ราคาจ่ายเต็มหรือมัดจำ50%-->
                
           <div class="linewidth"></div>
          <button class="btn-submit" type="submit" id="alertButton" style="width:100%;" >จอง</button>        
          <button class="btn-cancel" type="button" style="width:100%;" onclick="location.href='all_meetingroom.php'">ยกเลิก</button>
        </form>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script>
// คำนวณจำนวนเงินที่ต้องชำระเมื่อเลือกการชำระเงิน
function calculateTotal() {
    const total_Amounts = <?php echo htmlspecialchars($totalPricess); ?>; // รับค่าราคาทั้งหมดจาก PHP
    let totalAmount;

    // ตรวจสอบการเลือกตัวเลือกการชำระเงิน
    if (document.getElementById('depositPayment').checked) {
        totalAmount = total_Amounts * 0.50; // มัดจำ 50%
    } else {
        totalAmount = total_Amounts; // ชำระเต็มจำนวน
    }

    // ฟอร์แมตจำนวนเงินให้มีเครื่องหมายจุลภาค
    document.getElementById('totalAmount').textContent = formatNumber(totalAmount) + " บาท";

    // อัพเดตค่าใน <input type="hidden">
    document.querySelector('input[name="totalAmount"]').value = totalAmount;
}
// ฟังก์ชันสำหรับฟอร์แมตจำนวนเงิน
function formatNumber(number) {
    return new Intl.NumberFormat().format(number.toFixed(2));
}

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
    
    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        fileStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> ไฟล์ถูกอัปโหลดเรียบร้อย';
        fileStatus.style.color = '#4CAF50'; // เปลี่ยนสีข้อความเมื่อมีการอัปโหลด

        // แสดงภาพพรีวิว
        const reader = new FileReader();
        reader.onload = function(e) {
            qrCodePreview.src = e.target.result;
            qrCodePreview.style.display = 'block'; // แสดงภาพพรีวิว
        };
        reader.readAsDataURL(file);
    } else {
        fileStatus.innerHTML = '*กรุณาอัปโหลดไฟล์';
        fileStatus.style.color = '#FF5722'; // สีข้อความเตือน
        qrCodePreview.style.display = 'none'; // ซ่อนภาพพรีวิว
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
            title: "ยืนยันการจองห้องประชุม?",
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
                    text: "ห้องประชุมของคุณจองสำเร็จ.",
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
