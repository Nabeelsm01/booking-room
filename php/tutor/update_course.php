<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_course = $_POST['id_course'];
    $name_course = $_POST['name_course'];
    $detail_course = $_POST['detail_course'];
    $date_course = $_POST['date_course'];
    $date_course_end = $_POST['date_course_end'];
    $day_course = $_POST['day_course'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $duration_hour = $_POST['duration_hour'];
    $price_course = $_POST['price_course'];
    $meeting_type = $_POST['meeting_type'];
    $room_course = $_POST['room_course'];
    $label_doc = $_POST['label_doc'];
  

    // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
    if (!empty($_FILES['image_course']['name'])) {
        // ถ้ามีการอัปโหลดไฟล์ใหม่ ให้ทำการอัปโหลด
        $target_dir = "../../img/tutor/"; // โฟลเดอร์ที่เก็บภาพ
        $target_file = $target_dir . basename($_FILES["image_course"]["name"]);

        // ตรวจสอบประเภทไฟล์
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'avif'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['errors'] = "ประเภทไฟล์ไม่ถูกต้อง";
            header("Location: edit_formcourse.php?id=" . $id_course); // เปลี่ยนเส้นทางกลับไปยังหน้าที่ยืนยัน
            exit();
        }

        // อัปโหลดไฟล์
        if (move_uploaded_file($_FILES["image_course"]["tmp_name"], $target_file)) {
            $image_course = $target_file;
        } else {
            $_SESSION['errors'] = "ไม่สามารถอัปโหลดภาพได้";
            header("Location: edit_formcourse.php?id=" . $id_course); // เปลี่ยนเส้นทางกลับไปยังหน้าที่ยืนยัน
            exit();
        }
    } else {
        // ใช้ภาพเดิมหากไม่มีการอัปโหลดไฟล์ใหม่
        $sql = "SELECT image_course FROM course WHERE id_course = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_course);
        $stmt->execute();
        $result = $stmt->get_result();
        $course = $result->fetch_assoc();
        $image_course = $course['image_course'];
    }



    $flie_doc = '';

    // ตรวจสอบการอัพโหลดไฟล์ใหม่
    if (!empty($_FILES['flie_doc']['name'])) {
        $target_dir = "../../img/tutor/";
        $target_file = $target_dir . basename($_FILES["flie_doc"]["name"]);
        
        // ตรวจสอบประเภทไฟล์
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'doc', 'docx'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['error'] = "อนุญาตเฉพาะไฟล์ PDF, DOC, และ DOCX เท่านั้น";
            header("Location: edit_formcourse.php?id=" . $id_course);
            exit();
        }

        // ลบไฟล์เก่า (ถ้ามี)
        $sql = "SELECT flie_doc FROM course WHERE id_course = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_course);
        $stmt->execute();
        $result = $stmt->get_result();
        $old_file = $result->fetch_assoc();
        
        if (!empty($old_file['flie_doc']) && file_exists($old_file['flie_doc'])) {
            unlink($old_file['flie_doc']);
        }

        // อัพโหลดไฟล์ใหม่
        if (move_uploaded_file($_FILES["flie_doc"]["tmp_name"], $target_file)) {
            $flie_doc = $target_file;
        } else {
            $_SESSION['error'] = "ไม่สามารถอัพโหลดไฟล์ได้";
            header("Location: edit_formcourse.php?id=" . $id_course);
            exit();
        }
    } else {
        // ใช้ไฟล์เดิมถ้าไม่มีการอัพโหลดใหม่
        $sql = "SELECT flie_doc FROM course WHERE id_course = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_course);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_file = $result->fetch_assoc();
        $flie_doc = $current_file['flie_doc'];
    }

    // อัปเดตข้อมูลห้องประชุมในฐานข้อมูล
    $sql = "UPDATE course SET name_course = ?, detail_course = ?, date_course = ?, date_course_end = ?, day_course = ?, start_time = ?, end_time = ?, duration_hour = ?, price_course = ?, meeting_type = ?, room_course = ? , image_course = ?  , 	flie_doc = ?  , label_doc = ? WHERE id_course = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssisssssi", $name_course, $detail_course, $date_course, $date_course_end, $day_course, $start_time, $end_time, $duration_hour, $price_course, $meeting_type, $room_course, $image_course, $flie_doc , $label_doc, $id_course);

    if ($stmt->execute()) {
        $_SESSION['success'] = "อัปเดตข้อมูลห้องประชุมเรียบร้อยแล้ว";
    } else {
        $_SESSION['errors'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: edit_course.php"); // เปลี่ยนเส้นทางไปยังหน้าห้องประชุม
    exit();
}
?>
