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
    $_SESSION['totalAmount'] = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : '';

    $_SESSION['totalAmount'] = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : '';

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
$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : '';

$totalAmount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : ''; //ราคารวมหลังมัดจำ/เต็ม->

?>
<?php


$sqlSelect = "
    SELECT * 
    FROM details_roombooking 
    JOIN roombooking ON details_roombooking.id_bookingroom = roombooking.id_bookingroom 
    JOIN meetingroom ON details_roombooking.id_room = meetingroom.id_room 
    WHERE roombooking.name = ? AND roombooking.date_start = ? AND roombooking.date_end = ? 
    ORDER BY roombooking.id_bookingroom DESC LIMIT 1";

    $stmt = $conn->prepare($sqlSelect);
    $stmt->bind_param('sss', $name, $date_start, $date_end);
    $stmt->execute();

    // ดึงผลลัพธ์
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_pricess = $row['total_pricess']; 
    $id_booking = $row['id_bookingroom'];
    $id_details = $row['id_details'];
    $id = $row['id']; 
    $id_tutor = $row['id_tutor']; 
    $name_room = $row['name_room']; 
    // echo "Total Price: " . $total_pricess;
    // echo "id_booking: " . $id_booking;
    // echo "id_details: " . $id_details;
    // echo "id: " . ($id !== null ? $id : 'NULL'); 
    // echo "id_tutor: " . ($id_tutor !== null ? $id_tutor : 'NULL');    
    // echo "name_room: " . $name_room;
    } else {
    echo "No booking room found.";
    }
 
// -------------insert ข้อมูลการจ่ายตังค์ส่วนนนี้ๆ -----------------

// // ตรวจสอบว่า $image_room มีข้อมูล
$qrCodeUpload = isset($_SESSION['qrCodeUpload']) ? $_SESSION['qrCodeUpload'] : '';
$errors=[];

// Validate file upload
    if (isset($_FILES['qrCodeUpload']) && $_FILES['qrCodeUpload']['error'] === UPLOAD_ERR_OK) {
        
        // ทำการอัพโหลดไฟล์ที่นี่
        $target_dir = "../../img/slip/";
        $target_file = $target_dir . basename($_FILES["qrCodeUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // ตรวจสอบว่ารูปภาพถูกต้อง
        $check = getimagesize($_FILES["qrCodeUpload"]["tmp_name"]);
        if($check !== false) {
            // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
            if (move_uploaded_file($_FILES["qrCodeUpload"]["tmp_name"], $target_file)) {
                // อัพโหลดไฟล์สำเร็จ
                $qrCodeUpload = $target_file; // กำหนดค่า $qrCodeUpload ให้เป็นเส้นทางที่ถูกต้อง
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    } else {
        $errors[] = "File upload failed.";
    }

       
            $sql_insert_payment = "INSERT INTO payment_room (id_bookingroom, total_pricess, totalAmount, paymentOption ,paymentMethod ,qrCodeUpload) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql_insert_payment);
            
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            
            $stmt->bind_param("idisss", $id_booking, $total_pricess, $totalAmount, $paymentOption, $paymentMethod, $qrCodeUpload);
            if ($stmt->execute()) {
                //  echo "Payment for booking room ID $id_booking inserted successfully.<br>";
                header('location:booking_stepfive.php');
            } else {
                // echo "Error inserting payment for booking room ID $id_bookingroom: " . htmlspecialchars($stmt->error) . "<br>";
                // header('location:booking_stepfive.php');
            }

            // Close statement
            $stmt->close();



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
            $id_paymentroom = $row['id_paymentroom'];
            $status = $row['status'];
            // echo "id_paymentroom: " . $id_paymentroom;
            // echo "status: " . $status;
            } else {
            echo "No booking room found.";
            }



// แสดงการจองห้องประชุม
            $sql_insert_booking = "INSERT INTO room_booking_summary (id_bookingroom, id_details, id_paymentroom) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert_booking);
            
            if ($stmt === false) {
                die('Prepare failed: ' . $conn->error);
            }

            $stmt->bind_param("iii", $id_booking, $id_details, $id_paymentroom);
 
            if ($stmt->execute()) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            
            $stmt->close();


            $sqlSelectsum = "
            SELECT * 
            FROM room_booking_summary 
            JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom 
            WHERE roombooking.name = ? AND roombooking.date_start = ? AND roombooking.date_end = ? 
            ORDER BY roombooking.id_bookingroom DESC LIMIT 1";

            $stmt = $conn->prepare($sqlSelectsum);
            $stmt->bind_param('sss', $name, $date_start, $date_end);
            $stmt->execute();

            // ดึงผลลัพธ์
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_sum_room = $row['id_sum_room'];
            // echo "id_sum_room: " . $id_sum_room;
            } else {
            echo "No booking room found.";
            }

// การแจ้งเตือนห้องประชุม

            if($status === 'ชำระเงินครบแล้ว'){
                $message = "การชำระเงินเสร็จสิ้นแล้ว สำหรับการจองห้องประชุม $name_room";
            }else if($status === 'มัดจำแล้ว'){
                $message = "ชำระเงินมัดจำแล้ว สำหรับการจองห้องประชุม $name_room";
            }else if($status === 'รอดำเนินการ'){
                $message = "รอตรวจสอบการชำระเงิน 1-2วัน สำหรับการจองห้องประชุม $name_room";
            }else {
                $message = "สถานะการชำระเงินไม่ถูกต้อง";
            }

            $sql_insert_nortification = "INSERT INTO notifications_room (id_sum_room, message) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_insert_nortification);
            
            if ($stmt === false) {
                die('Prepare failed: ' . $conn->error);
            }

            $stmt->bind_param("is", $id_sum_room, $message);
 
            if ($stmt->execute()) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            
            $stmt->close();
            $conn->close();
?>