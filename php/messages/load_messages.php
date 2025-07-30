<?php
session_start();
include('../connect.php');

// รับค่า ID และประเภทของผู้ใช้ที่ล็อกอิน
$user_type = '';
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $user_type = 'user';
} elseif (isset($_SESSION['id_tutor'])) {
    $user_id = $_SESSION['id_tutor'];
    $user_type = 'tutor';
} elseif (isset($_SESSION['id_provider'])) {
    $user_id = $_SESSION['id_provider'];
    $user_type = 'provider';
} else {
    die('Unauthorized access.');
}

$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : '';
$receiver_type = isset($_GET['receiver_type']) ? $_GET['receiver_type'] : '';

// สร้างเงื่อนไขการค้นหาตามประเภทผู้ใช้ที่ล็อกอินและผู้รับที่กำหนด
$conditions = [];
$params = [];
$types = '';

// สร้างเงื่อนไขสำหรับการค้นหาผู้ส่งและผู้รับ (แสดงเฉพาะข้อความของคู่สนทนานี้เท่านั้น)
// สร้างเงื่อนไขสำหรับผู้ส่งและผู้รับที่ต้องตรงกันและครอบคลุมข้ามประเภท
switch($user_type) {
    case 'user':
        $conditions[] = "((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))"; // แชทกับ user
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';

        $conditions[] = "((sender_id = ? AND receiver_tutor_id = ?) OR (sender_tutor_id = ? AND receiver_id = ?))"; // แชทกับ tutor
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';

        $conditions[] = "((sender_id = ? AND receiver_provider_id = ?) OR (sender_provider_id = ? AND receiver_id = ?))"; // แชทกับ provider
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';
        break;

    case 'tutor':
        $conditions[] = "((sender_tutor_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_tutor_id = ?))"; // แชทกับ user
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';

        $conditions[] = "((sender_tutor_id = ? AND receiver_tutor_id = ?) OR (sender_tutor_id = ? AND receiver_tutor_id = ?))"; // แชทกับ tutor
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';

        $conditions[] = "((sender_tutor_id = ? AND receiver_provider_id = ?) OR (sender_provider_id = ? AND receiver_tutor_id = ?))"; // แชทกับ provider
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';
        break;

    case 'provider':
        $conditions[] = "((sender_provider_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_provider_id = ?))"; // แชทกับ user
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';

        $conditions[] = "((sender_provider_id = ? AND receiver_tutor_id = ?) OR (sender_tutor_id = ? AND receiver_provider_id = ?))"; // แชทกับ tutor
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';

        $conditions[] = "((sender_provider_id = ? AND receiver_provider_id = ?) OR (sender_provider_id = ? AND receiver_provider_id = ?))"; // แชทกับ provider
        $params[] = $user_id;
        $params[] = $receiver_id;
        $params[] = $receiver_id;
        $params[] = $user_id;
        $types .= 'iiii';
        break;
}

$sql = "SELECT m.*,
    COALESCE(u1.name_lastname, t1.name_lastname_tutor, p1.name_provider) as sender_name,
    COALESCE(u2.name_lastname, t2.name_lastname_tutor, p2.name_provider) as receiver_name
FROM chat_messages m
LEFT JOIN user u1 ON m.sender_id = u1.id
LEFT JOIN tutor t1 ON m.sender_tutor_id = t1.id_tutor
LEFT JOIN provider p1 ON m.sender_provider_id = p1.id_provider
LEFT JOIN user u2 ON m.receiver_id = u2.id
LEFT JOIN tutor t2 ON m.receiver_tutor_id = t2.id_tutor
LEFT JOIN provider p2 ON m.receiver_provider_id = p2.id_provider
WHERE (" . implode(' OR ', $conditions) . ")
ORDER BY m.timestamp ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $time = date('H:i', strtotime($row['timestamp']));
        
        // ตรวจสอบว่าเป็นผู้ส่งหรือผู้รับ
        $is_sender = false;
        switch($user_type) {
            case 'user':
                $is_sender = ($row['sender_id'] == $user_id);
                break;
            case 'tutor':
                $is_sender = ($row['sender_tutor_id'] == $user_id);
                break;
            case 'provider':
                $is_sender = ($row['sender_provider_id'] == $user_id);
                break;
        }

        if ($is_sender) {
            echo '<div class="message sender">
                    <span class="meta">You [' . $time . ']</span><br>' . 
                    htmlspecialchars($row['message']) . 
                  '</div>';
        } else {
            echo '<div class="message receiver">
                    <span class="meta">' . htmlspecialchars($row['sender_name']) . ' [' . $time . ']</span><br>' . 
                    htmlspecialchars($row['message']) . 
                  '</div>';
        }
    }
} else {
    echo 'No messages yet.';
}

$stmt->close();
$conn->close();
?>
