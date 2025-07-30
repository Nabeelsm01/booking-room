<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_room = $_POST['id_room'];
    $name_room = $_POST['name_room'];
    $detail_room = $_POST['detail_room'];
    $opacity_room = $_POST['opacity_room'];
    $price_room = $_POST['price_room'];
    $room_type = $_POST['room_type'];
    $number_room = $_POST['number_room'];
    $address_room = $_POST['address_room'];

    // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
    if (!empty($_FILES['image_room']['name'])) {
        // ถ้ามีการอัปโหลดไฟล์ใหม่ ให้ทำการอัปโหลด
        $target_dir = "../../img/"; // โฟลเดอร์ที่เก็บภาพ
        $target_file = $target_dir . basename($_FILES["image_room"]["name"]);

        // ตรวจสอบประเภทไฟล์
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'avif'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['errors'] = "ประเภทไฟล์ไม่ถูกต้อง";
            header("Location: edit.php?id=" . $id_room); // เปลี่ยนเส้นทางกลับไปยังหน้าที่ยืนยัน
            exit();
        }

        
        // อัปโหลดไฟล์
        if (move_uploaded_file($_FILES["image_room"]["tmp_name"], $target_file)) {
            $image_room = $target_file;
        } else {
            $_SESSION['errors'] = "ไม่สามารถอัปโหลดภาพได้";
            header("Location: edit.php?id=" . $id_room); // เปลี่ยนเส้นทางกลับไปยังหน้าที่ยืนยัน
            exit();
        }
    } else {
        // ใช้ภาพเดิมหากไม่มีการอัปโหลดไฟล์ใหม่
        $sql = "SELECT image_room FROM meetingroom WHERE id_room = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_room);
        $stmt->execute();
        $result = $stmt->get_result();
        $room = $result->fetch_assoc();
        $image_room = $room['image_room'];
    }

    // อัปเดตข้อมูลห้องประชุมในฐานข้อมูล   
    $sql = "UPDATE meetingroom SET name_room = ?, detail_room = ?, opacity_room = ?, price_room = ?, room_type = ?, number_room = ?, image_room = ?, address_room = ? WHERE id_room = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssi", $name_room, $detail_room, $opacity_room, $price_room, $room_type, $number_room, $image_room, $address_room, $id_room);

    if ($stmt->execute()) {
        $_SESSION['success'] = "อัปเดตข้อมูลห้องประชุมเรียบร้อยแล้ว";
    } else {
        $_SESSION['errors'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: edit.php"); // เปลี่ยนเส้นทางไปยังหน้าห้องประชุม
    exit();
}
?>
