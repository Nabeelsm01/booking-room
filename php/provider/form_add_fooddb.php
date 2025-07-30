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

if (isset($_POST['form_food'])) {
    $name_food = mysqli_real_escape_string($conn, $_POST['name_food']);
    $detail_food = mysqli_real_escape_string($conn, $_POST['detail_food']);
    $type_food = mysqli_real_escape_string($conn, $_POST['type_food']);
    $price_food = mysqli_real_escape_string($conn, $_POST['price_food']);

    // Validate file upload
    if (count($errors) == 0) {
        if (isset($_FILES['image_food']) && $_FILES['image_food']['error'] === UPLOAD_ERR_OK) {
            // ทำการอัพโหลดไฟล์ที่นี่
            $target_dir = "../../img/food/";
            $target_file = $target_dir . basename($_FILES["image_food"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // ตรวจสอบว่ารูปภาพถูกต้อง
            $check = getimagesize($_FILES["image_food"]["tmp_name"]);
            if($check !== false) {
                // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
                if (move_uploaded_file($_FILES["image_food"]["tmp_name"], $target_file)) {
                    // อัพโหลดไฟล์สำเร็จ
                    $image_food = $target_file; // กำหนดค่า $image_food ให้เป็นเส้นทางที่ถูกต้อง
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
                $sql = "INSERT INTO food (name_food, detail_food, type_food, price_food, image_food ,id_provider) VALUES ('$name_food', '$detail_food', '$type_food', '$price_food','$image_food','$id_provider')";
                // $stmt = $conn->prepare($sql);
                // $stmt->bind_param("iss", $id_provider,$name_room, $detail_room, $opacity_room, $price_room, $number_room, $image_room, $address_room);
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Data has been submitted successfully.";
                    header('location:c_addfood.php'); // เปลี่ยนเป็นหน้าที่คุณต้องการเปลี่ยนไปเมื่อส่งข้อมูลสำเร็จ
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
        header('location: form_add_food.php');
        exit();
    }
?>