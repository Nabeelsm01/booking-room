<?php
session_start();
include('connect.php');

// รับ ID ผู้ใช้ที่ล็อกอิน
$user_id = $_SESSION['id'] ?? $_SESSION['id_tutor'] ?? $_SESSION['id_provider'];
$search = '%' . $_GET['search'] . '%';

// ค้นหาผู้ใช้ทั้งหมด
$sql = "SELECT 
            id as user_id, 
            name_lastname as name, 
            'user' as type 
        FROM user 
        WHERE id != ? AND name_lastname LIKE ?
        UNION
        SELECT 
            id_tutor as user_id, 
            name_lastname_tutor as name, 
            'tutor' as type 
        FROM tutor 
        WHERE id_tutor != ? AND name_lastname_tutor LIKE ?
        UNION
        SELECT 
            id_provider as user_id, 
            name_provider as name, 
            'provider' as type 
        FROM provider 
        WHERE id_provider != ? AND name_provider LIKE ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isisis", $user_id, $search, $user_id, $search, $user_id, $search);
$stmt->execute();
$result = $stmt->get_result();

// ส่งผลลัพธ์กลับเป็น HTML
while($row = $result->fetch_assoc()) {
    echo '<div class="chat-item" data-id="'.$row['user_id'].'" data-name="'.htmlspecialchars($row['name']).'">';
    echo '  <div class="avatar">';
    echo '    <i class="fas fa-user"></i>';
    echo '  </div>';
    echo '  <div class="chat-info">';
    echo '    <div class="chat-name">'.htmlspecialchars($row['name']).'</div>';
    echo '    <div class="chat-type">'.$row['type'].'</div>';
    echo '  </div>';
    echo '</div>';
}
?>