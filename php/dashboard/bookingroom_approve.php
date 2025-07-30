<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งค่า id_sum_room มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "UPDATE payment_room 
    JOIN room_booking_summary ON payment_room.id_paymentroom = room_booking_summary.id_paymentroom
    SET payment_room.status = 'ชำระเงินครบแล้ว'
    WHERE room_booking_summary.id_sum_room = ?"; // ปรับเงื่อนไขตามที่ต้องการ



    // เตรียม statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // ลองอัปเดตสถานะ
    if ($stmt->execute()) {
        $status = 'ชำระเงินครบแล้ว'; // สถานะปัจจุบันหลังอัปเดต

        $sqlSelectsum = "
        SELECT name_room
        FROM room_booking_summary 
        JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom 
        JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details 
        JOIN meetingroom ON details_roombooking.id_room = meetingroom.id_room 
        WHERE room_booking_summary.id_sum_room = ?";

        $stmt = $conn->prepare($sqlSelectsum);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // ดึงผลลัพธ์
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name_room = $row['name_room'];
        // echo "id_sum_room: " . $id_sum_room;
        } else {
        echo "No booking room found.";
        }
        
        // กำหนดข้อความแจ้งเตือน
        if($status === 'ชำระเงินครบแล้ว') {
            $message = "อนุมัติแล้ว การชำระเงินเสร็จสิ้นแล้ว สำหรับการจองห้องประชุม $name_room";
        } else {
            $message = "สถานะการชำระเงินไม่ถูกต้อง สำหรับการจองห้องประชุม $name_room";
        }

        // เพิ่มการแจ้งเตือนเข้าไปในตาราง notifications_room
        $sql_insert_nortification = "INSERT INTO notifications_room (id_sum_room, message) VALUES (?, ?)";
        $stmt_notification = $conn->prepare($sql_insert_nortification);
        
        if ($stmt_notification === false) {
            die('Prepare failed: ' . $conn->error);
        }

        // ใส่ id_sum_room และข้อความแจ้งเตือนลงไป
        $stmt_notification->bind_param("is", $id, $message);

        if ($stmt_notification->execute()) {
            // แจ้งเตือนสำเร็จ
            header('Location:bookingroom_admin.php');
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
