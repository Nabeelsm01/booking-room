<?php
session_start();
include('../connect.php');

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
    } elseif (isset($_SESSION['id_tutor'])) {
        $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
    } elseif (isset($_SESSION['id_provider'])) {
        $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
        header("Location: /project end/php/home.php");
        exit();
    }

    if (isset($_SESSION['id'])) { 
        // สำหรับผู้ใช้ทั่วไป
        $id = $_SESSION['id']; 
        $sql = "SELECT *
                FROM room_booking_summary 
                JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom
                JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details
                JOIN payment_room ON room_booking_summary.id_paymentroom = payment_room.id_paymentroom
                JOIN meetingroom ON details_roombooking.id_room = meetingroom.id_room 
                WHERE details_roombooking.id = ?
                ORDER BY room_booking_summary.id_sum_room DESC"; // เรียงตาม id_sum_room ใหม่ไปเก่า
        
        // เตรียม query และ bind ตัวแปร
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

    } elseif (isset($_SESSION['id_tutor'])) {
        // สำหรับติวเตอร์
        $id_tutor = $_SESSION['id_tutor']; 
        $sql = "SELECT *
                FROM room_booking_summary 
                JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom
                JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details
                JOIN payment_room ON room_booking_summary.id_paymentroom = payment_room.id_paymentroom
                JOIN meetingroom ON details_roombooking.id_room = meetingroom.id_room 
                WHERE details_roombooking.id_tutor = ?
                ORDER BY room_booking_summary.id_sum_room DESC"; // เรียงตาม id_sum_room ใหม่ไปเก่า
        
        // เตรียม query และ bind ตัวแปร
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_tutor);

    } elseif (isset($_SESSION['id_provider'])) {
        // สำหรับผู้ให้บริการ
        $id_provider = $_SESSION['id_provider'];
        $sql = "SELECT *
                FROM room_booking_summary 
                JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom
                JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details
                JOIN payment_room ON room_booking_summary.id_paymentroom = payment_room.id_paymentroom
                JOIN meetingroom ON details_roombooking.id_room = meetingroom.id_room 
                WHERE details_roombooking.id_provider = ? 
                ORDER BY room_booking_summary.id_sum_room DESC"; // เรียงตาม id_sum_room ใหม่ไปเก่า
        
        // เตรียม query และ bind ตัวแปร
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_provider);
    } else {
        // ถ้าไม่มี session ใดๆ
        echo "กรุณาเข้าสู่ระบบก่อน";
        exit;
    }

    // หลังจากเตรียม query แล้ว
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการจองของคุณ</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@400;700&display=swap');

        body {
            background: linear-gradient(158deg, rgba(184,201,245,0.7) 0%, rgba(181,232,248,0.7) 100%);
            margin: 0;
            font-family: 'Prompt', sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 35px;
            color: #31708f;
        
        }
        h3 {
            font-size: 1.5rem;
            color: #31708f;
            margin-bottom:10px;
        }
        p{
            margin-top:-5px;
        }
        .container {
            max-width: 80%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .notification {
            background-color: #e7f3fe;
            color: #31708f;
            border-left: 6px solid #2196F3;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .booking-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px;
            border-bottom: 1px solid #ddd;
        }

        .booking-item:last-child {
            border-bottom: none;
        }

        .booking-image {
            width: 250px;
            height: auto;
            border-radius: 10px;
            margin-right:10px;
        }

        .booking-details {
            flex: 1;
            padding: 0 20px;

        }

        .booking-status {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .status-pending {
            color: #ff9800;
        }

        .status-confirmed {
            color: #4caf50;
        }

        .status-cancelled {
            color: #f44336;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .button {
            background-color: #fff;
            color: #31708f;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none; 
            font-size: 16px;
            border-radius: 15px;
            border: 1px solid #31708f;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: #90a7dc;
            border: 1px solid  #90a7dc;
            color: #fff;
            transition: 0.5s;
        }
        .button-review{
            background-color: #4caf50; 
            color: #fff;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none; 
            font-size: 16px;
            border-radius: 15px;
            border: 1px solid  #188a55;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .button-review:hover {
            background-color: #188a55;
            color: #fff;
            transition: 0.5s;
        }
        .button-cancel{
            background-color: #fff;
            color: #31708f;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none; 
            font-size: 16px;
            border-radius: 15px;
            border: 1px solid #31708f;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .button-cancel:hover {
            background-color: #f44336; 
            border: 1px solid #f44336; 
            color: #fff;
            transition: 0.5s;
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

<a href="../home.php" class="turn-btn">ย้อนกลับ</a>
<div class="container">
    <h1>ข้อมูลการจองของคุณ</h1>

    <!-- แสดงการแจ้งเตือน -->
    <div class="notification">
        <strong>แจ้งเตือน:</strong> การจองของคุณได้รับการยืนยันแล้ว! กรุณาตรวจสอบรายละเอียดการจองด้านล่าง
    </div>

    <?php
    // ตรวจสอบว่ามีการจองหรือไม่
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='booking-item'>";
            echo "<img class='booking-image' src='" . htmlspecialchars($row["image_room"]) . "' alt='" . htmlspecialchars($row["name_room"]) . "'>";
            echo "<div class='booking-details'>";
            echo "<h3>" . htmlspecialchars($row['name_room']) . "</h3>";
            echo "<p>ผู้จอง: " . htmlspecialchars($row['name']) . "</p>";
            echo "<p>วันที่เริ่มจอง: " . htmlspecialchars($row['date_start']) . "</p>";
            echo "<p>วันที่สิ้นสุดการจอง: " . htmlspecialchars($row['date_end']) . "</p>";
            echo "<p>ระยะเวลา: " . htmlspecialchars($row['day_book']) . " วัน</p>";
            echo "<p>ราคาเต็ม: " . htmlspecialchars(number_format($row['total_pricess'], 2)) . " ฿</p>";
            echo "<p>การชำระเงิน: " . htmlspecialchars($row['paymentOption']) . "</p>";
            echo "<strong><p>จ่ายแล้ว: " . htmlspecialchars(number_format($row['totalAmount'], 2)) . " ฿</p></strong>";            
            echo "</div>";
            echo "<div class='buttons'>";
            echo "<div class='booking-status ";
            if ($row['status_summary'] == 'cancelled') {
                echo "status-cancelled'>"; // ถ้ามีการยกเลิก ให้ใช้สถานะยกเลิก
            } else {
                // ถ้าไม่มีการยกเลิก จะตรวจสอบสถานะการจ่ายเงิน
                echo ($row['status'] == 'รอดำเนินการ' ? 'status-pending' : 
                      ($row['status'] == 'ชำระเงินครบแล้ว' ? 'status-confirmed' : 'status-unknown')) . "'>";
            }
            echo htmlspecialchars($row['status_summary'] == 'cancelled' ? 'ยกเลิกการจอง' : htmlspecialchars($row['status']));
            echo "</div>";
            // echo "<a href='cancel_room_booking_summary.php?id_sum_room=" . $row['id_sum_room'] . "' class='button-cancel'>ยกเลิกการจอง</a>";
            ?>
            <a href="#" class='button-cancel' onclick="confirmCancellation('<?php echo $row['id_sum_room']; ?>')">ยกเลิกการจอง</a>
            <?php
            echo "<a href='pay_remaining.php?id_booking=" . $row['id_bookingroom'] . "' class='button'>จ่ายเงินส่วนที่เหลือ</a>";
            // echo "<a href='review_booking.php?id_booking=" . $row['id_bookingroom'] . "' class='button-review'>รีวิวการจอง</a>";
            ?>
            <?php
            if($row['status'] == 'ชำระเงินครบแล้ว') {
            ?>
                <form action="test_review_room.php" method="post">
                    <input type="hidden" name="id_room" value="<?php echo ($row['id_room']); ?>">   
                    <input type="hidden" name="id_bookingroom" value="<?php echo ($row['id_bookingroom']); ?>">   
                    <input type="submit" class="button-review" value="ส่งรีวิว">
                </form>     
            <?php
            } else {
            ?>
                <form action="#" method="post">
                    <input type="hidden" name="id_room" value="<?php echo ($row['id_room']); ?>">   
                    <input type="hidden" name="id_bookingroom" value="<?php echo ($row['id_bookingroom']); ?>">   
                    <input type="submit" class="button-review" value="ส่งรีวิว" disabled>
                </form>  
            <?php
            }
            ?>
            <?php
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>ไม่มีการจองที่จะแสดง</p>";
    }
    ?>
</div>
<script>
    function confirmCancellation(bookingId) {
        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการจองนี้?")) {
            // ถ้าผู้ใช้คลิก "ตกลง" จะส่งไปยังลิงก์ยกเลิก
            window.location.href = 'cancel_room_booking_summary.php?id_sum_room=' + bookingId;
        }
    }
</script>

</body>
</html>

<?php
// ปิด statement และ connection
$stmt->close();
$conn->close();
?>
