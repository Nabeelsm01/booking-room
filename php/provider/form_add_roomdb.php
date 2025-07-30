<?php
session_start();
include('../connect.php');
$errors = array(); 

// // ตรวจสอบว่า id_user ถูกตั้งค่าใน session หรือไม่
// if (!isset($_SESSION['id_providerr'])) {
//     $errors[] = "Error: User ID not found in session.";
//     $_SESSION['errors'] = implode('<br>', $errors);
//     // header('location: form_add_data.php');
//     exit();
// }
if (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider'];
}

// $id_providerr = $_SESSION['id_providerr']; // ดึง id_user จาก session

if (isset($_POST['form_room'])) {
    $name_room = mysqli_real_escape_string($conn, $_POST['name_room']);
    $detail_room = mysqli_real_escape_string($conn, $_POST['detail_room']);
    $opacity_room = mysqli_real_escape_string($conn, $_POST['opacity_room']);
    $price_room = mysqli_real_escape_string($conn, $_POST['price_room']);
    $room_type = mysqli_real_escape_string($conn, $_POST['room_type']);
    $number_room = mysqli_real_escape_string($conn, $_POST['number_room']);
    $address_room = mysqli_real_escape_string($conn, $_POST['address_room']);

    // Validate file upload
    if (count($errors) == 0) {
        if (isset($_FILES['image_room']) && $_FILES['image_room']['error'] === UPLOAD_ERR_OK) {
            // ทำการอัพโหลดไฟล์ที่นี่
            $target_dir = "../../img/";
            $target_file = $target_dir . basename($_FILES["image_room"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // ตรวจสอบว่ารูปภาพถูกต้อง
            $check = getimagesize($_FILES["image_room"]["tmp_name"]);
            if($check !== false) {
                // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
                if (move_uploaded_file($_FILES["image_room"]["tmp_name"], $target_file)) {
                    // อัพโหลดไฟล์สำเร็จ
                    $image_room = $target_file; // กำหนดค่า $image_room ให้เป็นเส้นทางที่ถูกต้อง
                } else {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errors[] = "File is not an image.";
            }
        } else {
            $errors[] = "File upload failed.";
        }
    
        
            // ตรวจสอบข้อผิดพลาดก่อนการ insert
            if (empty($errors)) {
                $sql = "INSERT INTO meetingroom (id_provider, name_room, detail_room, opacity_room, price_room, room_type, number_room, image_room, address_room) VALUES ('$id_provider', '$name_room', '$detail_room', '$opacity_room', '$price_room','$room_type', '$number_room', '$image_room', '$address_room')";
                // $stmt = $conn->prepare($sql);
                // $stmt->bind_param("iss", $id_provider,$name_room, $detail_room, $opacity_room, $price_room, $number_room, $image_room, $address_room);
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Data has been submitted successfully.";
                    header('location:c_addroom.php'); // เปลี่ยนเป็นหน้าที่คุณต้องการเปลี่ยนไปเมื่อส่งข้อมูลสำเร็จ
                    exit();
                } else {
                    $errors[] = "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
       
    }
    echo"id successfull";
} else{
    echo"not found id ";
}    
    
    // จัดการข้อผิดพลาด
    if (!empty($errors)) {
        $_SESSION['errors'] = implode('<br>', $errors);
        header('location: form_add_room.php');
        exit();
    }
?>