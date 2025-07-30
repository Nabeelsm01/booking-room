<?php
session_start();
include('../connect.php');

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f6fa;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: rgb(77,151,184);
            background: linear-gradient(158deg, rgba(77,151,184,1) 0%, rgba(29,120,135,1) 100%);
            height: 100vh;
            padding: 20px;
            color: white;
            position: fixed;
            transition: width 0.3s ease;
        }

        .sidebar.closed {
            width: 80px;
        }

        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 40px;
            text-align: center;
        }

        .sidebar.closed h2 {
            display: none;
            transition: 3s;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            transition: 3s;
        }

        .sidebar ul li {
            margin: 20px 0;
            transition: 3s;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: 1s;
    
        }

        .sidebar ul li a i {
            margin-right: 10px;
            transition: 0.3s;
        }

        /* ซ่อนข้อความเมื่อ Sidebar ปิด */
        .sidebar.closed ul li a span {
            display: none;
            transition: 0.3s;
        }

        .sidebar.closed ul li a i {
            margin-right: 0;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background: rgb(77,151,184);
            background: linear-gradient(158deg, rgba(77,151,184,0.3) 0%, rgba(29,120,135,0.3) 100%);
            border:1px solid rgb(77,151,184);
        }

        /* Toggle button */
        .toggle-btn {
            position: absolute;
            top: 20px;
            right: -20px;
            background: rgb(77,151,184);
            background: linear-gradient(158deg, rgba(77,151,184,1) 0%, rgba(29,120,135,1) 80%);
            border-radius: 50%;
            color: white;
            font-size: 20px;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .main-content.shifted {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        /* Header */
        .header {
            background-color: #fff;
            box-shadow: 0 5px 10px 7px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            color: #40739e;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Section styling */
        .section {
            margin-bottom: 30px;
        }

        .section h3 {
            font-size: 22px;
            color: #a4b1b2;
            margin-bottom: 20px;
        }

        .card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        .card p{
            color: #5c7b7e;
        }
        .card-btn-add{
            display: flex;
            justify-content: flex-end; /* จัดปุ่มให้อยู่ขวาสุด */
        }
        .btn {
            display: inline-block;
            background: rgb(77,151,184);
            background: linear-gradient(158deg, rgba(77,151,184,1) 0%, rgba(29,120,135,1) 100%);
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            margin: 10px;
   
            width: 17vh;
        }
        .btn:hover {
            background: rgb(77,151,184);
            background: linear-gradient(158deg, rgba(77,151,184,0.7) 0%, rgba(29,120,135,0.7) 100%);
        }
        h3{
            font-size:18px;
            font-family: "Prompt", sans-serif;
            font-style: normal;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius:25px;
            overflow: hidden; /* ซ่อนส่วนที่เกินออกจากขอบมน */
            box-shadow: 0 0px 10px rgba(0, 0, 0, 0.2);
            border: 10px solid #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
            border-radius:25px;
            
        }

        th, td {
            padding: 7px;
            text-align: left;
            background-color: #f1f5f7;
            border-radius:5px;
        }

        th {
            background-color: #f1f5f7;
            text-align:center;
            font-size:14px;

        }

        td img {
            max-width: 100px;
            border-radius: 10px;
        }

        .edit-button {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: darkgreen;
            color: white;
            text-decoration: none;
        }
        .delete-button {
            background-color: #ff1b1b; /* Green */
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: darkred;
            color: white;
            text-decoration: none;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 10px;
            }

            .header {
                flex-direction: column;
                text-align: center;
            }
        }
        /* สไตล์สำหรับกล่องแจ้งเตือน */
        .alert {
            position: fixed;
            top: 40px; /* จัดตำแหน่งที่กลางจอแนวตั้ง */
            left: 50%; /* จัดตำแหน่งที่กลางจอแนวนอน */
            transform: translate(-50%, -50%); /* เลื่อนกล่องกลับไปครึ่งหนึ่งของขนาดตัวเอง เพื่อให้อยู่ตรงกลางจริงๆ */
            background-color: #a9f0b9; /* สีพื้นหลังเขียวเพื่อบอกความสำเร็จ */
            border: 1px solid #4CAF50;
            color: #137117;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            z-index: 1000;
            transition: opacity 0.5s ease; /* ทำให้จางหายอย่างช้าๆ */
            opacity: 1;
        }


        .alert.hide {
            opacity: 0; /* ซ่อนกล่องแจ้งเตือน */
            transition: opacity 0.5s ease;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
    <h2>admin</h2>
        <ul>
            <li><a href="dashboard_admin.php"><i class="bi bi-house-door"></i> <span>Dashboard</span></a></li>
            <li><a href="bookingroom_admin.php"><i class="bi bi-calendar-check"></i> <span>การจองห้อง</span></a></li>
            <li><a href="register_admin.php"><i class="bi bi-calendar-check"></i> <span>การลงทะเบียน</span></a></li>
            <li><a href="payment_admin.php"><i class="bi bi-receipt"></i> <span>ประวัติการชำระเงิน</span></a></li>
            <li><a href="food_admin.php"><i class="bi bi-card-list"></i> <span>รายการอาหาร</span></a></li>
            <li><a href="promotion_admin.php"><i class="bi bi-gift"></i> <span>โปรโมชั่น</span></a></li>
            <!-- <li><a href="#"><i class="bi bi-bell"></i> <span>การแจ้งเตือน</span></a></li> -->
            <li><a href="meetingroom_admin.php"><i class="bi bi-building"></i> <span>ห้องประชุม</span></a></li>
            <li><a href="course_admin.php"><i class="bi bi-book"></i> <span>คอร์สติว</span></a></li>
            <?php if (isset($_SESSION['email'])): ?>
            <li>
                <a href="/project end/php/home.php?logout=1" style="background: rgb(255,32,32);
background: linear-gradient(158deg, rgba(255,32,32,1) 0%, rgba(172,10,10,1) 100%);"><i class="bi bi-box-arrow-right"></i><span> Logout</span></a>
            </li>
        <?php endif; ?>

        </ul>
        <div class="toggle-btn" id="toggleBtn">
            <i class="bi bi-list"></i>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="header">
            <div class="logo" style="font-size:22px;">ระบบจัดการโปรโมชั่น</div>
            <div class="notification">.</div>
        </div>

        <!-- Dashboard Content -->
        <div class="section">
            <h3>สรุปโปรโมชั่นม</h3>
            <div class="card">
                <p>แสดงจำนวนโปรโมชั่น</p>
                <div class="card-btn-add">
                    <a href="meetingroom_add.php" class="btn">เพิ่มข้อมูล</a>
                </div>
                <?php
                    // ดึงข้อมูลจากฐานข้อมูล
                    $sql = "SELECT promotion_room.*, meetingroom.name_room, meetingroom.image_room
                            FROM promotion_room 
                            JOIN meetingroom ON promotion_room.id_room = meetingroom.id_room";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // แสดงข้อมูลในรูปแบบของ HTML Table
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>รูปภาพ</th>";
                        echo "<th>ห้องประชุม</th>";
                        echo "<th>ชื่อโปรโมชั่น</th>";
                        echo "<th>ประเภท</th>"; 
                        echo "<th>มูลค่าส่วนลด</th>";
                        echo "<th>วันที่เริ่มต้น </th>";
                        echo "<th>วันที่สิ้นสุด</th>";
                        echo "<th>code</th>";
                        echo "<th>สถานะ</th>";
                        echo "<th>การจัดการ</th>";
                        echo "<th>การจัดการ</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><img src='" . $row["image_room"] . "' alt='" . $row["name_room"] . "'></td>";
                            echo "<td>" . $row["name_room"] . "</td>";
                            echo "<td>" . $row["promotion_title_room"] . "</td>";
                            echo "<td>" . $row["promotion_type_room"] . "</td>";
                            echo "<td>" . $row["discount_value_room"] . "</td>";
                            echo "<td>" . $row["start_date_room"] . "</td>";
                            echo "<td>" . $row["end_date_room"] . "</td>";
                            echo "<td>" . $row["promo_code_room"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "<td><a href='meetingroom_edit.php?id=" . $row["id_promotion_room"] . "' class='edit-button'>แก้ไข</a></td>";
                            // echo "<td><a href='meetingroom_delete.php?id=" . $row["id_room"] . "' class='delete-button'>ลบ</a></td>";
                            echo "<td><a href='#' onclick='confirmDelete(" . $row["id_promotion_room"] . ")' class='delete-button'>ลบ</a></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "ไม่มีห้องประชุมที่จะแสดง";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
            </div>
        </div>
    </div>
 
    <!-- JavaScript -->
    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('closed');
            mainContent.classList.toggle('shifted');
        });
    </script>

    <!-- Bootstrap JS (optional but included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <?php
    if (isset($_SESSION['success'])) {
        echo "
        <div class='alert'>
            " . $_SESSION['success'] . "
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('.alert').style.display = 'none';
            }, 3000); // ซ่อนกล่องแจ้งเตือนหลังจาก 3 วินาที
        </script>
        ";
        unset($_SESSION['success']); // ลบค่าออกเพื่อไม่ให้ alert ซ้ำเมื่อ refresh หน้า
    }
?>
<script>
function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบห้องประชุมนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'meetingroom_delete.php?id=' + id;
            }
        });
}
</script>
</body>
</html>
