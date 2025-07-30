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
$id_room = $_POST['id_room'];
$rating_room = $_POST['rating'];
$review_room = $_POST['review_room'];
// $id_user = $_POST['id'];
$id_bookingroom = $_POST['id_bookingroom'];
}

if(!empty($id)){
    $sql = "INSERT INTO review_room (id_bookingroom, id, id_room, review_room, rating_room) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisi", $id_bookingroom, $id_user, $id_room, $review_room, $rating_room);

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}else if(!empty($id_tutor)){
    $sql = "INSERT INTO review_room (id_bookingroom, id_tutor, id_room, review_room, rating_room) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisi", $id_bookingroom, $id_tutor, $id_room, $review_room, $rating_room);

    if ($stmt->execute()) {
    echo "Review submitted successfully!";
    } else {
    echo "Error: " . $stmt->error;
    }
}else{
    echo"";
}

$stmt->close();
$conn->close();

// กลับไปยังหน้าหลัก
header("Location: test_review_room.php");
?>
