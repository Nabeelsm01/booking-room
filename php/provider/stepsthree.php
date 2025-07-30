<?php
session_start();
include('../connect.php');

// รับค่าจาก POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedFoods = isset($_POST['selectedFoods']) ? $_POST['selectedFoods'] : '[]';
    
    // แปลง JSON เป็น Array
    $selectedFoodsArray = json_decode($selectedFoods, true);

    // แสดงข้อมูล
    echo "<h1>ข้อมูลการสั่งซื้อ</h1>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ชื่อเมนู</th><th>ราคา/จาน</th><th>จำนวน</th><th>ราคารวม</th></tr>";
    
    $totalQuantity = 0;
    $totalPrice = 0;
    
    if (!empty($selectedFoodsArray)) {
        foreach ($selectedFoodsArray as $food) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($food['name']) . "</td>";
            echo "<td>" . htmlspecialchars($food['price']) . "</td>";
            echo "<td>" . htmlspecialchars($food['quantity']) . "</td>";
            echo "<td>" . htmlspecialchars($food['totalPrice']) . "</td>";
            echo "</tr>";
            
            $totalQuantity += $food['quantity'];
            $totalPrice += $food['totalPrice'];
        }
        
        echo "<tr><td colspan='2'><strong>รวมทั้งหมด</strong></td><td><strong>$totalQuantity</strong></td><td><strong>$totalPrice</strong></td></tr>";
        echo "</table>";
    } else {
        echo "<p>ไม่มีเมนูอาหารที่เลือกไว้</p>";
    }
}
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
้<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="p-detail">
            <img class="image_room" src="<?php echo htmlspecialchars($image_room); ?>" alt="รูปห้องประชุม">
            <p><strong>ชื่อห้องประชุม:</strong> <?php echo htmlspecialchars($room_name); ?></p>
            <p><strong>รหัสห้อง:</strong> <?php echo htmlspecialchars($room_id); ?></p> 
            <p><strong>ความจุห้อง:</strong> <?php echo htmlspecialchars($opacity_room); ?> คน</p>
            <p><strong>ประเภท:</strong> <?php echo htmlspecialchars($room_type); ?></p>
            <p><strong>ราคา:</strong> <?php echo htmlspecialchars($room_price); ?> บาท/วัน</p>
            <p><strong>ที่ตั้ง:</strong> <?php echo htmlspecialchars($address_room); ?></p>
            </div>
            <div class="p-detail_2">
                <p><strong>ผู้จองห้องประชุม</strong></p>
                <p><strong>ชื่อผู้จอง:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>อีเมลผู้จอง:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($number); ?></p>
                <p><strong>วันที่เริ่มจอง:</strong> <?php echo htmlspecialchars($date_start); ?></p>
                <p><strong>วันที่สิ้นสุดจอง:</strong> <?php echo htmlspecialchars($date_end); ?></p>
                <p><strong>ระยะเวลา(วัน):</strong> <?php echo htmlspecialchars($day_book); ?></p>
                </div>
      
</body>
</html>

<?php
session_start(); // เริ่มต้นเซสชัน

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
    // $_SESSION['room_detail'] = isset($_POST['room_detail']) ? $_POST['room_detail'] : '';
    $_SESSION['room_price'] = isset($_POST['room_price']) ? $_POST['room_price'] : '';
    $_SESSION['room_type'] = isset($_POST['room_type']) ? $_POST['room_type'] : '';
    $_SESSION['opacity_room'] = isset($_POST['opacity_room']) ? $_POST['opacity_room'] : '';
    $_SESSION['address_room'] = isset($_POST['address_room']) ? $_POST['address_room'] : '';

    $_SESSION['id_provider'] = isset($_POST['id_provider']) ? $_POST['id_provider'] : '';

    // เก็บข้อมูล selectedFoods ลงในตัวแปรและเซสชัน
    $selectedFoods = isset($_POST['selectedFoods']) ? $_POST['selectedFoods'] : '[]';
    $_SESSION['selectedFoods'] = json_decode($selectedFoods, true);
}

// เก็บค่าจากเซสชันลงในตัวแปร
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$number = isset($_SESSION['number']) ? $_SESSION['number'] : '';
$date_start = isset($_SESSION['date_start']) ? $_SESSION['date_start'] : '';
$date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : '';
$day_book = isset($_SESSION['day_book']) ? $_SESSION['day_book'] : '';

$image_room = isset($_SESSION['image_room']) ? $_SESSION['image_room'] : '';
$room_id = isset($_SESSION['room_id']) ? $_SESSION['room_id'] : '';
$room_name = isset($_SESSION['room_name']) ? $_SESSION['room_name'] : '';
// $room_detail = isset($_SESSION['room_detail']) ? $_SESSION['room_detail'] : '';
$room_price = isset($_SESSION['room_price']) ? $_SESSION['room_price'] : ''; 
$room_type = isset($_SESSION['room_type']) ? $_SESSION['room_type'] : ''; 
$opacity_room = isset($_SESSION['opacity_room']) ? $_SESSION['opacity_room'] : ''; 
$address_room = isset($_SESSION['address_room']) ? $_SESSION['address_room'] : ''; 

$id_provider = isset($_SESSION['id_provider']) ? $_SESSION['id_provider'] : ''; 

// เก็บค่าของ selectedFoods ในตัวแปร
$selectedFoodsArray = isset($_SESSION['selectedFoods']) ? $_SESSION['selectedFoods'] : [];
?>
