<?php
session_start();
include('../../connect.php');
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ และตรวจสอบประเภทผู้ใช้
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'ผู้ให้บริการ') {
    // ถ้าไม่ใช่ติวเตอร์ จะถูกเปลี่ยนเส้นทางไปยังหน้า "unauthorized.php"
    header("Location: /project end/php/unauthorized.php");
    exit();
}

// รับ id_promotion_room จาก URL
$id_promotion_room = isset($_GET['id_promotion_room']) ? intval($_GET['id_promotion_room']) : 0;

if ($id_promotion_room > 0) {
    // เตรียมคำสั่ง SQL
    $sql = "SELECT * FROM promotion_room WHERE id_promotion_room = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_promotion_room);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ดึงข้อมูลเป็นอาร์เรย์แบบ associative
        $promotion = $result->fetch_assoc();
        // ส่งข้อมูลในรูปแบบ JSON
        echo json_encode($promotion);
    } else {
        // ไม่มีข้อมูล
        echo json_encode([]);
    }

    $stmt->close();
} else {
    // ID ไม่ถูกต้อง
    echo json_encode(['error' => 'Invalid promotion ID']);
}

$conn->close();
?>