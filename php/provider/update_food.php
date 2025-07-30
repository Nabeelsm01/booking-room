<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_food = $_POST['id_food'];
    $name_food = $_POST['name_food'];
    $detail_food = $_POST['detail_food'];
    $type_food = $_POST['type_food'];
    $price_food = $_POST['price_food'];
   

    // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
    if (!empty($_FILES['image_food']['name'])) {
        // ถ้ามีการอัปโหลดไฟล์ใหม่ ให้ทำการอัปโหลด
        $target_dir = "../../img/food/"; // โฟลเดอร์ที่เก็บภาพ
        $target_file = $target_dir . basename($_FILES["image_food"]["name"]);

        // ตรวจสอบประเภทไฟล์
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'avif'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['errors'] = "ประเภทไฟล์ไม่ถูกต้อง";
            header("Location: edit_food.php?id=" . $id_food); // เปลี่ยนเส้นทางกลับไปยังหน้าที่ยืนยัน
            exit();
        }

        
        // อัปโหลดไฟล์
        if (move_uploaded_file($_FILES["image_food"]["tmp_name"], $target_file)) {
            $image_food = $target_file;
        } else {
            $_SESSION['errors'] = "ไม่สามารถอัปโหลดภาพได้";
            header("Location: edit_food.php?id=" . $id_food); // เปลี่ยนเส้นทางกลับไปยังหน้าที่ยืนยัน
            exit();
        }
    } else {
        // ใช้ภาพเดิมหากไม่มีการอัปโหลดไฟล์ใหม่
        $sql = "SELECT image_food FROM food WHERE id_food = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_food);
        $stmt->execute();
        $result = $stmt->get_result();
        $food = $result->fetch_assoc();
        $image_food = $food['image_food'];
    }

    // อัปเดตข้อมูลห้องประชุมในฐานข้อมูล   
    $sql = "UPDATE food SET name_food = ?, detail_food = ?, type_food = ?, price_food = ?, image_food = ? WHERE id_food = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisi", $name_food, $detail_food, $type_food, $price_food, $image_food, $id_food);

    if ($stmt->execute()) {
        $_SESSION['success'] = "อัปเดตข้อมูลห้องประชุมเรียบร้อยแล้ว";
    } else {
        $_SESSION['errors'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: edit_food.php"); // เปลี่ยนเส้นทางไปยังหน้าห้องประชุม
    exit();
}
?>
