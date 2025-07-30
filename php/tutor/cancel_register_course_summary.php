<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งค่า id_sum_room มาหรือไม่
if (isset($_GET['id_register_course'])) {
    $id_register_course = $_GET['id_register_course'];

    // เตรียม SQL เพื่ออัปเดตสถานะการจองเป็น 'cancelled'
    $sql = "UPDATE register_course 
            SET status_register = 'ยกเลิก' 
            WHERE id_register_course = ?";

    // เตรียม statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_register_course);

    // ลองอัปเดตสถานะ
    if ($stmt->execute()) {
        // ถ้าอัปเดตสำเร็จ
        // echo "การจองได้ถูกยกเลิกเรียบร้อยแล้ว";
        // คุณสามารถใช้การ redirect กลับไปยังหน้าการจอง
        header('Location:register_course_summary.php');
        // exit();
    } else {
        // ถ้าอัปเดตล้มเหลว
        echo "เกิดข้อผิดพลาดในการยกเลิกการจอง";
    }

    // ปิด statement และ connection
    $stmt->close();
    $conn->close();
} else {
    echo "ไม่พบการจองที่ต้องการยกเลิก";
}
?>
