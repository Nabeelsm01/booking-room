<?php
session_start();
include('../connect.php');

// --------- ส่วนที่ 1: การจองห้องประชุม ---------
// 1. สถิติภาพรวมการจองห้อง
$roomStats = $conn->query("
    SELECT 
        COUNT(*) as total_bookings,
        SUM(payment_room.totalAmount) as total_revenue,
        COUNT(DISTINCT roombooking.id_bookingroom) as unique_customers
    FROM room_booking_summary 
    JOIN payment_room ON room_booking_summary.id_paymentroom = payment_room.id_paymentroom
    JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom
    WHERE room_booking_summary.status_summary = 'active'
");
$roomStatsData = $roomStats->fetch_assoc();

// 2. การจองรายเดือน
$monthlyBookings = $conn->query("
    SELECT 
        DATE_FORMAT(roombooking.date_start, '%M %Y') as booking_month,
        COUNT(*) as booking_count,
        SUM(payment_room.totalAmount) as monthly_revenue
    FROM room_booking_summary
    JOIN roombooking ON room_booking_summary.id_bookingroom = roombooking.id_bookingroom
    JOIN payment_room ON room_booking_summary.id_paymentroom = payment_room.id_paymentroom
    WHERE room_booking_summary.status_summary = 'active'
    GROUP BY YEAR(roombooking.date_start), MONTH(roombooking.date_start)
    ORDER BY roombooking.date_start DESC
    LIMIT 6
");

// 3. สถิติการใช้ห้องแต่ละประเภท
$roomUsage = $conn->query("
    SELECT 
        meetingroom.name_room,
        COUNT(*) as usage_count
    FROM room_booking_summary
    JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details
    JOIN meetingroom ON details_roombooking.id_room = meetingroom.id_room
    WHERE room_booking_summary.status_summary = 'active'
    GROUP BY meetingroom.id_room
");

// --------- ส่วนที่ 2: การลงทะเบียนคอร์สติว ---------
// 1. สถิติภาพรวมคอร์สติว
$courseStats = $conn->query("
    SELECT 
        COUNT(*) as total_registrations,
        COUNT(DISTINCT register_course.id) as unique_students,
        SUM(course.price_course) as total_course_revenue
    FROM register_course
    JOIN course ON register_course.id_course = course.id_course
    WHERE register_course.status_register = 'ชำระแล้ว'
");
$courseStatsData = $courseStats->fetch_assoc();

// 2. การลงทะเบียนคอร์สรายเดือน
$monthlyCourses = $conn->query("
    SELECT 
        DATE_FORMAT(register_course.reg_date, '%M %Y') as registration_month,
        COUNT(*) as registration_count,
        SUM(course.price_course) as monthly_revenue
    FROM register_course
    JOIN course ON register_course.id_course = course.id_course
    WHERE register_course.status_register = 'ชำระแล้ว'
    GROUP BY YEAR(register_course.reg_date), MONTH(register_course.reg_date)
    ORDER BY register_course.reg_date DESC
    LIMIT 6
");

// 3. สถิติคอร์สยอดนิยม
$popularCourses = $conn->query("
    SELECT 
        course.name_course,
        COUNT(*) as enrollment_count,
        tutor.name_lastname_tutor as tutor_name
    FROM register_course
    JOIN course ON register_course.id_course = course.id_course
    JOIN tutor ON course.id_tutor = tutor.id_tutor
    WHERE register_course.status_register = 'ชำระแล้ว'
    GROUP BY course.id_course
    ORDER BY enrollment_count DESC
    LIMIT 5
");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 2em;
            color: #4a90e2;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .trend-up {
            color: #27ae60;
        }
        
        .trend-down {
            color: #c0392b;
        }
        
        .popular-course-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .popular-course-item:last-child {
            border-bottom: none;
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
            <div class="logo" style="font-size:22px;">ระบบประมวลผลโดยรวม</div>
            <div class="notification">.</div>
        </div>

        <!-- Dashboard Content -->
        <div class="section">
            <h3>สรุปภาพโดยรวม</h3>
            <div class="card">
                <!-- <p>แสดงจำนวนห้องประชุม</p> -->
                <div class="card-btn-add">
                    <!-- <a href="meetingroom_add.php" class="btn">เพิ่มข้อมูล</a> -->
                </div>
                <body class="bg-light">
    <div class="container-fluid py-4">
        <!-- ส่วนที่ 1: การจองห้องประชุม -->
        <h2 class="section-title">
            <i class="fas fa-building me-2"></i>สรุปการจองห้องประชุม
        </h2>
        
        <div class="row">
            <!-- สถิติภาพรวมห้องประชุม -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-value">
                        <?php echo number_format($roomStatsData['total_bookings']); ?>
                    </div>
                    <div class="stat-label">การจองทั้งหมด</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-value">
                        ฿<?php echo number_format($roomStatsData['total_revenue']); ?>
                    </div>
                    <div class="stat-label">รายได้รวม</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">
                        <?php echo number_format($roomStatsData['unique_customers']); ?>
                    </div>
                    <div class="stat-label">ลูกค้าที่ใช้บริการ</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- กราฟการจองรายเดือน -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h4>การจองรายเดือน</h4>
                    <canvas id="roomBookingChart"></canvas>
                </div>
            </div>
            
            <!-- กราฟสัดส่วนการใช้ห้อง -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h4>สัดส่วนการใช้ห้องประชุม</h4>
                    <canvas id="roomUsageChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ส่วนที่ 2: การลงทะเบียนคอร์สติว -->
        <h2 class="section-title mt-5">
            <i class="fas fa-graduation-cap me-2"></i>สรุปการลงทะเบียนคอร์สติว
        </h2>
        
        <div class="row">
            <!-- สถิติภาพรวมคอร์สติว -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="stat-value">
                        <?php echo number_format($courseStatsData['total_registrations']); ?>
                    </div>
                    <div class="stat-label">การลงทะเบียนทั้งหมด</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-value">
                        ฿<?php echo number_format($courseStatsData['total_course_revenue']); ?>
                    </div>
                    <div class="stat-label">รายได้รวมจากคอร์สติว</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-value">
                        <?php echo number_format($courseStatsData['unique_students']); ?>
                    </div>
                    <div class="stat-label">จำนวนนักเรียน</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- กราฟการลงทะเบียนรายเดือน -->
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>การลงทะเบียนคอร์สรายเดือน</h4>
                    <canvas id="courseRegistrationChart"></canvas>
                </div>
            </div>
            
            <!-- คอร์สยอดนิยม -->
            <div class="col-md-4">
                <div class="chart-container">
                    <h4>คอร์สยอดนิยม</h4>
                    <div class="popular-courses">
                        <?php while($course = $popularCourses->fetch_assoc()): ?>
                        <div class="popular-course-item">
                            <h6><?php echo htmlspecialchars($course['name_course']); ?></h6>
                            <small class="text-muted">
                                ผู้สอน: <?php echo htmlspecialchars($course['tutor_name']); ?><br>
                                จำนวนผู้ลงทะเบียน: <?php echo number_format($course['enrollment_count']); ?> คน
                            </small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<script>
        // ข้อมูลสำหรับกราฟการจองห้องรายเดือน
        const roomBookingData = {
            labels: <?php 
                $labels = [];
                $bookingCounts = [];
                $monthlyRevenues = [];
                mysqli_data_seek($monthlyBookings, 0);
                while($row = $monthlyBookings->fetch_assoc()) {
                    $labels[] = $row['booking_month'];
                    $bookingCounts[] = $row['booking_count'];
                    $monthlyRevenues[] = $row['monthly_revenue'];
                }
                echo json_encode($labels);
            ?>,
            datasets: [{
                label: 'จำนวนการจอง',
                data: <?php echo json_encode($bookingCounts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        // ข้อมูลสำหรับกราฟสัดส่วนการใช้ห้อง
        const roomUsageData = {
            labels: <?php 
                $roomLabels = [];
                $usageCounts = [];
                mysqli_data_seek($roomUsage, 0);
                while($row = $roomUsage->fetch_assoc()) {
                    $roomLabels[] = $row['name_room'];
                    $usageCounts[] = $row['usage_count'];
                }
                echo json_encode($roomLabels);
            ?>,
            datasets: [{
                data: <?php echo json_encode($usageCounts); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ]
            }]
        };

        // ข้อมูลสำหรับกราฟการลงทะเบียนคอร์สรายเดือน
        const courseRegistrationData = {
            labels: <?php 
                $courseLabels = [];
                $registrationCounts = [];
                mysqli_data_seek($monthlyCourses, 0);
                while($row = $monthlyCourses->fetch_assoc()) {
                    $courseLabels[] = $row['registration_month'];
                    $registrationCounts[] = $row['registration_count'];
                }
                echo json_encode($courseLabels);
            ?>,
            datasets: [{
                label: 'จำนวนการลงทะเบียน',
                data: <?php echo json_encode($registrationCounts); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // สร้างกราฟการจองห้องรายเดือน
        new Chart(document.getElementById('roomBookingChart'), {
            type: 'bar',
            data: roomBookingData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'จำนวนการจอง'
                        }
                    }
                }
            }
        });

        // สร้างกราฟสัดส่วนการใช้ห้อง
        new Chart(document.getElementById('roomUsageChart'), {
            type: 'pie',
            data: roomUsageData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // สร้างกราฟการลงทะเบียนคอร์สรายเดือน
        new Chart(document.getElementById('courseRegistrationChart'), {
            type: 'line',
            data: courseRegistrationData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'จำนวนการลงทะเบียน'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
