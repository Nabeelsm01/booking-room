<?php
// session_start();
// include('connect.php');

// // ตรวจสอบว่ามีการส่งข้อมูลหรือไม่
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $id_noti_room = $_POST['id_noti_room'];

//     // อัปเดตสถานะการแจ้งเตือนเป็น "read"
//     $sql = "UPDATE notifications_room SET status = 'read' WHERE id_noti_room = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $id_noti_room);
//     $stmt->execute();

//     // ตรวจสอบผลการอัปเดต
//     if ($stmt->affected_rows > 0) {
//         // ส่งสถานะสำเร็จกลับไปให้ AJAX
//         echo json_encode(['status' => 'success']);
//     } else {
//         // หากการอัปเดตล้มเหลว
//         echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตสถานะการแจ้งเตือนได้']);
//     }

//     // ปิด statement
//     $stmt->close();
// } else {
//     // กรณีที่ไม่มีการ POST ข้อมูล
//     echo json_encode(['status' => 'error', 'message' => 'ไม่รองรับวิธีการร้องขอ']);
// }

// // ปิดการเชื่อมต่อ
// $conn->close();

session_start();
include('connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_noti = $_POST['id_noti'];
    $type = $_POST['type'];

    // ตรวจสอบประเภทของการแจ้งเตือนว่าเป็นห้องประชุมหรือคอร์ส
    if ($type === 'room') {
        // อัปเดตสถานะการแจ้งเตือนเป็น "read" สำหรับการจองห้องประชุม
        $sql = "UPDATE notifications_room SET status = 'read' WHERE id_noti_room = ?";
    } elseif ($type === 'course') {
        // อัปเดตสถานะการแจ้งเตือนเป็น "read" สำหรับการลงทะเบียนคอร์ส
        $sql = "UPDATE notifications_course SET status_noti_course = 'read' WHERE id_noti_course = ?";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบประเภทของการแจ้งเตือน']);
        exit;
    }

    // เตรียม statement สำหรับการอัปเดต
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถเตรียมคำสั่ง SQL ได้']);
        exit;
    }

    $stmt->bind_param("i", $id_noti);
    $stmt->execute();

    // ตรวจสอบผลการอัปเดต
    if ($stmt->affected_rows > 0) {
        // ส่งสถานะสำเร็จกลับไปให้ AJAX
        $redirectUrl = ($type === 'room') ? 'provider/room_booking_summary.php' : 'tutor/register_course_summary.php';
        echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
    } else {
        // หากการอัปเดตล้มเหลว
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตสถานะการแจ้งเตือนได้']);
        $redirectUrl = ($type === 'room') ? 'provider/room_booking_summary.php' : 'tutor/register_course_summary.php';
    }

    // ปิด statement
    $stmt->close();
} else {
    // กรณีที่ไม่มีการ POST ข้อมูล
    echo json_encode(['status' => 'error', 'message' => 'ไม่รองรับวิธีการร้องขอ']);
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
