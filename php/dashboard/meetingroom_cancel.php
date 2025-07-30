<?php
session_start();
include('../connect.php');

    // ค้นหาห้องประชุมล่าสุดที่เพิ่มโดยผู้ใช้
    $sql = "SELECT id_room FROM meetingroom ORDER BY id_room DESC LIMIT 1";    // ปรับให้ตรงกับโครงสร้างตารางของคุณ
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_room = $row['id_room'];

        // ลบห้องประชุมล่าสุด
        $delete_sql = "DELETE FROM meetingroom WHERE id_room = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id_room);
        if ($delete_stmt->execute()) {
            $_SESSION['success'] = "cancel add meetingroom";
        } else {
            $_SESSION['errors'] = " " . $delete_stmt->error;
        }
        $delete_stmt->close();
    } else {
        $_SESSION['errors'] = "";
    }

$stmt->close();
$conn->close();
header("Location: meetingroom_add.php"); // เปลี่ยนเส้นทางไปยังหน้าห้องประชุม
exit();
?>
