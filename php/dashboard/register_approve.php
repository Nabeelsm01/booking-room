<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งค่า id_sum_room มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "UPDATE register_course 
    SET register_course.status_register = 'ชำระแล้ว'
    WHERE register_course.id_register_course = ?"; // ปรับเงื่อนไขตามที่ต้องการ

    // เตรียม statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // ลองอัปเดตสถานะ
    if ($stmt->execute()) {
        // ถ้าอัปเดตสำเร็จ
        // echo "การจองได้ถูกยกเลิกเรียบร้อยแล้ว";
        $status_register = 'ชำระแล้ว'; // สถานะปัจจุบันหลังอัปเดต

        $sqlSelect_register_course = "
            SELECT *,course.name_course
            FROM register_course 
            JOIN course ON register_course.id_course = course.id_course 
            WHERE register_course.id_register_course = ?";

        $stmt = $conn->prepare($sqlSelect_register_course);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // ดึงผลลัพธ์
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name_course = $row['name_course'];
        // echo "id_sum_room: " . $id_sum_room;
        } else {
        echo "No booking room found.";
        }
        
        // กำหนดข้อความแจ้งเตือน
        if($status_register === 'ชำระแล้ว') {
            $message = "อนุมัติแล้ว การชำระเงินเสร็จสิ้นแล้ว สำหรับการลงทะเบียนคอร์ส $name_course";
        } else {
            $message = "สถานะการชำระเงินไม่ถูกต้อง สำหรับการลงทะเบียนคอร์ส $name_course";
        }

        // เพิ่มการแจ้งเตือนเข้าไปในตาราง notifications_room
        $sql_insert_nortification = "INSERT INTO notifications_course (id_register_course, message_c) VALUES (?, ?)";
        $stmt_notification = $conn->prepare($sql_insert_nortification);
        
        if ($stmt_notification === false) {
            die('Prepare failed: ' . $conn->error);
        }

        // ใส่ id_sum_room และข้อความแจ้งเตือนลงไป
        $stmt_notification->bind_param("is", $id, $message);

        if ($stmt_notification->execute()) {
            // แจ้งเตือนสำเร็จ
            header('Location:register_admin.php');
        } else {
            echo "Error: " . $stmt_notification->error;
        }

        // ปิด statement สำหรับการแจ้งเตือน
        $stmt_notification->close();
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
