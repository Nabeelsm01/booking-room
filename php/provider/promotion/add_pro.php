<?php
session_start();
include('../../connect.php');
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ และตรวจสอบประเภทผู้ใช้
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'ผู้ให้บริการ') {
    // ถ้าไม่ใช่ติวเตอร์ จะถูกเปลี่ยนเส้นทางไปยังหน้า "unauthorized.php"
    header("Location: /project end/php/unauthorized.php");
    exit();
}
// รับข้อมูลจากฟอร์ม
$promotion_title_room = $_POST['promotion_title_room'];
$promotion_type_room = $_POST['promotion_type_room'];
$discount_value_room = $_POST['discount_value_room'];
$start_date_room = $_POST['start_date_room'];
$end_date_room = $_POST['end_date_room'];
$promo_code_room = isset($_POST['promo_code_room']) ? $_POST['promo_code_room'] : NULL;
$terms = $_POST['terms'];
$status = 'active'; // หรือกำหนดค่าเริ่มต้นอื่นๆ
$id_room = $_POST['id_room'];

// เตรียมคำสั่ง SQL
$sql = "INSERT INTO promotion_room (promotion_title_room, promotion_type_room, discount_value_room, start_date_room, end_date_room, promo_code_room, terms, status, id_room) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisssssi", $promotion_title_room, $promotion_type_room, $discount_value_room, $start_date_room, $end_date_room, $promo_code_room, $terms, $status, $id_room);

// ตรวจสอบการดำเนินการ
if ($stmt->execute()) {
    echo "เพิ่มโปรโมชั่นสำเร็จ!";
    header("Location: promotion_room.php"); // เปลี่ยนเส้นทางไปยังหน้ารายการโปรโมชั่น
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
