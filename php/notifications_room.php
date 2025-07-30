<?php
session_start();
include('connect.php');

// ดึง id_user จาก session
$id_user = 1; // ตัวอย่าง: กำหนดให้เป็นหมายเลขผู้ใช้ที่ล็อกอินอยู่

// SQL เพื่อดึงข้อมูลการแจ้งเตือน
$sql = "SELECT *
        FROM notifications_room
        JOIN room_booking_summary ON notifications_room.id_sum_room = room_booking_summary.id_sum_room
        JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details
        WHERE details_roombooking.id = ?
        ORDER BY notifications_room.notification_time DESC"; // เรียงตามเวลาแจ้งเตือน

// เตรียม query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือน</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .notification-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .notification-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
        }

        .notification-card.unread {
            border-left: 5px solid #007bff; /* สีน้ำเงินสำหรับการแจ้งเตือนที่ยังไม่ได้อ่าน */
            background-color: #e7f3ff; /* สีฟ้าอ่อนสำหรับพื้นหลัง */
        }

        .notification-card p {
            margin: 0;
        }

        .status {
            font-weight: bold;
            color: #555;
        }

        .notification-time {
            color: #888;
            font-size: 0.9em;
        }

        .mark-read {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <h1>การแจ้งเตือนของคุณ</h1>
        
        <?php
        // ตรวจสอบว่ามีการแจ้งเตือนหรือไม่
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = ($row['status'] === 'unread') ? 'unread' : ''; // เพิ่มคลาสสำหรับการแจ้งเตือนที่ยังไม่ได้อ่าน
                echo "<div class='notification-card $statusClass'>";
                echo "<p>" . $row['message'] . "</p>";
                echo "<div>";
                    echo "<span class='status'>" . $row['status'] . "</span><br>";
                    echo "<span class='notification-time'>" . $row['notification_time'] . "</span>";
                    if ($row['status'] === 'unread') {
                        echo "<form action='notification_read.php' method='POST' style='display:inline;'>";
                        echo "<input type='hidden' name='id_noti_room' value='" . $row['id_noti_room'] . "'>";
                        echo "<button type='submit' class='mark-read'>ทำเครื่องหมายว่าอ่านแล้ว</button>";
                        echo "</form>";
                    }
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>ไม่มีการแจ้งเตือน</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
// ปิด statement และ connection
$stmt->close();
$conn->close();
?>
