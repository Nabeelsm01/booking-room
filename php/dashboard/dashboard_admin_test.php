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
    <title>Dashboard สรุปข้อมูล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>