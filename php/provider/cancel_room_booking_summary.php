<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งค่า id_sum_room มาหรือไม่
if (isset($_GET['id_sum_room'])) {
    $id_sum_room = $_GET['id_sum_room'];

    // เตรียม SQL เพื่ออัปเดตสถานะการจองเป็น 'cancelled'
    $sql = "UPDATE room_booking_summary 
            SET status_summary = 'cancelled' 
            WHERE id_sum_room = ?";

    // เตรียม statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_sum_room);

    // ลองอัปเดตสถานะ
    if ($stmt->execute()) {
        // ถ้าอัปเดตสำเร็จ
        // echo "การจองได้ถูกยกเลิกเรียบร้อยแล้ว";
        // คุณสามารถใช้การ redirect กลับไปยังหน้าการจอง
        header('Location: room_booking_summary.php');
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
