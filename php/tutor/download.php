<?php
if (isset($_GET['file'])) {
    $file_path = $_GET['file'];
    
    // ตรวจสอบว่าไฟล์อยู่ในโฟลเดอร์ที่กำหนดเท่านั้น
    $allowed_directory = realpath("../../img/tutor/");
    $requested_file = realpath($file_path);
    
    if ($requested_file === false || strpos($requested_file, $allowed_directory) !== 0) {
        die("ไม่สามารถเข้าถึงไฟล์นี้ได้");
    }
    
    if (file_exists($file_path)) {
        $file_name = basename($file_path);
        
        // กำหนด headers สำหรับการดาวน์โหลด
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        // อ่านและส่งไฟล์
        readfile($file_path);
        exit;
    } else {
        echo "ไม่พบไฟล์ที่ต้องการดาวน์โหลด";
    }
} else {
    echo "ไม่ได้ระบุไฟล์ที่ต้องการดาวน์โหลด";
}
?>