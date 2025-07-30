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
if (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor'];
}

// $id_providerr = $_SESSION['id_providerr']; // ดึง id_user จาก session

if (isset($_POST['form_tutor'])) {
    $name_course = mysqli_real_escape_string($conn, $_POST['name_course']);
    $detail_course = mysqli_real_escape_string($conn, $_POST['detail_course']);
    $date_course = mysqli_real_escape_string($conn, $_POST['date_course']);
    $date_course_end = mysqli_real_escape_string($conn, $_POST['date_course_end']);
    $day_course = mysqli_real_escape_string($conn, $_POST['day_course']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']); 
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $duration_hour = mysqli_real_escape_string($conn, $_POST['duration_hour']);
    $price_course = mysqli_real_escape_string($conn, $_POST['price_course']);
    $meeting_type = mysqli_real_escape_string($conn, $_POST['meeting_type']);
    $room_course = mysqli_real_escape_string($conn, $_POST['room_course']); 
    //$flie_doc = mysqli_real_escape_string($conn, $_POST['flie_doc']);
    $label_doc = mysqli_real_escape_string($conn, $_POST['label_doc']);

    // Validate file upload
    if (count($errors) == 0) {
        if (isset($_FILES['image_course']) && $_FILES['image_course']['error'] === UPLOAD_ERR_OK) {
            // ทำการอัพโหลดไฟล์ที่นี่
            $target_dir = "../../img/tutor/";
            $target_file = $target_dir . basename($_FILES["image_course"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // ตรวจสอบว่ารูปภาพถูกต้อง
            $check = getimagesize($_FILES["image_course"]["tmp_name"]);
            if($check !== false) {
                // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
                if (move_uploaded_file($_FILES["image_course"]["tmp_name"], $target_file)) {
                    // อัพโหลดไฟล์สำเร็จ
                    $image_course = $target_file; // กำหนดค่า $image_room ให้เป็นเส้นทางที่ถูกต้อง
                } else {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errors[] = "File is not an image.";
            }
        } else {
            $errors[] = "File upload failed.";
        }
        
         // 2. จัดการอัพโหลดเอกสาร
         $flie_doc = '';
         if (isset($_FILES['flie_doc']) && $_FILES['flie_doc']['error'] === UPLOAD_ERR_OK) {
             $doc_target_dir = "../../img/tutor/";
             
             $doc_target_file = $doc_target_dir . basename($_FILES["flie_doc"]["name"]);
             $fileType = strtolower(pathinfo($doc_target_file, PATHINFO_EXTENSION));
             
             // ตรวจสอบนามสกุลไฟล์ที่อนุญาต
             $allowed_extensions = array("pdf", "doc", "docx", "txt");
             if (in_array($fileType, $allowed_extensions)) {
                 if (move_uploaded_file($_FILES["flie_doc"]["tmp_name"], $doc_target_file)) {
                     $flie_doc = $doc_target_file;
                 } else {
                     $errors[] = "ไม่สามารถอัพโหลดเอกสารได้";
                 }
             } else {
                 $errors[] = "อนุญาตเฉพาะไฟล์ PDF, DOC, DOCX, และ TXT เท่านั้น";
             }
         }
    
        
            // ตรวจสอบข้อผิดพลาดก่อนการ insert
            if (empty($errors)) {
                $sql = "INSERT INTO course (name_course, detail_course, date_course, date_course_end, day_course,  start_time, end_time, duration_hour, price_course, meeting_type, room_course, image_course, id_tutor, flie_doc, label_doc) VALUES ('$name_course', '$detail_course', '$date_course', '$date_course_end', '$day_course', '$start_time', '$end_time', '$duration_hour', '$price_course', '$meeting_type', '$room_course', '$image_course', '$id_tutor' ,'$flie_doc', '$label_doc')";
                // $stmt = $conn->prepare($sql);
                // $stmt->bind_param("iss", $id_provider,$name_room, $detail_room, $opacity_room, $price_room, $number_room, $image_room, $address_room);
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Data has been submitted successfully.";
                    header('location:c_addcourse.php'); // เปลี่ยนเป็นหน้าที่คุณต้องการเปลี่ยนไปเมื่อส่งข้อมูลสำเร็จ
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
        header('location: add_course.php');
        exit();
    }
?>