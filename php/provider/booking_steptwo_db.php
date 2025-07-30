<?php
session_start();
include('../connect.php');

// หากผู้ใช้เป็นติวเตอร์ จะสามารถเข้าถึงเนื้อหาด้านล่างได้

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

?>
<?php
// booking_page.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['number'] = $_POST['number'];
    $_SESSION['date_start'] = $_POST['date_start'];
    $_SESSION['date_end'] = $_POST['date_end'];
    $_SESSION['day_book'] = $_POST['day_book'];
}   
    // รับค่าจากฟอร์มด้วย POST
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $number = isset($_SESSION['number']) ? $_SESSION['number'] : '';
    $date_start = isset($_SESSION['date_start']) ? $_SESSION['date_start'] : '';
    $date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : '';
    $day_book = isset($_SESSION['day_book']) ? $_SESSION['day_book'] : '';

?>
<?php

        $sql = "INSERT INTO roombooking (name, email, phone_number, date_start, date_end, day_book) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssissi", $name, $email, $number, $date_start, $date_end, $day_book);

        if ($stmt->execute()) {
            // echo "Data inserted successfully.";
            header('location:booking_steptwo.php');
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
?>