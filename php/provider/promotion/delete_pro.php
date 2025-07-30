<?php
session_start();
include('../../connect.php');
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ และตรวจสอบประเภทผู้ใช้
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'ผู้ให้บริการ') {
    // ถ้าไม่ใช่ติวเตอร์ จะถูกเปลี่ยนเส้นทางไปยังหน้า "unauthorized.php"
    header("Location: /project end/php/unauthorized.php");
    exit();
}
// รับ id โปรโมชั่นจาก URL หรือฟอร์ม
$id_promotion_room = $_GET['id_promotion_room']; // หรือ $_POST['id']

// เตรียมคำสั่ง SQL
$sql = "DELETE FROM promotion_room WHERE id_promotion_room = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_promotion_room);

// ตรวจสอบการดำเนินการ
if ($stmt->execute()) {
    echo "ลบโปรโมชั่นสำเร็จ!";
    header("Location: promotion_room.php"); // เปลี่ยนเส้นทางไปยังหน้ารายการโปรโมชั่น
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
