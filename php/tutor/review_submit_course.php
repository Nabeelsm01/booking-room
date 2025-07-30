<?php
session_start();
include('../connect.php');

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
} elseif (isset($_SESSION['id_tutor'])) {
    $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
} elseif (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
}

// รับค่าจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$id_course = $_POST['id_course'];
$rating_course = $_POST['rating'];
$review_course = $_POST['review_course'];
// $id_user = $_POST['id'];
$id_register_course = $_POST['id_register_course'];
}


    $sql = "INSERT INTO review_course (id_register_course, id, id_course, review_course, rating_course) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisi", $id_register_course, $id, $id_course, $review_course, $rating_course);

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

$stmt->close();
$conn->close();

// กลับไปยังหน้าหลัก
header("Location: review_course.php");
?>
