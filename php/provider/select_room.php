<?php
session_start();
include('../connect.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .meeting-rooms {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            background-color: #B8C9F5; /* Background color */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .meeting-room {
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            width: 300px;
            text-align: center;
        }

        .meeting-room:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .meeting-room h2 {
            color: #333;
        }

        .meeting-room p {
            color: #666;
        }

        .meeting-room img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
    <title>Meeting Rooms</title>
</head>
<body>
    <?php
    // ดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT * FROM meetingroom";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // แสดงข้อมูลในรูปแบบของ HTML
        echo "<div class='meeting-rooms'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='meeting-room'>";
            echo "<h2>" . $row["name_room"] . "</h2>";
            echo "<p>" . $row["detail_room"] . "</p>";
            echo "<p>ความจุ: " . $row["opacity_room"] . " คน</p>";
            echo "<p>ราคา: " . $row["price_room"] . " บาท</p>";
            echo "<img src='" . $row["image_room"] . "' alt='" . $row["name_room"] . "'>";
            echo "<p>ที่อยู่: " . $row["address_room"] . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "ไม่มีห้องประชุมที่จะแสดง";
    }
    $conn->close();
    ?>
</body>
</html>
