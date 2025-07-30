<?php 
    session_start();
    include('../connect.php');

    // // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ และตรวจสอบประเภทผู้ใช้
    // if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'ติวเตอร์') {
    //     // ถ้าไม่ใช่ติวเตอร์ จะถูกเปลี่ยนเส้นทางไปยังหน้า "unauthorized.php"
    //     header("Location: /project end/php/unauthorized.php");
    //     exit();
    // }

    // if (isset($_SESSION['id'])) {
    //     $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
    // } elseif (isset($_SESSION['id_tutor'])) {
    //     $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
    // } elseif (isset($_SESSION['id_provider'])) {
    //     $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
    // }


    // if(!empty($id_tutor)){
    //     $sql = "SELECT course.name_course, course.room_course, tutor.name_lastname_tutor 
    //     FROM course 
    //     INNER JOIN tutor ON course.id_tutor = tutor.id_tutor 
    //     WHERE course.id_tutor = '$id_tutor'";
    //     $result = $conn->query($sql);
    //     $courses = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
    //     // ตรวจสอบว่ามีผลลัพธ์หรือไม่
    //     if ($result->num_rows > 0) {
    //         // ดึงข้อมูลจากผลลัพธ์
    //         while ($row = $result->fetch_assoc()) {
    //             $courses[] = [
    //                 'name_tutor' => $row["name_lastname_tutor"],
    //                 'name_course' => $row["name_course"],
    //                 'number_room' => $row["room_course"]
    //             ];
    //             $tutor_name = $row["name_lastname_tutor"];
    //         }
    //     } else {
    //         // echo "ไม่มีข้อมูลที่ตรงกับเงื่อนไข";
    //     }
    // }else{
    //     // echo 'คุณไม่ใช่ติวเตอร์ ไม่สามารถสร้างห้องประชุมได้';
    // }

    //     // ปิดการเชื่อมต่อฐานข้อมูล
    //     $conn->close();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $room_id = htmlspecialchars($_POST['room']);
        var_dump($room_id);
        // ตรวจสอบว่ารหัสห้องมีอยู่ในฐานข้อมูลหรือไม่
        $stmt = $conn->prepare("SELECT * FROM course WHERE room_course = ?");
        $stmt->bind_param("s", $room_id);  // "s" หมายถึง string
        $stmt->execute();
        $result = $stmt->get_result();    
    
        if ($result->num_rows > 0) {
            // ถ้ารหัสห้องตรงกับฐานข้อมูล
            $room = $result->fetch_assoc();
            $room_name = $room['name_course'];  // หรือถ้าต้องการใช้ชื่อคอร์ส
            header("Location: jitsi_videomeeting.php?room=" . urlencode($room_id)); // พาไปยังห้องประชุม
            exit;
        } else {
            $error_message = "ห้องไม่พบ กรุณาตรวจสอบรหัสห้องอีกครั้ง.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project end/css/meeting_online.css">
    <title>Enter Room Number</title>
    
</head>
<body>
<?php include('../navbar_tap.php'); ?>
<h2 class="hh_name">ห้องประชุมออนไลน์สำหรับคนทั่วไป/นักเรียน</h2>
<div class="container_block">
    <div id="form-container">
        <h2 class="h_name">Enter Room Code (Invite)</h2>
        <form action="" method="POST">
            <input type="text" name="room" placeholder="Enter Room Code" required>
            <button type="submit">Join Meeting</button>
        </form>
        <?php
            // แสดงข้อความผิดพลาดเฉพาะเมื่อมีการกรอกข้อมูลผิด
            if (!empty($error_message)) {
                echo "<p style='color: red;'>$error_message</p>"; // แสดงข้อความผิดพลาด
                unset($error_message); // เคลียร์ข้อความผิดพลาด
            }
            ?>
    </div>

    <!-- <div class="block_element">
  
    // echo "<div class='name_tutor'> ติวเตอร์: " . $tutor_name . "</div>";
    // if(!empty($courses)){
    //     foreach ($courses as $course) {
    //         echo "<div class='list_tutor'> ชื่อคอร์ส: " . $course['name_course'] . ", หมายเลขห้อง: " . $course['number_room'] . "</div><br>";
    //     }
    // }else{
        
    // }
    // ?>
     </div> -->
</div>
<a href="../home.php" class="turn-btn">ย้อนกลับ</a>
</body>
</html>
