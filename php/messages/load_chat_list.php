<?php
session_start();
include('connect.php');

$user_id = $_SESSION['id'] ?? $_SESSION['id_tutor'] ?? $_SESSION['id_provider'];

// ดึงรายการแชทที่เคยคุย
$sql = "SELECT DISTINCT 
            m.sender_id, 
            m.receiver_id,
            CASE 
                WHEN u.id IS NOT NULL THEN u.name_lastname
                WHEN t.id_tutor IS NOT NULL THEN t.name_lastname_tutor
                WHEN p.id_provider IS NOT NULL THEN p.name_provider
            END as name,
            m.message as last_message,
            m.timestamp as last_time
        FROM chat_messages m
        LEFT JOIN user u ON (u.id = IF(m.sender_id = ?, m.receiver_id, m.sender_id))
        LEFT JOIN tutor t ON (t.id_tutor = IF(m.sender_id = ?, m.receiver_id, m.sender_id))
        LEFT JOIN provider p ON (p.id_provider = IF(m.sender_id = ?, m.receiver_id, m.sender_id))
        WHERE m.sender_id = ? OR m.receiver_id = ?
        ORDER BY m.timestamp DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $user_id, $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $other_id = ($row['sender_id'] == $user_id) ? $row['receiver_id'] : $row['sender_id'];
    
    echo '<div class="chat-item" data-id="'.$other_id.'" data-name="'.htmlspecialchars($row['name']).'">';
    echo '  <div class="avatar">';
    echo '    <i class="fas fa-user"></i>';
    echo '  </div>';
    echo '  <div class="chat-info">';
    echo '    <div class="chat-name">'.htmlspecialchars($row['name']).'</div>';
    echo '    <div class="last-message">'.htmlspecialchars(substr($row['last_message'], 0, 30)).'...</div>';
    echo '    <div class="time">'.date('H:i', strtotime($row['last_time'])).'</div>';
    echo '  </div>';
    echo '</div>';
}
?>