<?php
session_start();
include('../connect.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor'];

    // ค้นหาห้องประชุมล่าสุดที่เพิ่มโดยผู้ใช้
    $sql = "SELECT id_course FROM course WHERE id_tutor = ? ORDER BY id_course DESC LIMIT 1";    // ปรับให้ตรงกับโครงสร้างตารางของคุณ
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tutor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_course = $row['id_course'];

        // ลบห้องประชุมล่าสุด
        $delete_sql = "DELETE FROM course WHERE id_course = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id_course);
        if ($delete_stmt->execute()) {
            $_SESSION['success'] = "";
        } else {
            $_SESSION['errors'] = " " . $delete_stmt->error;
        }
        $delete_stmt->close();
    } else {
        $_SESSION['errors'] = "";
    }

    $stmt->close();
} else {
    $_SESSION['errors'] = "";
}

$conn->close();
header("Location: add_course.php"); // เปลี่ยนเส้นทางไปยังหน้าห้องประชุม
exit();
?>
