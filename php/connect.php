
<?php
$servername = "localhost";
$username = "root";
$password = "";
$namedb = "registerdb";

// เชื่อมต่อกับ MySQL โดยยังไม่เลือก database
$conn = mysqli_connect($servername, $username, $password);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// สร้าง database ถ้ายังไม่มี
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $namedb";
if (!mysqli_query($conn, $sql_create_db)) {
    die("Error creating database: " . mysqli_error($conn));
}

// เลือกใช้ database ที่สร้าง
mysqli_select_db($conn, $namedb);

?>
