<?php
session_start();
include('../connect.php');


        if (isset($_SESSION['id_tutor'])) {
        $id_tutor = $_SESSION['id_tutor'];

            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                // ลบข้อมูลห้องประชุมจากฐานข้อมูล
                $sql = "DELETE FROM course WHERE id_course = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "ลบข้อมูลห้องคอร์สติวเรียบร้อยแล้ว";
                } else {
                    $_SESSION['errors'] = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $stmt->error;
                }
                $stmt->close();
            }

            $conn->close();
            header("Location: delete_course.php"); // เปลี่ยนเส้นทางกลับไปยังหน้าห้องประชุม
            exit();
        } else {
                echo "<p>ไม่มี id_tutor ในเซสชัน</p>";
            }
            
            $conn->close();
?>
        
