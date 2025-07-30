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

// เก็บค่าของ selectedFoods ในตัวแปร
$selectedFoodsArray = isset($_SESSION['selectedFoods']) ? $_SESSION['selectedFoods'] : [];

$paymentOption = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : '';
$paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : '';

$totalPricess = isset($_POST['total_pricess']) ? $_POST['total_pricess'] : 0;
// ตรวจสอบว่า $totalPricess เป็นตัวเลขและแปลงเป็น float หากเป็นตัวเลข
$totalPricess = is_numeric($totalPricess) ? (float)$totalPricess : 0;
// $promotion_id = isset($_SESSION['id_promotion_room']) ? $_SESSION['id_promotion_room'] : '';

        $sqlSelectBooking = "SELECT id_bookingroom FROM roombooking WHERE name = ? AND date_start = ? AND date_end = ? ORDER BY id_bookingroom DESC LIMIT 1";
        $stmt = $conn->prepare($sqlSelectBooking);
        $stmt->bind_param('sss', $name, $date_start, $date_end);
        $stmt->execute();
       // ดึงผลลัพธ์
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_booking_room = $row['id_bookingroom']; // เก็บค่า id_bookingroom ลงในตัวแปร
            // echo "ID Booking Room: " . $id_booking_room;
        } else {
            echo "No booking room found.";
        }

// ลูปสำหรับการเลือกอาหาร
    foreach ($selectedFoodsArray as $food) { 
        $id_food = $food['id'];
        
        // กรณีที่มีทั้งอาหารและโปรโมชั่น
        if (!empty($id_food) && !empty($id_promotion_room)) {
            $stmt = $conn->prepare("INSERT INTO details_roombooking (id_room, id_food, id_promotion_room, total_pricess, id, id_tutor, id_bookingroom) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidiii", $room_id, $id_food, $id_promotion_room, $total_pricess, $id, $id_tutor, $id_booking_room);
        }
        // กรณีที่มีแค่อาหารแต่ไม่มีโปรโมชั่น
        else if (!empty($id_food) && empty($id_promotion_room)) {
            $stmt = $conn->prepare("INSERT INTO details_roombooking (id_room, id_food, total_pricess, id, id_tutor, id_bookingroom) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iidiii", $room_id, $id_food, $total_pricess, $id, $id_tutor, $id_booking_room);
        }

        // Execute the query for each food item
        if ($stmt->execute()) {
            //echo "เพิ่มรายละเอียดห้องประชุมสำเร็จ!";
            header('location:booking_stepfour.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // กรณีที่ไม่มีอาหารแต่มีโปรโมชั่น (นอกลูป)
    if (empty($selectedFoodsArray) && !empty($id_promotion_room)) {
        $stmt = $conn->prepare("INSERT INTO details_roombooking (id_room, id_promotion_room, total_pricess, id, id_tutor, id_bookingroom) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iidiii", $room_id, $id_promotion_room, $total_pricess, $id, $id_tutor, $id_booking_room);
        
        // Execute the query
        if ($stmt->execute()) {
            //echo "เพิ่มรายละเอียดห้องประชุมสำเร็จ!";
            header('location:booking_stepfour.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // กรณีที่ไม่มีทั้งอาหารและโปรโมชั่น (นอกลูป)
    else if (empty($selectedFoodsArray) && empty($id_promotion_room)) {
        $stmt = $conn->prepare("INSERT INTO details_roombooking (id_room, total_pricess, id, id_tutor, id_bookingroom) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idiii", $room_id, $total_pricess, $id, $id_tutor, $id_booking_room);
        
        // Execute the query
        if ($stmt->execute()) {
            //echo "เพิ่มรายละเอียดห้องประชุมสำเร็จ!";
            header('location:booking_stepfour.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    }


    // echo "Total Pricess: " . $total_pricess;
?>