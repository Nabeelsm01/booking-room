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

// ดึงรายการแชทที่เคยคุย
$recent_chats_sql = "
    SELECT DISTINCT 
        CASE 
            WHEN (m.sender_id = ? OR m.sender_tutor_id = ? OR m.sender_provider_id = ?) THEN
                COALESCE(
                    CASE WHEN m.receiver_id IS NOT NULL THEN CONCAT(u.name_lastname, '|user|', m.receiver_id) END,
                    CASE WHEN m.receiver_tutor_id IS NOT NULL THEN CONCAT(t.name_lastname_tutor, '|tutor|', m.receiver_tutor_id) END,
                    CASE WHEN m.receiver_provider_id IS NOT NULL THEN CONCAT(p.name_provider, '|provider|', m.receiver_provider_id) END
                )
            ELSE
                COALESCE(
                    CASE WHEN m.sender_id IS NOT NULL THEN CONCAT(u.name_lastname, '|user|', m.sender_id) END,
                    CASE WHEN m.sender_tutor_id IS NOT NULL THEN CONCAT(t.name_lastname_tutor, '|tutor|', m.sender_tutor_id) END,
                    CASE WHEN m.sender_provider_id IS NOT NULL THEN CONCAT(p.name_provider, '|provider|', m.sender_provider_id) END
                )
        END as contact_info,
        MAX(m.timestamp) as last_message_time
    FROM chat_messages m
    LEFT JOIN user u ON (m.sender_id = u.id OR m.receiver_id = u.id)
    LEFT JOIN tutor t ON (m.sender_tutor_id = t.id_tutor OR m.receiver_tutor_id = t.id_tutor)
    LEFT JOIN provider p ON (m.sender_provider_id = p.id_provider OR m.receiver_provider_id = p.id_provider)
    WHERE 
        m.sender_id = ? OR m.sender_tutor_id = ? OR m.sender_provider_id = ? OR
        m.receiver_id = ? OR m.receiver_tutor_id = ? OR m.receiver_provider_id = ?
    GROUP BY contact_info
    ORDER BY last_message_time DESC";

$stmt = $conn->prepare($recent_chats_sql);
$stmt->bind_param('iiiiiiiii', 
    $user_id, $user_id, $user_id,
    $user_id, $user_id, $user_id,
    $user_id, $user_id, $user_id
);
$stmt->execute();
$recent_chats = $stmt->get_result();

// ดึงรายชื่อผู้ใช้ทั้งหมดสำหรับการค้นหา
$all_users_sql = "
    SELECT id as user_id, name_lastname as name, 'user' as type FROM user WHERE id != ?
    UNION
    SELECT id_tutor as user_id, name_lastname_tutor as name, 'tutor' as type FROM tutor WHERE id_tutor != ?
    UNION
    SELECT id_provider as user_id, name_provider as name, 'provider' as type FROM provider WHERE id_provider != ?
    ORDER BY name";

$stmt = $conn->prepare($all_users_sql);
$stmt->bind_param('iii', $user_id, $user_id, $user_id);
$stmt->execute();
$all_users = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --bg-light: #f8f9fa;
            --border-color: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .chat-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            height: calc(100vh - 40px);
        }

        .sidebar {
            width: 300px;
            background: #fff;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .search-container {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            outline: none;
            transition: all 0.3s;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }

        .contact-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .contact-item {
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .contact-item:hover {
            background-color: var(--bg-light);
        }

        .contact-item.active {
            background-color: #e3f2fd;
        }

        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 15px;
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f5f7f9;
        }

        .message {
            max-width: 70%;
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
        }

        .message.sender {
            margin-left: auto;
            background-color: var(--primary-color);
            color: white;
            border-bottom-right-radius: 5px;
        }

        .message.receiver {
            background-color: white;
            border-bottom-left-radius: 5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .message-time {
            font-size: 0.75rem;
            color: rgba(0,0,0,0.5);
            margin-top: 5px;
        }

        .chat-input-container {
            padding: 15px;
            background: #fff;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            margin-right: 10px;
            outline: none;
        }

        .send-button {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .send-button:hover {
            background-color: #0056b3;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .turn-btn {
            background-color: white; /* Green */
            color: #717FA1;
            padding: 13px 20px;
            text-align: center;
            text-decoration: none; 
            font-size: 16px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 10px;
            margin-top: -10px;
            box-shadow: 0 5px 10px 7px rgba(0, 0, 0, 0.05);
            position:fixed;
        }

        .turn-btn:hover {
            background-color:#90a7dc;
            color:#fff;
            text-decoration: none;
            transition: 0.5s;
            box-shadow: 2px 2px 5px #8892a8;
        }
    </style>
</head>
<body>
<a href="/project end/php/home.php" class="turn-btn">ย้อนกลับ</a>
<div class="chat-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="search-container">
            <input type="text" class="search-input" id="searchInput" placeholder="Search users...">
        </div>
        
        <!-- Recent Chats -->
        <div class="contact-list" id="recentChats">
            <h6 class="px-3 pt-2">Recent Chats</h6>
            <?php while($chat = $recent_chats->fetch_assoc()) {
                list($name, $type, $id) = explode('|', $chat['contact_info']);
                echo "<div class='contact-item' data-id='$id' data-type='$type'>";
                echo "<strong>" . htmlspecialchars($name) . "</strong><br>";
                echo "<small class='text-muted'>$type</small>";
                echo "</div>";
            } ?>
        </div>

        <!-- Search Results -->
        <div class="contact-list" id="searchResults" style="display: none;">
            <h6 class="px-3 pt-2">Search Results</h6>
            <?php while($user = $all_users->fetch_assoc()) { ?>
                <div class='contact-item' data-id='<?= $user['user_id'] ?>' data-type='<?= $user['type'] ?>'>
                    <strong><?= htmlspecialchars($user['name']) ?></strong><br>
                    <small class='text-muted'><?= $user['type'] ?></small>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
        <div class="chat-header">
            <h5 id="currentContact">Select a contact to start chatting</h5>
        </div>
        
        <div class="chat-messages" id="chat-box">
            <!-- Messages will be loaded here -->
        </div>

        <div class="chat-input-container">
            <input type="text" id="message" class="chat-input" placeholder="Type a message..." required>
            <input type="hidden" id="sender_id" value="<?= $user_id ?>">
            <input type="hidden" id="sender_type" value="<?= $user_type ?>">
            <input type="hidden" id="current_receiver_id" value="">
            <input type="hidden" id="current_receiver_type" value="">
            <button type="button" id="send-button" class="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    let currentReceiverId = '';
    let currentReceiverType = '';

    // Search functionality
    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        if (searchTerm) {
            $('#recentChats').hide();
            $('#searchResults').show();
            $('#searchResults .contact-item').each(function() {
                const name = $(this).find('strong').text().toLowerCase();
                $(this).toggle(name.includes(searchTerm));
            });
        } else {
            $('#recentChats').show();
            $('#searchResults').hide();
        }
    });

    // Handle contact selection
    $('.contact-item').click(function() {
        $('.contact-item').removeClass('active');
        $(this).addClass('active');
        
        currentReceiverId = $(this).data('id');
        currentReceiverType = $(this).data('type');
        const contactName = $(this).find('strong').text();
        
        $('#currentContact').text(contactName);
        $('#current_receiver_id').val(currentReceiverId);
        $('#current_receiver_type').val(currentReceiverType);
        
        loadMessages();
    });

    // Load messages function
    function loadMessages() {
        if (currentReceiverId && currentReceiverType) {
            $.get('load_messages.php', {
                receiver_id: currentReceiverId,
                receiver_type: currentReceiverType
            }, function(data) {
                $('#chat-box').html(data);
                scrollToBottom();
            });
        }
    }

    // Auto reload messages
    setInterval(function() {
        if (currentReceiverId && currentReceiverType) {
            loadMessages();
        }
    }, 3000);

    // Send message
    $('#send-button').click(function() {
        const message = $('#message').val().trim();
        if (!message || !currentReceiverId) return;

        $.ajax({
            url: 'send_message.php',
            type: 'POST',
            data: {
                message: message,
                sender_id: $('#sender_id').val(),
                sender_type: $('#sender_type').val(),
                receiver_id: currentReceiverId,
                receiver_type: currentReceiverType
            },
            success: function(response) {
                $('#message').val('');
                loadMessages();
            }
        });
    });

    // Handle enter key
    $('#message').keypress(function(e) {
        if (e.which == 13) {
            $('#send-button').click();
            return false;
        }
    });

    // Scroll to bottom function
    function scrollToBottom() {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
</script>
</body>
</html>