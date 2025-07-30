<?php
session_start();
include('../connect.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // ลบข้อมูลห้องประชุมจากฐานข้อมูล
        $sql = "DELETE FROM meetingroom WHERE id_room = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "ลบข้อมูลห้องประชุมเรียบร้อยแล้ว";
        } else {
            $_SESSION['errors'] = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
    header("Location: meetingroom_admin.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าห้องประชุม
    exit();
?>
