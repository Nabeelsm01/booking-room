<?php
session_start();
include('../connect.php');

// กำหนดตัวแปร $user_name
$user_name = '';

if (isset($_SESSION['name_lastname'])) {
    $user_name = $_SESSION['name_lastname'];
} elseif (isset($_SESSION['name_lastname_tutor'])) {
    $user_name = $_SESSION['name_lastname_tutor'];
} elseif (isset($_SESSION['name_provider'])) {
    $user_name = $_SESSION['name_provider'];
}

$email = $_SESSION['email'];

// ตรวจสอบประเภทของผู้ใช้และดึง id_user จากเซสชัน
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
} elseif (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
} elseif (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
}

?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // เก็บข้อมูลจาก POST ลงในเซสชัน
    $_SESSION['name_std'] = isset($_POST['name_std']) ? $_POST['name_std'] : '';
    $_SESSION['email_std'] = isset($_POST['email_std']) ? $_POST['email_std'] : '';
    $_SESSION['number_std'] = isset($_POST['number_std']) ? $_POST['number_std'] : '';
    $_SESSION['qrCodeUpload_course'] = isset($_POST['qrCodeUpload_course']) ? $_POST['qrCodeUpload_course'] : '';
    $_SESSION['id_course'] = isset($_POST['id_course']) ? $_POST['id_course'] : 0;
}
// รับค่าจากเซสชัน
$name_std = isset($_SESSION['name_std']) ? $_SESSION['name_std'] : '';
$email_std = isset($_SESSION['email_std']) ? $_SESSION['email_std'] : '';
$number_std = isset($_SESSION['number_std']) ? $_SESSION['number_std'] : '';
$qrCodeUpload_course = isset($_SESSION['qrCodeUpload_course']) ? $_SESSION['qrCodeUpload_course'] : '';
$id_course = isset($_SESSION['id_course']) ? $_SESSION['id_course'] : 0;


?>
<?php
// -------------insert ข้อมูลการจ่ายตังค์ส่วนนนี้ๆ -----------------

// // ตรวจสอบว่า $image_room มีข้อมูล
$qrCodeUpload_course = isset($_SESSION['qrCodeUpload_course']) ? $_SESSION['qrCodeUpload_course'] : '';
$errors=[];

// Validate file upload
    if (isset($_FILES['qrCodeUpload_course']) && $_FILES['qrCodeUpload_course']['error'] === UPLOAD_ERR_OK) {
        
        // ทำการอัพโหลดไฟล์ที่นี่
        $target_dir = "../../img/slip/";
        $target_file = $target_dir . basename($_FILES["qrCodeUpload_course"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // ตรวจสอบว่ารูปภาพถูกต้อง
        $check = getimagesize($_FILES["qrCodeUpload_course"]["tmp_name"]);
        if($check !== false) {
            // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
            if (move_uploaded_file($_FILES["qrCodeUpload_course"]["tmp_name"], $target_file)) {
                // อัพโหลดไฟล์สำเร็จ
                $qrCodeUpload_course = $target_file; // กำหนดค่า $qrCodeUpload_course ให้เป็นเส้นทางที่ถูกต้อง
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    } else {
        $errors[] = "File upload failed.";
    }


            $sql_insert_register_course = "INSERT INTO register_course (name_std, email_std, number_std, qrCodeUpload_course, id_course, id) VALUES (?, ?, ?, ?, ?, ?) ";

            $stmt = $conn->prepare($sql_insert_register_course);
            
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            
            $stmt->bind_param("ssssii", $name_std, $email_std, $number_std, $qrCodeUpload_course, $id_course, $id);
            if ($stmt->execute()) {
                //  echo "Payment for booking room ID $id_booking inserted successfully.<br>";
                header('location:register_stepthree.php');
            } else {
                // echo "Error inserting payment for booking room ID $id_bookingroom: " . htmlspecialchars($stmt->error) . "<br>";
                // header('location:booking_stepfive.php');
            }

            // Close statement
            $stmt->close();


// ดึง id course ออกมา

        $sqlSelect_register_course = "
            SELECT *
            FROM register_course 
            JOIN course ON register_course.id_course = course.id_course 
            WHERE register_course.id_course = ? AND register_course.id = ? 
            ORDER BY register_course.id_register_course DESC LIMIT 1";

            $stmt = $conn->prepare($sqlSelect_register_course);
            $stmt->bind_param('ii', $id_course, $id);
            $stmt->execute();

            // ดึงผลลัพธ์
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_register_course = $row['id_register_course'];
            $name_course = $row['name_course'];
            $status_register = $row['status_register'];
            } else {
            echo "No booking room found.";
            }
            var_dump($id_register_course);
            var_dump($name_course);

// การแจ้งเตือนคอร์ส

            // if($status_register === 'ชำระแล้ว'){
            //     $message = "การชำระเงินเสร็จสิ้นแล้ว สำหรับการจองห้องประชุม $name_course";
            // }else if($status_register === 'ชำระไม่สำเร็จ'){
            //     $message = "ชำระไม่สำเร็จ สำหรับการจองห้องประชุม $name_course";
            // }else {
            //     $message = "สถานะการชำระเงินไม่ถูกต้อง";
            // }

            // $sql_insert_nortification = "INSERT INTO notifications_room (message) VALUES (?)";
            // $stmt = $conn->prepare($sql_insert_nortification);

            // if ($stmt === false) {
            //     die('Prepare failed: ' . $conn->error);
            // }

            // $stmt->bind_param("s", $message);

            // if ($stmt->execute()) {
            //     // echo "New record created successfully";
            // } else {
            //     echo "Error: " . $stmt->error;
            // }

            // $stmt->close();
            // $conn->close();

            if($status_register === 'ชำระแล้ว'){
                $message_c = "การชำระเงินเสร็จสิ้นแล้ว สำหรับการลงทะเบียนคอร์ส $name_course";
            }else if($status_register === 'ชำระไม่สำเร็จ'){
                $message_c = "ชำระไม่สำเร็จ สำหรับการลงทะเบียนคอร์ส $name_course";
            }else if($status_register === 'รอดำเนินการ'){
                $message_c = "รอตรวจสอบการชำระเงิน ภายใน 1-2ชม. สำหรับการลงทะเบียนคอร์ส $name_course";
            }else {
                $message_c = "สถานะการชำระเงินไม่ถูกต้อง";
            }

            $sql_insert_nortification_c = "INSERT INTO notifications_course (id_register_course, message_c) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_insert_nortification_c);
            
            if ($stmt === false) {
                die('Prepare failed: ' . $conn->error);
            }

            $stmt->bind_param("is", $id_register_course, $message_c);
 
            if ($stmt->execute()) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            
            $stmt->close();
            $conn->close();
?>