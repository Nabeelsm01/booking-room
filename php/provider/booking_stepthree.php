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

    $_SESSION['totalPricess'] = isset($_POST['totalPricess']) ? $_POST['totalPricess'] : '';
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

$totalPricess = isset($_POST['totalPricess']) ? $_POST['totalPricess'] : '';

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
            margin-left: 16%; /* ขยับไปทางขวามือ */
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
<a href="booking_steptwo.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">ข้อมูลการจอง</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 50px; margin-top: 32px;"  aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_meetingroom.php" style="text-decoration:none;">ห้องประชุม</a></li>
                    <li class="breadcrumb-item active" ><a href="booking_room.php" style="text-decoration:none;">รายละเอียดการจอง</a></li>
                    <li class="breadcrumb-item active" ><a href="booking_steptwo.php" style="text-decoration:none;">เลือกอาหาร</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ข้อมูลการจอง</li>
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
            <div class="container_p">
                <h class="text-head"><strong>รายละเอียดห้อง</strong></h>
                <div class="container_text">
                    <div class="text_p">
                        <img src="<?php echo htmlspecialchars($image_room); ?>" alt="Room Image" class="room-image">
                    </div>
                    <div class="text_p">
                        <p><strong>ชื่อห้อง:</strong> <?php echo htmlspecialchars($room_name); ?></p>
                        <p><strong>รหัสห้อง:</strong> <?php echo htmlspecialchars($room_id); ?></p>
                        <p><strong>ประเภทห้อง:</strong> <?php echo htmlspecialchars($room_type); ?></p>
                        <p><strong>ความจุ:</strong> <?php echo htmlspecialchars($opacity_room); ?> คน</p>
                        <p><strong>ราคา:</strong> ฿<?php echo htmlspecialchars($room_price); ?> บาท</p>
                        <p><strong>ที่อยู่:</strong> <?php echo htmlspecialchars($address_room); ?></p>
                    </div>
                    </div>
            </div>
            
            <div class="linewidth"></div>
            <h class="text-head"><strong>รายละเอียดการสั่งอาหาร</strong></h>
            <table>
               
                    <?php
                    $day_book = intval($day_book);

                    if (!empty($selectedFoodsArray)): ?>

                        <thead>
                        <tr>              
                            <th>รูป</th>
                            <th>ชื่อเมนู</th>
                            <th>ประเภท</th>
                            <th>ราคา/จาน</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    $totalQuantity = 0;
                    $totalPrice = 0;
                    foreach ($selectedFoodsArray as $food):
                        $totalQuantity += $food['quantity'];
                        $totalPrice += $food['totalPrice'];
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($food['image']); ?>" alt="รูปภาพของ <?php echo htmlspecialchars($food['name']); ?>" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td style="text-align:left;"><?php echo htmlspecialchars($food['name']); ?></td>
                        <td><?php echo htmlspecialchars($food['type']); ?></td>
                        <td><?php echo htmlspecialchars($food['price']); ?></td>
                        <td><?php echo htmlspecialchars($food['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($food['totalPrice']); ?></td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
                </table>
                    <?php else: ?>
                        <p style="padding:10px; color: #717FA1; font-size:18px;">ไม่ได้เลือกรายการอาหาร</p>
                    <?php endif; ?>

                    <!-- ส่วนของฟุตเตอร์ที่แยกออกมาและจะแสดงเสมอ -->
                    <table>
                        <tfoot>
                            <tr class="summary" style="color:black;">
                            <td ></td>
                                <td colspan="3" >รวมจำนวน:</td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ><?php echo $totalQuantity ?? 0; ?></td>
                                <td ><strong>
                                    <?php
                                    // ตรวจสอบว่ามีข้อมูลอาหารหรือไม่ ถ้ามีให้บวกราคารวมอาหารกับราคาห้องประชุม
                                    if (!empty($selectedFoodsArray)) {
                                        echo number_format($totalPrice, 2);
                                        $totalAmounts = $totalPrice + ($room_price * $day_book);
                                    } else {
                                        // ถ้าไม่มีข้อมูลอาหารให้แสดงเฉพาะราคาห้องประชุม
                                        // echo number_format($room_price, 2);
                                        $totalAmounts = $room_price * $day_book;
                                    }
                                    ?> บาท</strong></td>
                            </tr>
                        </tfoot>
                    </table>

        <form action="booking_stepfour_db.php" method="POST">  
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

                <input type="hidden" name="id_promotion_room" id="id_promotion_room" value="">

                <div class="pricetoom-food" style="text-align:right;padding:0 25px; font-size:30px; color: #4a54f1;">
    <tr class="summary">
        <td><strong>
        <?php
        // ตรวจสอบว่ามีข้อมูลอาหารหรือไม่ ถ้ามีให้บวกราคารวมกับราคาห้องประชุม
        if (!empty($selectedFoodsArray)) {
            echo "<p class='text-all-price' style='font-size:1.3rem;'>ราคารวม(ห้องประชุม/อาหาร)</p>";
            $totalPricess = $totalPrice + ($room_price * $day_book);
            $totalPricess_full = $totalPrice + ($room_price * $day_book);
            echo '<p>ยอดรวม: <span id="totalAmountAfterDiscount">' . number_format($totalPricess, 2) . '</span> บาท</p>';
            echo '<p style="font-size:25px; color: #bcc7c9; text-decoration: line-through;">(ราคาก่อนหักส่วนลด: '. number_format($totalPricess_full, 2) .' บาท)</p>';
            echo '<input type="hidden" name="total_pricess" value="' . $totalPricess . '">';
            echo '<input type="hidden" name="totalPricess_full" value="' . $totalPricess_full . '">';
            $_SESSION['totalPricess_full'] = $totalPricess_full;
            // var_dump($totalPricess_full);
        } else {
            // ถ้าไม่มีข้อมูลอาหารให้แสดงเฉพาะราคาห้องประชุม
            echo "<p class='text-all-price' style='font-size:1.3rem;'>ราคารวมห้องประชุม</p>";
            $totalPricess = $room_price * $day_book;
            $totalPricess_full = $room_price * $day_book;
            // echo number_format($totalPrice, 2);
            echo '<p>ยอดรวม: <span id="totalAmountAfterDiscount">' . number_format($totalPricess, 2) . '</span> บาท</p>';
            echo '<p style="font-size:25px; color: #bcc7c9; text-decoration: line-through;">(ราคาก่อนหักส่วนลด: '. number_format($totalPricess_full, 2) .' บาท)</p>';
            echo '<input type="hidden" name="total_pricess" value="' . $totalPricess . '">';
            echo '<input type="hidden" name="totalPricess_full" value="' . $totalPricess_full . '">';
            $_SESSION['totalPricess_full'] = $totalPricess_full;
        }
        ?> </strong></td>
        <!-- แสดงยอดรวมหลังจากหักส่วนลด -->
        <!-- <p>ยอดรวมหลังหักส่วนลด: <span id="totalAmountAfterDiscount"><?php echo number_format($totalPricess, 2); ?></span> บาท</p> -->
    </tr>
</div>

<div class="promotions" style="padding:0 20px; display:flex; justify-content: right; align-items: center; width: 100%; margin-bottom:10px;">
    <!-- แสดงผลโปรโมชั่นที่เลือก -->
    <div class="mt-4" style="font-size:18px; display:flex; align-items:center; justify-content: center; ">
        <h5 style="margin: 0;">โปรโมชั่นที่เลือก</h5>
        <div class="sss" style="padding:5px; border-radius:5px; margin:0 15px; border:2px solid #dfc700; background-color:#fff4c5;">
            <p id="selectedPromoText" style="margin: 0;">: ยังไม่ได้เลือกโปรโมชั่น</p>
        </div>
   
    <!-- ปุ่มสำหรับเปิด Modal -->
    <div class="text-center">
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#promotionModal">
            แสดงโปรโมชั่น
        </button>
    </div>
</div>
</div>
<!-- แสดงยอดรวมหลังจากหักส่วนลด
<p>ยอดรวมหลังหักส่วนลด: <span id="totalAmountAfterDiscount"></span> บาท</p> -->

<button class="btn-submit" type="submit" style="width:100%;">จอง</button>       
<button class="btn-cancel" type="button" style="width:100%;" onclick="location.href='all_meetingroom.php'">ยกเลิก</button> 
        </form>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

         <!-- Modal สำหรับแสดงโปรโมชั่น -->
    <div class="modal fade" id="promotionModal" tabindex="-1" aria-labelledby="promotionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="promotionModalLabel">เลือกโปรโมชั่น</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <form id="promotionForm">
                    <?php
                if (!empty($room_id)) {
                    // คำสั่ง SQL เพื่อดึงโปรโมชั่นที่ตรงกับ id_room จาก session
                    $sql = "SELECT id_promotion_room, promotion_title_room, promotion_type_room, discount_value_room, start_date_room, end_date_room 
                            FROM promotion_room 
                            WHERE id_room = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $room_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    echo '
                    <div class="form-check" style="padding:5px; border-radius:5px; border:2px solid #dfc700; background-color:#fff4c5; margin:20px;">
                        <input class="form-check-input" type="radio" name="selected_promotion" id="noPromo" value="" checked>
                        <label class="form-check-label" for="noPromo">
                            ไม่ใช้โปรโมชั่น
                        </label>
                    </div>';

                    if ($result->num_rows > 0) {
                        // วนลูปแสดงผลแต่ละโปรโมชั่นที่ตรงกับ id_room ใน session
                        while ($row = $result->fetch_assoc()) {
                            $promotion_id = $row['id_promotion_room'];
                            $promotion_title = $row['promotion_title_room'];
                            $promotion_type = $row['promotion_type_room'];
                            $discount_value = $row['discount_value_room'];
                            $start_date = $row['start_date_room'];
                            $end_date = $row['end_date_room'];

                            // เพิ่มสัญลักษณ์ % หรือ บาท ขึ้นอยู่กับประเภทโปรโมชั่น
                            $discount_suffix = ($promotion_type == 'percentage') ? '%' : 'บาท';

                            // แสดงโปรโมชั่นในรูปแบบ radio button
                            echo '
                            <div class="form-check" style="padding:5px; border-radius:5px; border:2px solid #dfc700; background-color:#fff4c5; margin:20px;">
                                <input class="form-check-input" type="radio" name="selected_promotion" id="promo' . $promotion_id . '" value="' . $promotion_id . '">
                                <label class="form-check-label" for="promo' . $promotion_id . '">
                                     ลด ' . $discount_value . ' ' . $discount_suffix . ' ( ใช้ได้ตั้งแต่ ' . $start_date . ' ถึง ' . $end_date . ')
                                </label>
                            </div>';
                        }
                    } else {
                        echo '<p>ไม่มีโปรโมชั่นสำหรับห้องประชุมนี้</p>';
                    }

                    $stmt->close();
                } else {
                    echo '<p>ไม่พบ id_room ใน session</p>';
                }
                ?>
                </form>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="button" class="btn btn-warning" id="selectPromoBtn" data-bs-dismiss="modal">เลือกโปรโมชั่น</button>
          </div>
        </div>
      </div>
    </div>
<!-- <p><?php echo $totalPricess ?></p> -->
<?php
if (!empty($selectedPromotion)) {
    if ($promotion_type == 'percentage') {
        $totalPricess -= ($totalPricess * ($discount_value / 100));
    } else {
        $totalPricess -= $discount_value;
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // เมื่อผู้ใช้กดปุ่มเลือกโปรโมชั่น
    document.getElementById('selectPromoBtn').addEventListener('click', function() {
        // ดึงค่าของโปรโมชั่นที่เลือก
        const selectedPromo = document.querySelector('input[name="selected_promotion"]:checked');
        
        if (selectedPromo) {
            // แสดงผลโปรโมชั่นที่เลือกในหน้าหลัก
            document.getElementById('selectedPromoText').textContent = selectedPromo.value;
        } else {
            alert("กรุณาเลือกโปรโมชั่น");
        }
    });
</script>
<script>
// เมื่อผู้ใช้กดปุ่มเลือกโปรโมชั่น
document.getElementById('selectPromoBtn').addEventListener('click', function() {
    // ดึงค่าของโปรโมชั่นที่เลือก
    const selectedPromo = document.querySelector('input[name="selected_promotion"]:checked');
    
    if (selectedPromo) {
        // แสดงข้อความโปรโมชั่นที่เลือก
        const promoLabel = selectedPromo.nextElementSibling.textContent;
        document.getElementById('selectedPromoText').textContent = promoLabel;

        // เก็บค่า id_promotion ไปใส่ใน hidden input
        document.getElementById('id_promotion_room').value = selectedPromo.value;

        // ดึงข้อมูลส่วนลดจาก label เช่น "ลด 10% หรือ ลด 500 บาท"
        const discountData = promoLabel.match(/ลด\s(\d+)\s(%|บาท)/);
        let discountAmount = 0;
        const discountValue = parseFloat(discountData[1]);
        const discountType = discountData[2]; // '%' หรือ 'บาท'

        // ดึงยอดรวมก่อนหักส่วนลด
        let totalAmount = <?php echo $totalPricess; ?>;

        // คำนวณส่วนลด
        if (discountType === '%') {
            discountAmount = totalAmount * (discountValue / 100);
        } else if (discountType === 'บาท') {
            discountAmount = discountValue;
        }

        // คำนวณยอดรวมหลังหักส่วนลด
        const discountedTotal = totalAmount - discountAmount;

        // แสดงยอดรวมหลังหักส่วนลด
        document.getElementById('totalAmountAfterDiscount').textContent = discountedTotal.toFixed(2);
        
        // อัพเดตค่า total_pricess ใน input hidden
        document.querySelector('input[name="total_pricess"]').value = discountedTotal.toFixed(2);

        // แสดง id_promotion_room ทันทีในหน้าเว็บ
        document.getElementById('promoSelectedDisplay').textContent = "ID โปรโมชั่นที่เลือกคือ: " + selectedPromo.value;
    } else {
        alert("กรุณาเลือกโปรโมชั่น");
    }
});

</script>

</body>
</html>
