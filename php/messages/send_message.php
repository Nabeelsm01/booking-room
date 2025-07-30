<?php
session_start();
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $sender_id = $_POST['sender_id'];
    $sender_type = $_POST['sender_type'];
    $receiver_id = $_POST['receiver_id'];
    $receiver_type = $_POST['receiver_type'];

    // ตั้งค่าค่าเริ่มต้นเป็น null
    $sender_id_val = null;
    $sender_tutor_id = null;
    $sender_provider_id = null;
    $receiver_id_val = null;
    $receiver_tutor_id = null;
    $receiver_provider_id = null;

    // กำหนดค่า sender ID ตามประเภท
    switch($sender_type) {
        case 'user':
            $sender_id_val = $sender_id;
            break;
        case 'tutor':
            $sender_tutor_id = $sender_id;
            break;
        case 'provider':
            $sender_provider_id = $sender_id;
            break;
    }

    // กำหนดค่า receiver ID ตามประเภท
    switch($receiver_type) {
        case 'user':
            $receiver_id_val = $receiver_id;
            break;
        case 'tutor':
            $receiver_tutor_id = $receiver_id;
            break;
        case 'provider':
            $receiver_provider_id = $receiver_id;
            break;
    }

    // เพิ่มข้อความลงในฐานข้อมูล
    $sql = "INSERT INTO chat_messages (
        sender_id, sender_tutor_id, sender_provider_id,
        receiver_id, receiver_tutor_id, receiver_provider_id,
        message
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiss", 
        $sender_id_val, $sender_tutor_id, $sender_provider_id,
        $receiver_id_val, $receiver_tutor_id, $receiver_provider_id,
        $message
    );

    if ($stmt->execute()) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>