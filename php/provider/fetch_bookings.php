<?php
session_start();
include('../connect.php');

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Query เพื่อนำวันจองที่มีอยู่ในระบบ
    $sql = "
    SELECT roombooking.date_start, roombooking.date_end 
    FROM details_roombooking 
    JOIN roombooking ON details_roombooking.id_bookingroom = roombooking.id_bookingroom 
    WHERE details_roombooking.id_room = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reserved_dates = [];
    while ($row = $result->fetch_assoc()) {
        $startDate = new DateTime($row['date_start']);
        $endDate = new DateTime($row['date_end']);
        
        // ใช้การวนลูปเพื่อสร้างวันที่ทั้งหมดที่ถูกจอง
        for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $reserved_dates[] = [
                'title' => 'ไม่ว่าง', // ข้อความแสดงในปฏิทิน
                'start' => $date->format('Y-m-d'), // วันถูกจอง
                'end' => $date->format('Y-m-d'), // วันถูกจอง
                'allDay' => true, // ให้แสดงเป็นการจองทั้งวัน
                'backgroundColor' => '#b1bcc8', // สีแดงอ่อน
                'borderColor' => '#b1bcc8', // สีขอบ
                'classNames' => ['booked'], // เพิ่ม class สำหรับการจัดการ CSS
            ];
        }
    }

    // ส่งคืนวันที่จองที่มีอยู่ในรูปแบบ JSON
    echo json_encode($reserved_dates);
} else {
    // หากไม่ได้รับค่า room_id
    echo json_encode(['error' => 'Room ID not provided']);
}

$conn->close();
?>
