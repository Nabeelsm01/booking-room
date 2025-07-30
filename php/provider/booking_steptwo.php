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
// booking_page.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['number'] = $_POST['number'];
    $_SESSION['date_start'] = $_POST['date_start'];
    $_SESSION['date_end'] = $_POST['date_end'];
    $_SESSION['day_book'] = $_POST['day_book'];

    $_SESSION['image_room'] = $_POST['image_room'];
    $_SESSION['room_id'] = $_POST['room_id'];
    $_SESSION['room_name'] = $_POST['room_name'];
    // $_SESSION['room_detail'] = $_POST['room_detail'];
    $_SESSION['room_price'] = $_POST['room_price'];
    $_SESSION['room_type'] = $_POST['room_type'];
    $_SESSION['opacity_room'] = $_POST['opacity_room'];
    $_SESSION['address_room'] = $_POST['address_room'];

    $_SESSION['id_provider'] = $_POST['id_provider'];

}   

    // รับค่าจากฟอร์มด้วย POST
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $number = isset($_SESSION['number']) ? $_SESSION['number'] : '';
    $date_start = isset($_SESSION['date_start']) ? $_SESSION['date_start'] : '';
    $date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : '';
    $day_book = isset($_SESSION['day_book']) ? $_SESSION['day_book'] : '';

    // รับค่าอื่น ๆ จาก POST
    $image_room = isset($_SESSION['image_room']) ? $_SESSION['image_room'] : '';
    $room_id = isset($_SESSION['room_id']) ? $_SESSION['room_id'] : '';
    $room_name = isset($_SESSION['room_name']) ? $_SESSION['room_name'] : '';
    // $room_details = isset($$_SESSION['room_details']) ? ($$_SESSION['room_details']) : '';
    $room_price = isset($_SESSION['room_price']) ? $_SESSION['room_price'] : ''; 
    $room_type = isset($_SESSION['room_type']) ? $_SESSION['room_type'] : ''; 
    $opacity_room = isset($_SESSION['opacity_room']) ? $_SESSION['opacity_room'] : ''; 
    $address_room = isset($_SESSION['address_room']) ? $_SESSION['address_room'] : ''; 

    $id_provider = isset($_SESSION['id_provider']) ? $_SESSION['id_provider'] : ''; 

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
        } .stepper {
            display: flex;
            justify-content: space-around; 
            margin-bottom: 10px;
            margin-top: 10px; /* ขยับลงมานิดนึง */
            margin-left: 23%; /* ขยับไปทางขวามือ */
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

        /* bootstrap */
        .custombtnfood{
            width:100%; 
            height:100%; 
            background:none;
            border:none;
            color:#6075ab;
        }
        .custombtnfood:hover{
            background-color:  #90a7dc; /* สีเขียว */
            color: #fff;
        }
        .modal-custom{
            max-width: 50%; 
        }
        .body-custom{
            background-color: #d3e0ff; 
        }
        .b1{
            margin:auto;
            width: 97%;
            height: 70px;
            display:flex;
            background-color: #fff;
            border-radius:5px;
            color:#6075ab;
            box-shadow: 0 3px 3px 3px rgba(0, 0, 0, 0.02);
            padding:5px;
            justify-content: space-between; 
            margin-bottom:5px;
        }
        .b2{
            /* border:1px solid red; */
        }
        .b2.left{
            flex-grow:1;
            max-width:100px;
            width:100%;
            height:auto;
            object-fit:cover;
            border-radius:5px;
        }
        .b2.center1{
            flex-grow:1;
            margin:auto;
            margin-left:10px;
            width:250px;
            overflow:hidden;
            white-space: nowrap; /* ป้องกันการตัดบรรทัดใหม่ */
            text-overflow: ellipsis; /* แสดง ... เมื่อข้อความยาวเกินไป */
        }
        .b2.center2{
            flex-grow:1;
            margin:auto;
            margin-left:10px;
            width:50px;
            text-align:left;
            overflow:hidden;
        }
        .b2.right{
            flex-grow:1;
            text-align:center;
            margin:auto;
        }
        .modal3-img{
            max-width:10vw;
            width:100%;
            height:10vw;
            object-fit:cover;
            border-radius:10px;
            margin-right:10px;
        }
        .modal3-name{
            font-size:22px;
            color:#6075ab;
            max-width:20vw;
            max-height:5vw;
            height:auto;
            overflow:hidden;
            text-overflow: ellipsis; /* แสดง ... เมื่อข้อความยาวเกินไป */
            margin-bottom:5px;
        }
        .modal3-price{
            font-size:20px;
            color:#6075ab;
        }
        .modal3container{
            display:flex;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #6075ab;
            justify-content: left;
            margin: 0; /* ลบ margin ออกเพื่อลดช่องว่าง */
            padding:  0; /* เพิ่ม padding เล็กน้อยให้ยังมีระยะ */
            line-height:0.5 ;  /* ปรับ line-height เพื่อลดช่องว่างระหว่างบรรทัด */
            margin-left:5px;
        }

        .total-all {
            display: flex;
            flex-direction: column; /* แสดงจำนวนรวมและราคารวมในแนวตั้ง */
            margin-left: 0;
            text-align: left;
        }
        .modal-footer button {
            margin: 0; /* ปรับให้ไม่มีช่องว่างระหว่างปุ่ม */
        }
        .total22 {
            font-size: 16px;
            color: #6075ab;
            margin: 0;
            padding: 0;
            line-height: 1.5;
            margin-left: 5px;
        }

        .total-all2 {

        }

        .custom-modal2-footer {
            display: flex;
            justify-content: space-between; /* กระจายเนื้อหาระหว่างซ้ายและขวา */
            align-items: center;
            width: 100%;
        }

        .total-all22 {
            display: flex;
            gap: 10px; /* เพิ่มช่องว่างระหว่างปุ่ม */
        }




            /* ซ่อนปุ่มเพิ่มลด */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield; /* สำหรับ Firefox */
        }
        .btn-custom5{
            border-radius: 35%;
            background-color: #d3e0ff; 
            border:2px solid #90a7dc; 
            transition: 0.5s ease; /* เพิ่มการเปลี่ยนสีแบบนุ่มนวล */
        }
        .btn-custom5:hover {
            background-color:#6075ab;
            border:none; 
            color:#d3e0ff; ;
        }

        
    </style>
</head>
<body>
<a href="booking_room.php" class="turn-btn">ย้อนกลับ</a>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">จองห้องประชุม</h2>
             <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);margin-left: 50px; margin-top: 32px;"  aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="all_meetingroom.php" style="text-decoration:none;">ห้องประชุม</a></li>
                    <li class="breadcrumb-item active" ><a href="booking_room.php" style="text-decoration:none;">รายละเอียดการจอง</a></li>
                    <li class="breadcrumb-item active" aria-current="page">เลือกอาหาร</li>
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
        <div class="blockcenter_3">
            <div class="p-detail">
                <img class="image_room" src="<?php echo htmlspecialchars($image_room); ?>" alt="รูปห้องประชุม">
                <p><strong>ชื่อห้องประชุม:</strong> <?php echo htmlspecialchars($room_name); ?></p>
                <p><strong>รหัสห้อง:</strong> <?php echo htmlspecialchars($room_id); ?></p> 
                <p><strong>ความจุห้อง:</strong> <?php echo htmlspecialchars($opacity_room); ?> คน</p>
                <p><strong>ประเภท:</strong> <?php echo htmlspecialchars($room_type); ?></p>
                <p><strong>ราคา:</strong> ฿<?php echo htmlspecialchars($room_price); ?> บาท/วัน</p>
                <p><strong>ที่ตั้ง:</strong> <?php echo htmlspecialchars($address_room); ?></p>
            </div>
            
            <div class="line-height"></div>
            <div class="p-detail_2">
                <div class="text-head-con">
                  <h class="text-head"><strong>ผู้จองห้องประชุม</strong></h></div>
                <p><strong>ชื่อผู้จอง:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>อีเมลผู้จอง:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($number); ?></p>
                <p><strong>วันที่เริ่มจอง:</strong> <?php echo htmlspecialchars($date_start); ?></p>
                <p><strong>วันที่สิ้นสุดจอง:</strong> <?php echo htmlspecialchars($date_end); ?></p>
                <p><strong>ระยะเวลา(วัน):</strong> <?php echo htmlspecialchars($day_book); ?></p>
                </div>
            <div class="line-height"></div>
            <div class="input-detail">
            <div class="input">
                <div class="text-head-con">
                <h class="text-head"><strong>เลือกเมนูอาหาร</strong>(ไม่เลือกก็ได้)</h></div>


                <p><strong>เลือกอาหารหลัก</strong></p>
                <div class="food">     <!-- -----------ปุ่มม 1 สำหรับบเลือกอาหารหลักกก ----------------------------------- -->
                    <!-- Button trigger modal -->
                    <button type="button" id="addMainFoodButton" class="btn btn-primary custombtnfood" data-bs-toggle="modal" data-bs-target="#staticBackdrop" >
                    <i class="bi bi-plus-circle"></i> เพิ่มอาหารหลัก
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-custom" >
                        <div class="modal-content" >
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">เลือกเมนูอาหาร</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body body-custom">
                             <!-- Alert Message -->
                                <div id="alertMessage" style="display: none; position: absolute; top: 30px; left: 50%; transform: translateX(-50%); background-color: #b5ffc7; border:1px solid #337e00; color: #337e00; padding: 10px; border-radius: 5px; z-index: 1050;">
                                    คุณได้เลือกอาหารสำเร็จ! อาหารถูกส่งไปหน้าเมนูที่เลือกไว้
                                </div>
                            <?php
                                
                            echo" <p>เลือกอาหารหลัก</p> ";    
                            
                            // ตรวจสอบว่า id_provider มีอยู่ในเซสชันหรือไม่
                            if (isset($_SESSION['id_provider'])) {
                                $id_provider = $_SESSION['id_provider'];

                                    // สร้างคำสั่ง SQL
                                    $sql = "SELECT *  FROM food WHERE id_provider = ? AND type_food = 'อาหารหลัก'";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $id_provider); // ใช้ i สำหรับ integer
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // เช็คผลลัพธ์
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {

                            echo"    <div class='b1'> ";
                            echo"    <img class='b2 left' src='". $row["image_food"] ."' alt='รูปอาหาร'> ";
                            echo"    <p class='b2 center1'>". $row["name_food"] ."</p> ";
                            echo"    <p class='b2 center2'><strong>". $row["price_food"] ."</strong> บาท/จาน </p> ";
                            echo"    <div class='b2 right'>";
                            echo"    <button class='btn btn-primary btn-md rounded-pill shadow-sm select-food' data-name='".$row["name_food"]."' data-price='".$row["price_food"]."' data-image='".$row["image_food"]."' data-type='".$row["type_food"]."' data-id='".$row["id_food"]."'>เลือก</button>";
                            // echo"    <a class='btn btn-primary btn-md rounded-pill shadow-sm' data-bs-toggle='modal' data-bs-target='#threeModal'>เลือก</a>";
                            // echo"    <a class='b2 right' data-bs-toggle='modal' data-bs-target='#threeModal'>0000</a>";
                            echo"    </div>";
                            // echo"    <a class='b2 right' href='#.php'>0000</a> ";
                            echo"    </div> ";
                                // <!-- เพิ่ม b1 ตรงนี้ -->
                                        }
                                    } else {
                                        echo "<p>*ยังไม่มีอาหารสำหรับห้องประชุมอันนี้</p>";
                                    }
                                
                                    // $stmt->close();
                                } else {
                                    echo "<p>ไม่มี id_provider ในเซสชัน</p>";
                                }
                                
                                // $conn->close();
                                ?>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                            <!-- ปุ่มนี้จะเปิด Modal ที่สอง -->
                            <div class="total-all22">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#secondModal">เมนูที่เลือกไว้</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>




               <!-- ปุ่มเพิ่มอาหารว่าง -->
                <p><strong>อาหารว่าง</strong></p>
                <div class="food">     <!-- -----------ปุ่มม 2 สำหรับบเลือกอาหารว่างงง ----------------------------------- --> 
                    <button type="button" id="addMainFoodButton" class="btn btn-secondary custombtnfood" data-bs-toggle="modal" data-bs-target="#snackModal">
                        <i class="bi bi-plus-circle"></i> เพิ่มอาหารว่าง
                    </button>
                </div>

                <!-- Modal สำหรับเลือกอาหารว่าง -->
                <div class="modal fade" id="snackModal" tabindex="-1" aria-labelledby="snackModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-custom">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="snackModalLabel">เลือกอาหารว่าง</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body body-custom">
                                <!-- Alert Message -->
                                <div id="alertMessageSnack" style="display: none; position: absolute; top: 30px; left: 50%; transform: translateX(-50%); background-color: #b5ffc7; border:1px solid #337e00; color: #337e00; padding: 10px; border-radius: 5px; z-index: 1050;">
                                    คุณได้เลือกอาหารสำเร็จ! อาหารถูกส่งไปหน้าเมนูที่เลือกไว้
                                </div>
                            <?php
                                echo "<p>เลือกอาหารว่าง</p>";

                                // ตรวจสอบว่า id_provider มีอยู่ในเซสชันหรือไม่
                                if (isset($_SESSION['id_provider'])) {
                                    $id_provider = $_SESSION['id_provider'];

                                    // สร้างคำสั่ง SQL สำหรับอาหารว่าง
                                    $sql = "SELECT * FROM food WHERE id_provider = ? AND type_food = 'อาหารว่าง'";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $id_provider); // ใช้ i สำหรับ integer
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // เช็คผลลัพธ์
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {

                                            echo "<div class='b1'>";
                                            echo "<img class='b2 left' src='". $row["image_food"] ."' alt='รูปอาหารว่าง'>";
                                            echo "<p class='b2 center1'>". $row["name_food"] ."</p>";
                                            echo "<p class='b2 center2'><strong>". $row["price_food"] ."</strong> บาท/จาน</p>";
                                            echo "<div class='b2 right'>";
                                            echo "<button class='btn btn-primary btn-md rounded-pill shadow-sm select-food' data-name='".$row["name_food"]."' data-price='".$row["price_food"]."' data-image='".$row["image_food"]."' data-type='".$row["type_food"]."' data-id='".$row["id_food"]."'>เลือก</button>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                    } else {
                                        echo "<p>*ยังไม่มีอาหารว่าง</p>";
                                    }
                                } else {
                                    echo "<p>ไม่พบข้อมูลผู้ให้บริการ</p>";
                                }
                            ?>
                            </div>
                            <div class="modal-footer">
                              <div class="total-all22">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#secondModal">เมนูที่เลือกไว้</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>

                  

                <!-- ปุ่มเพิ่มเครื่องดื่ม -->
                <p><strong>เครื่องดื่ม</strong></p>
                <div class="food">    <!-- -----------ปุ่มม 3 สำหรับบเลือกเดครื่องดื่ม ----------------------------------- -->

                    <button type="button" id="addMainFoodButton" class="btn btn-secondary custombtnfood" data-bs-toggle="modal" data-bs-target="#drinkModal">
                        <i class="bi bi-plus-circle"></i> เพิ่มเครื่องดื่ม
                    </button>
                </div>

                <!-- Modal สำหรับเลือกเครื่องดื่ม -->
                <div class="modal fade" id="drinkModal" tabindex="-1" aria-labelledby="drinkModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-custom">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="drinkModalLabel">เลือกเครื่องดื่ม</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body body-custom">
                                <!-- Alert Message -->
                                <div id="alertMessageDrink" style="display: none; position: absolute; top: 30px; left: 50%; transform: translateX(-50%); background-color: #b5ffc7; border:1px solid #337e00; color: #337e00; padding: 10px; border-radius: 5px; z-index: 1050;">
                                    คุณได้เลือกเครื่อมดื่มสำเร็จ! เครื่องดื่มถูกส่งไปหน้าเมนูที่เลือกไว้
                                </div>
                                <?php
                                    echo "<p>เลือกเครื่องดื่ม</p>";

                                    // ตรวจสอบว่า id_provider มีอยู่ในเซสชันหรือไม่
                                    if (isset($_SESSION['id_provider'])) {
                                        $id_provider = $_SESSION['id_provider'];

                                        // สร้างคำสั่ง SQL สำหรับเครื่องดื่ม
                                        $sql = "SELECT * FROM food WHERE id_provider = ? AND type_food = 'เครื่องดื่ม'";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $id_provider); // ใช้ i สำหรับ integer
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        // เช็คผลลัพธ์
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<div class='b1'>";
                                                echo "<img class='b2 left' src='". $row["image_food"] ."' alt='รูปเครื่องดื่ม'>";
                                                echo "<p class='b2 center1'>". $row["name_food"] ."</p>";
                                                echo "<p class='b2 center2'><strong>". $row["price_food"] ."</strong> บาท/แก้ว</p>";
                                                echo "<div class='b2 right'>";
                                                echo "<button class='btn btn-primary btn-md rounded-pill shadow-sm select-food' data-name='".$row["name_food"]."' data-price='".$row["price_food"]."' data-image='".$row["image_food"]."' data-type='".$row["type_food"]."' data-id='".$row["id_food"]."'>เลือก</button>";
                                                echo "</div>";
                                                echo "</div>";
                                            }
                                        } else {
                                            echo "<p>*ยังไม่มีเครื่องดื่ม</p>";
                                        }
                                    } else {
                                        echo "<p>ไม่พบข้อมูลผู้ให้บริการ</p>";
                                    }
                                ?>
                            </div>
                            <div class="modal-footer">
                              <div class="total-all22">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#secondModal">เมนูที่เลือกไว้</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                                          <!-- -----------form สำหรับบส่งข้อมูลอาหาร hidden ----------------------------------- -->
                <form id="orderForm" method="POST" action="booking_stepthree.php">
                        <input type="hidden" name="selectedFoods" id="selectedFoodsInput">
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

                        <button class="btn-submit-2" type="submit">จอง</button>
                        <button class="btn-cancel-2" type="button" onclick="location.href='all_meetingroom.php'" style="padding:6px 0;">ยกเลิก</button>
                    </form>    
                 
                </div> 
            </div> 
        </div> 

       <!-- Modal 2 -->
        <div class="modal fade" id="secondModal" tabindex="-1" aria-labelledby="secondModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-custom">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="secondModalLabel">เมนูอาหารที่เลือกไว้</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- ข้อมูลอาหารที่เลือกจะถูกแสดงที่นี่ -->
                        <div id="selectedFoods"></div>
                    </div>
                    <div class="modal-footer custom-modal2-footer">
                        <div class="total-all2">
                            <p id="totalMenus" class="total22">จำนวนเมนูทั้งหมด: 0 เมนู</p>
                            <p id="totalMenu2" class="total22">จำนวนรายการทั้งหมด: 0 รายการ</p>
                            <p id="totalPrice2" class="total22">ราคารวมทั้งหมด: 0 บาท</p>
                        </div> 
                        <div class="total-all22">
                            <button type="button" class="btn btn-secondary" id="closeSecondModal">กลับ</button>
                
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <!-- Modal 3 -->
        <div class="modal fade" id="threeModal" tabindex="-1" aria-labelledby="threeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="threeModalLabel">เลือกอาหาร/จำนวน</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal3container">
                    <img class="modal3-img" id="threeModalImage" class="img-fluid" src="" alt="รูปอาหาร" >
                    <div class="modal3container2">
                        <p class="modal3-name" id="threeModalName">ชื่อเมนู</p>
                        <p class="modal3-price" id="threeModalPrice">ราคา</p>

                         <!-- จำนวนและปุ่มเพิ่มลด -->
                        <div class="quantity-control" style="display:flex;">
                            <p class="modal3-price" >จำนวน: &nbsp;</p>
                            <div class="btn-quantity d-flex align-items-center">
                                <button type="button" id="decreaseQuantity" class="btn btn-custom5 ">-</button>
                                <input type="number" id="quantityInput" class="form-control mx-1" value="0" min="0" step="1" style="width: 80px;">
                                <button type="button" id="increaseQuantity" class="btn btn-custom5" style="border-radius:35%;">+</button>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                 <!-- จำนวนรวมและราคารวม -->
                <div class="total-all  d-flex flex-column">
                    <p id="totalQuantity" class="total">จำนวนรวม: 0 </p>&nbsp;
                    <p id="totalPrice" class="total"> ราคารวม: 0 บาท</p>
                </div>
                <div class="total-all2">
                    <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">กลับ</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">ยืนยัน</button>
                </div>
            </div>
            </div>
        </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script> 
        flatpickr("#date-start", {
            dateFormat: "Y-m-d",
            onChange: updateDuration
        });

        flatpickr("#date-end", {
            dateFormat: "Y-m-d",
            onChange: updateDuration
        });
        
        function updateDuration() {
            const startDateInput = document.getElementById('date-start')._flatpickr;
            const endDateInput = document.getElementById('date-end')._flatpickr;

            const startDate = new Date(startDateInput.selectedDates[0]);
            const endDate = new Date(endDateInput.selectedDates[0]);

            if (startDate && endDate && endDate >= startDate) {
                const timeDiff = endDate - startDate;
                const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                document.getElementById('duration').value = `${daysDiff} วัน`;
            } else {
                document.getElementById('duration').value = '';
            }
        }
    </script> 
   <script>
  document.addEventListener('DOMContentLoaded', function () {
    var firstModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    var secondModal = new bootstrap.Modal(document.getElementById('secondModal'));
    var threeModal = new bootstrap.Modal(document.getElementById('threeModal'));

    document.getElementById('closeSecondModal').addEventListener('click', function () {
      secondModal.hide();
      setTimeout(function() {
        firstModal.show();
      }, 300);
    });

    document.getElementById('closeThreeModal').addEventListener('click', function () {
      threeModal.hide();
      setTimeout(function() {
        firstModal.show();
      }, 300);
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // อ้างอิงถึงองค์ประกอบต่างๆ
    const foodPriceElement = document.getElementById('threeModalPrice');
    const quantityInput = document.getElementById('quantityInput');
    const totalQuantityElement = document.getElementById('totalQuantity');
    const totalPriceElement = document.getElementById('totalPrice');
    const increaseButton = document.getElementById('increaseQuantity');
    const decreaseButton = document.getElementById('decreaseQuantity');
    const confirmButton = document.getElementById('confirmButton');

    const selectedFoodsInput = document.getElementById('selectedFoodsInput');

    let pricePerUnit = 0;
    let foodName = '';
    let foodType = '';
    let foodImage = '';
    let foodId = 0;
    let selectedFoods = []; 

   
    // ดึงข้อมูลจากปุ่มเลือกใน modal1
    document.querySelectorAll('.select-food').forEach(button => {
        button.addEventListener('click', function () {
            foodName = this.getAttribute('data-name');
            pricePerUnit = parseFloat(this.getAttribute('data-price'));
            foodImage = this.getAttribute('data-image');
            foodType = this.getAttribute('data-type');
            // foodId = this.getAttribute('data-id');
            foodId = parseInt(this.getAttribute('data-id'));

            // ส่งข้อมูลไปยัง modal3
            const modal3 = new bootstrap.Modal(document.getElementById('threeModal'));
            document.getElementById('threeModalName').textContent = "ชื่อเมนู: " + foodName;
            document.getElementById('threeModalPrice').textContent = "ราคา: " + pricePerUnit.toFixed(2) + " บาท";
            document.getElementById('threeModalImage').src = foodImage;
         
            quantityInput.value = 0;
            updateTotals();

            modal3.show();
        });
    });

    // ฟังก์ชันจัดการการเปลี่ยนแปลงของ input
    quantityInput.addEventListener('input', function () {
        updateTotals();
    });

    // ฟังก์ชันเพิ่มจำนวน
    increaseButton.addEventListener('click', function () {
        quantityInput.value = parseInt(quantityInput.value, 10) + 1;
        updateTotals();
    });

    // ฟังก์ชันลดจำนวน
    decreaseButton.addEventListener('click', function () {
        quantityInput.value = Math.max(0, parseInt(quantityInput.value, 10) - 1);
        updateTotals();
    });

    // ฟังก์ชันคำนวณจำนวนรวมและราคารวม
    function updateTotals() {
        const quantity = parseInt(quantityInput.value, 10);
        const totalPrice = quantity * pricePerUnit;
        totalQuantityElement.textContent = 'จำนวนรวม: ' + quantity;
        totalPriceElement.textContent = 'ราคารวม: ' + totalPrice.toFixed(2) + ' บาท';
    }

    // ปุ่มยืนยันใน modal3
    confirmButton.addEventListener('click', function () {
        const quantity = parseInt(quantityInput.value, 10);

        if (foodName === "" || isNaN(pricePerUnit) || quantity <= 0) {
            alert("กรุณากรอกจำนวนและเลือกอาหารก่อน");
            return;
        }

        // ตรวจสอบว่าอาหารที่เลือกมีอยู่แล้วใน selectedFoods หรือไม่
        const existingFood = selectedFoods.find(item => item.name === foodName);

        if (existingFood) {
            // หากมีอยู่แล้ว ให้อัปเดตจำนวนและราคารวม
            existingFood.quantity += quantity;
            existingFood.totalPrice = existingFood.quantity * pricePerUnit;
        } else {
            // หากยังไม่มี ให้เพิ่มเป็นรายการใหม่
            selectedFoods.push({
                name: foodName,
                type: foodType,
                price: pricePerUnit,
                image: foodImage,
                id: foodId,
                quantity: quantity,
                totalPrice: quantity * pricePerUnit
            });
        }

        // ปิด modal3
        const modal3 = bootstrap.Modal.getInstance(document.getElementById('threeModal'));
        modal3.hide();

        // แสดงข้อความแจ้งเตือน
        const alertMessage = document.getElementById('alertMessage');
        alertMessage.style.display = 'block';

        // ซ่อนข้อความแจ้งเตือนหลังจาก 3 วินาที
        setTimeout(function () {
            alertMessage.style.display = 'none';
        }, 2000);

        // สำหรับ Modal อาหารว่าง
        const alertMessageSnack = document.getElementById('alertMessageSnack');
        alertMessageSnack.style.display = 'block';
        setTimeout(function () {
            alertMessageSnack.style.display = 'none';
        }, 2000);

        // สำหรับ Modal อาหารว่าง
        const alertMessageDrink = document.getElementById('alertMessageDrink');
        alertMessageDrink.style.display = 'block';
        setTimeout(function () {
            alertMessageDrink.style.display = 'none';
        }, 2000);

        // อัปเดต selectedFoods
        updateSelectedFoods();
        updateButtonText(); // อัปเดตข้อความปุ่ม
    });

    // ฟังก์ชันอัปเดต selectedFoods ใน modal2
    function updateSelectedFoods() {
        const selectedFoodsContainer = document.getElementById('selectedFoods');
        
        // คำนวณจำนวนเมนูทั้งหมด (จำนวนเมนูที่แตกต่างกัน)
        let totalMenus = selectedFoods.length;

        // คำนวณจำนวนรายการทั้งหมดและราคารวม
        let totalMenu2 = selectedFoods.reduce((total, item) => total + item.quantity, 0);
        let totalPrice2 = selectedFoods.reduce((total, item) => total + item.totalPrice, 0);

        // สร้างตารางเพื่อแสดงข้อมูล
        selectedFoodsContainer.innerHTML = `
            <table class="table">
                <thead>
                    <tr>
                        <th>รูป</th>
                        <th>ชื่อเมนู</th>
                        <th>ราคา/จาน</th>
                        <th>จำนวน</th>
                        <th>ราคารวม</th>
                        <th>ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    ${selectedFoods.map((item, index) => `
                        <tr>
                            <td><img src='${item.image}' alt='รูปอาหาร' style='width: 70px; height: 50px; object-fit:cover; border-radius:5px;'></td>
                            <td class="text-left align-middle">${item.name}</td>
                            <td class="text-center align-middle">${item.price.toFixed(2)} บาท</td>
                            <td class="text-center align-middle">${item.quantity}</td>
                            <td class="text-center align-middle">${item.totalPrice.toFixed(2)} บาท</td>
                            <td class="text-center align-middle"><button class='btn btn-danger remove-item' data-index='${index}'>ลบ</button></td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        
        // อัปเดตฟุตเตอร์ของ modal2
        document.getElementById('totalMenus').textContent = `เมนูทั้งหมด: ${totalMenus} เมนู`;
        document.getElementById('totalMenu2').textContent = `จำนวนรวมทั้งหมด: ${totalMenu2} จาน`;
        document.getElementById('totalPrice2').textContent = `ราคารวมทั้งหมด: ${totalPrice2.toFixed(2)} บาท`;

        // อัปเดตค่าฟิลด์ hidden ของฟอร์ม
        selectedFoodsInput.value = JSON.stringify(selectedFoods);
    }

    // ฟังก์ชันลบรายการจาก selectedFoods
    document.getElementById('selectedFoods').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-item')) {
            const index = event.target.getAttribute('data-index');

            // เพิ่มการยืนยันก่อนลบ
            const confirmDelete = confirm("คุณต้องการลบรายการนี้จริงหรือไม่?");
            if (confirmDelete) {
                selectedFoods.splice(index, 1);
                updateSelectedFoods();
            }
        }
    });

    // ปิด modal2
    document.getElementById('closeSecondModal').addEventListener('click', function () {
        const modal2 = bootstrap.Modal.getInstance(document.getElementById('secondModal'));
        modal2.hide();
    });
});

</script>

</body>
</html>

