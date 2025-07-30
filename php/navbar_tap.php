<?php
// เพิ่มบรรทัดนี้ที่ส่วนบนของไฟล์
$base_url = '/project end/php/';
if(isset($_SESSION['id']) || isset($_SESSION['id_tutor']) || isset($_SESSION['id_provider'])) {
    include('connect.php');
    
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
        $where_clause_room = "details_roombooking.id = ?";
        $where_clause_course = "register_course.id = ?";
    } elseif (isset($_SESSION['id_tutor'])) {
        $id = $_SESSION['id_tutor']; // สำหรับติวเตอร์
        $where_clause_room = "details_roombooking.id_tutor = ?";
        $where_clause_course = "register_course.id_tutor = ?";
    } elseif (isset($_SESSION['id_provider'])) {
        $id = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
        $where_clause_room = "details_roombooking.id_provider = ?";
        $where_clause_course = "register_course.id_provider = ?";
    }

    // SQL เพื่อดึงข้อมูลการแจ้งเตือนจากทั้งสองตาราง (notifications_room และ notifications_course)
    $sql = "
        (SELECT notifications_room.id_noti_room AS id_noti, notifications_room.message AS message, notifications_room.notification_time AS notification_time, 'room' AS type, notifications_room.status AS status
         FROM notifications_room
         JOIN room_booking_summary ON notifications_room.id_sum_room = room_booking_summary.id_sum_room
         JOIN details_roombooking ON room_booking_summary.id_details = details_roombooking.id_details
         WHERE " . $where_clause_room . ")
        UNION
        (SELECT notifications_course.id_noti_course AS id_noti, notifications_course.message_c AS message, notifications_course.time_noti_course AS notification_time, 'course' AS type, notifications_course.status_noti_course AS status
         FROM notifications_course
         JOIN register_course ON notifications_course.id_register_course = register_course.id_register_course
         WHERE " . $where_clause_course . ")
        ORDER BY notification_time DESC";

    // เตรียมและ execute query
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $id); // bind ค่าสำหรับทั้ง room และ course
        $stmt->execute();
        $result = $stmt->get_result();
    } catch (Exception $e) {
        // จัดการข้อผิดพลาด (ถ้าต้องการ)
        error_log("Query error: " . $e->getMessage());
    }
    
} else {
    // กรณีไม่มี session
    $login_message = "กรุณาเข้าสู่ระบบก่อน";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <link href="/project end/css/navbar_tag.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Navbar</title>
    <style>
        .custom-dropdown-menu {
            max-height: 1200px; /* ความสูงสูงสุดของ dropdown */
            height:80% auto;
            width: 220px;
            overflow-y: auto; /* เพิ่ม scroll ถ้ามีเนื้อหามากเกินไป */
            border:none;
            margin: -50px 0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* เงา */
            padding: 10px;


        }
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
       /* สำหรับการแสดง dropdown */
.dropdown-menu.custom-dropdown-menu {
    width: 300px; /* กำหนดความกว้างของ dropdown */
    max-height: 400px; /* กำหนดความสูงสูงสุดเพื่อทำให้มี scroll เมื่อการแจ้งเตือนเยอะ */
    overflow-y: auto; /* ทำให้เลื่อนแนวตั้งได้ */
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

/* สำหรับแต่ละการ์ดการแจ้งเตือน */
.notification-card {
    padding: 15px;
    margin-bottom: 10px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.3s ease;
    border-radius: 5px;
    overflow:hidden;
}

/* การแจ้งเตือนที่ยังไม่ได้อ่าน */
.notification-card.unread {
    background-color: #e9f4ff;
    border-left: 5px solid #007bff;
}

/* การแจ้งเตือนที่อ่านแล้ว */
.notification-card.read {
    background-color: #fff;
    opacity: 0.8; /* แสดงความแตกต่างของการแจ้งเตือนที่อ่านแล้ว */
}

/* ข้อความการแจ้งเตือน */
.notification-message {
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

/* รายละเอียดของการแจ้งเตือน */
.notification-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #888;
    margin-top: 5px;
}

/* สถานะของการแจ้งเตือน */
.status-label {
    color: #28a745;
    font-weight: bold;
    width: 100px;
}

/* เวลาการแจ้งเตือน */
.notification-time {
    color: #bbb;
    font-size: 12px;
}

/* ปุ่มทำเครื่องหมายว่าอ่านแล้ว */
.mark-read-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    font-size: 12px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.mark-read-btn:hover {
    background-color: #0056b3;
}

/* ข้อความเมื่อไม่มีการแจ้งเตือน */
.no-notifications {
    text-align: center;
    color: #999;
    font-size: 14px;
    padding: 10px;
}

/* ปรับการแสดงผลบนหน้าจอขนาดเล็ก */
@media (max-width: 576px) {
    .dropdown-menu.custom-dropdown-menu {
        width: 100%;
        max-height: 300px; /* ลดขนาดเมื่ออยู่บนมือถือ */
    }

    .notification-card {
        padding: 10px;
        font-size: 12px;
    }

    .mark-read-btn {
        padding: 3px 8px;
        font-size: 10px;
    }
}


    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <a href="/project end/php/home.php" class="navbar-brand">
                <img src="/project end/img/meetingroom.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="/project end/php/home.php" class="nav-link">หน้าหลัก</a></li>
                        <li class="nav-item"><a href="/project end/php/messages/chat_messages.php" class="nav-link">แชทสนทนา</a></li>
                        <li class="nav-item position-relative">
                            <a href="/project end/php/provider/room_booking_summary.php" class="nav-link">
                                การจอง
                                <!-- <span class="position-absolute top-50 start-95 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.2em 0.4em; margin-top:-12px;">
                                    0
                                    <span class="visually-hidden">unread messages</span>
                                </span> -->
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a href="/project end/php/tutor/register_course_summary.php" class="nav-link">
                                ลงทะเบียนคอร์ส
                                <!-- <span class="position-absolute top-50 start-95 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.2em 0.4em; margin-top:-12px;">
                                    0
                                    <span class="visually-hidden">unread messages</span>
                                </span> -->
                            </a>
                        </li>

                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" style="border:none; font-size:19px; font-family:sans-serif; color:#7888b2; margin-right: 20px;padding: 10px 10px;  border-radius:10px; box-shadow:none; background-color:none;">
                               เมนู
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start custom-dropdown">
                                <li><a class="nav-link" href="#">หน้าหลัก</a></li>
                                <li><a class="nav-link" href="#">ติดต่อ</a></li>
                                <li><a class="nav-link" href="/project end/php/meeting.php">เกี่ยวกับ</a></li>
                                <li class="nav-item dropdown-container">
                                    <div class="dropdown-menu-wrapper">
                                        <button type="button" class="btn dropdown-toggle new-dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow:none; background-color:none;">
                                            เพิ่มเติม
                                        </button>
                                        <ul class="dropdown-menu new-dropdown-menu">
                                            <li><a class="nav-link" href="/project end/php/navbar.php">navbar</a></li>
                                            <li><a class="nav-link" href="/project end/php/navbar_tap.php">navbar tap</a></li>
                                            <li><a class="nav-link" href="/project end/php/login.php">login</a></li>
                                            <li><a class="nav-link" href="/project end/php/provider/confirm_room.php">con</a></li>
                                            <li><a class="nav-link" href="footer.php">footer</a></li>
                                        </ul>
                                    </div>
                                </li>
                        </ul>
                            </div>
                
                        <!-- <li class="nav-item position-relative">
                            <a href="#" class="nav-link">
                            การแจ้งเตือน
                                <span class="position-absolute top-50 start-95 translate-middle custom-notification-badge badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.2em 0.4em; margin-top:-12px;">
                                    1
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                        </li> -->
                        <li class="nav-item position-relative">
                        <a class="nav-link dropdown-toggle" href="/project end/php/provider/notifications_room.php" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            การแจ้งเตือน
                            <!-- <span class="position-absolute top-50 start-95 translate-middle custom-notification-badge badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.2em 0.4em; margin-top:-12px;">
                                1
                                <span class="visually-hidden">unread messages</span>
                            </span> -->
                        </a>
                        <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="notificationDropdown" style="margin-top:13px; margin-left:-40px;">
                            <!-- <li class="notification-item"><a href="/project end/php/notifications_room.php">การแจ้งเตือน 1: คุณมีการจองใหม่ </a></li>
                            <li class="notification-item">การแจ้งเตือน 2: ชำระเงินเรียบร้อย</li>
                            <li class="notification-item">การแจ้งเตือน 3: การจองของคุณกำลังดำเนินการ</li> -->

                            <!-- <a href="provider/room_booking_summary.php" style="text-decoration:none;"> -->
                            <?php                      
                           if (isset($result) && $result instanceof mysqli_result) {
                               if ($result->num_rows > 0) {
                                   while ($row = $result->fetch_assoc()) {
                                       // ตรวจสอบสถานะของการแจ้งเตือนจากทั้งสองตาราง
                                       $status = isset($row['status']) ? $row['status'] : (isset($row['status_noti_course']) ? $row['status_noti_course'] : null);
                                       
                                       // กำหนดคลาสตามสถานะ
                                       $statusClass = ($status === 'unread') ? 'unread' : 'read';
                                       ?>
                                       <!-- <div style="cursor: pointer;" class='notification-card <?php echo $statusClass; ?>' id='notification-<?php echo htmlspecialchars($row['id_noti']); ?>' data-id='<?php echo htmlspecialchars($row['id_noti']); ?>' data-type='<?php echo htmlspecialchars($row['type']); ?>'> -->
                                       <div style="cursor: pointer;" class='notification-card <?php echo $statusClass; ?>' 
                                            id='notification-<?php echo htmlspecialchars($row['id_noti']); ?>' 
                                            data-id='<?php echo htmlspecialchars($row['id_noti']); ?>' 
                                            data-type='<?php echo htmlspecialchars($row['type']); ?>'
                                            data-url='<?php echo $base_url . (($row['type'] === 'room') ? 'provider/room_booking_summary.php' : 'tutor/register_course_summary.php'); ?>'>
                                           <p class='notification-message'><?php echo htmlspecialchars($row['message']); ?></p>
                                           <div  class='notification-details'>
                                               <span class='status-label' >
                                                   <?php echo ($status === 'unread') ? 'ยังไม่ได้อ่าน' : 'อ่านแล้ว'; ?>
                                               </span>
                                               <span class='notification-time'><?php echo htmlspecialchars($row['notification_time']); ?></span>
                                               <span class='notification-type'>
                                                   <?php echo ($row['type'] === 'room') ? 'การจองห้องประชุม' : 'การลงทะเบียนคอร์ส'; ?>
                                               </span>
                                           </div>
                                       </div>
                                       <?php
                                   }
                               } else {
                                   echo "<p class='no-notifications'>ไม่มีการแจ้งเตือน</p>";
                               }
                           } else {
                               echo "<p class='connection-error'>ไม่สามารถโหลดการแจ้งเตือนได้ในขณะนี้</p>";
                           }
                            ?>
                            <!-- </a> -->
                            <!-- เพิ่มการแจ้งเตือนเพิ่มเติมตามต้องการ -->
                        </ul>
                    </li>

                        <div class="dropdown text-end">
                            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="margin-left:10px; margin-top:5px;">
                                <img src="/project end/img/user.png" alt="mdo" width="40" height="40" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu text-small custom-dropdown">
                                <img src="/project end/img/user.png" alt="mdo" width="40" height="40" class="rounded-circle" style="margin-left:85px; margin-bottom:10px;">
                                        <div class="name">
                                            <!-- logged in user information -->
                                            <?php if (isset($_SESSION['email'])): ?>
                                                <p class="name"><strong><?php echo $_SESSION['email']; ?></strong></p>
                                                <!-- <p><a href="home.php?logout='1'">logout</a></p> --> 
                                            <?php endif ?>  
                                            </div>
                                            <div class="name">
                                                        <?php if (isset($_SESSION['name_lastname']) || isset($_SESSION['name_lastname_tutor']) || isset($_SESSION['name_provider'])): ?>
                                                            <p class="name">ชื่อผู้ใช้ : 
                                                                <?php
                                                                if (isset($_SESSION['name_lastname'])) {
                                                                    echo $_SESSION['name_lastname'];
                                                                } elseif (isset($_SESSION['name_lastname_tutor'])) {
                                                                    echo $_SESSION['name_lastname_tutor'];
                                                                } elseif (isset($_SESSION['name_provider'])) {
                                                                    echo $_SESSION['name_provider'];
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php endif ?>
                                            </div> 
                                            <div class="name">
                                                        <?php if (isset($_SESSION['id_provider']) || isset($_SESSION['id']) || isset($_SESSION['id_tutor'])): ?>
                                                            <p class="name">รหัสผู้ใช้ : 
                                                                <?php
                                                                if (isset($_SESSION['id_provider'])) {
                                                                    echo $_SESSION['id_provider'];
                                                                } elseif (isset($_SESSION['id'])) {
                                                                    echo $_SESSION['id'];
                                                                } elseif (isset($_SESSION['id_tutor'])) {
                                                                    echo $_SESSION['id_tutor'];
                                                                }
                                                                ?>
                                                            </p>
                                                            <?php endif ?>
                                            </div>  
                                            <div class="name" >
                                                        <?php if (isset($_SESSION['user_type'])): ?>
                                                            <p class="name">สถานะผู้ใช้ : <?php echo $_SESSION['user_type']; ?></p>
                                                            <?php endif ?>
                                            </div>         
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="nav-link" href="#">Profile</a></li>
                                <li><a class="nav-link" href="/project end/php/provider/meetingroom.php">ห้องประชุม</a></li>
                                <li><a class="nav-link" href="/project end/php/tutor/tutor.php">ติวเตอร์</a></li>
                                <li><a class="nav-link" href="#">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if (isset($_SESSION['email'])): ?>
                                    <div class="li-a-logout" onclick="location.href='/project end/php/home.php?logout=1'"><label  style="cursor:pointer;">Logout</label></div>
                                    <!-- <p><a href="home.php?logout='1'">Logout</a></p> -->
                                <?php endif; ?>
                            </ul>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <script>
        // ใช้ JavaScript เพื่ออัปเดตจำนวนการแจ้งเตือน
        document.querySelector('.badge').textContent = '0'; // เปลี่ยนจำนวนการแจ้งเตือน 
        // ใช้ JavaScript เพื่ออัปเดตจำนวนการแจ้งเตือน
        document.querySelector('.custom-notification-badge').textContent = '0'; // เปลี่ยนจำนวนการแจ้งเตือน
    </script>
    <script>
    document.querySelectorAll('.notification-card').forEach(card => {
    card.addEventListener('click', function() {
        const id_noti = this.dataset.id;
        const type = this.dataset.type;
        const redirectUrl = this.dataset.url;

        if (this.classList.contains('unread')) {
            fetch('notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id_noti=${id_noti}&type=${type}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = redirectUrl;
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        } else {
            // กรณีอ่านแล้ว ให้ redirect ไปยัง URL ที่กำหนดไว้
            window.location.href = redirectUrl;
        }
    });
});
    </script>

</body>
</html>
