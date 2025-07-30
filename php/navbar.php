<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .logout_red {
            color: #FF0000; /* สีของข้อความปกติ */
            text-decoration: none; /* เอาเส้นใต้ของลิงก์ออก */
        }
        .logout_red:hover {
            background-color: #660000;
            /* พื้นหลังสีแดงเมื่อ hover */
            color:#fff;
            font-weight:bold;
            position: relative;
            left:-15px;
            width: 210px;
            border-radius:5px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="innav">
            <a href="/project end/php/home.php" class="logo-container"><img src="/project end/img/asdasdsad.png" alt="" class="logosss"></a>
            <ul class="li-flex">
                <li><a href="/project end/php/home.php">หน้าหลัก</a></li>
                <li><a href="/project end/php/meeting.php">แชท</a></li>
                <li><a href="#">การจอง</a></li>
                <li><a href="#">การแจ้งเตือน</a></li>
                <li class="dropdown-link">
                    <a href="#">เมนู <i class="bi bi-caret-down-fill"></i></a>
                    <ul class="dropdown">
                        <li><a href="#">ติดต่อ</a></li>
                        <li><a href="/project end/php/meeting.php">เกี่ยวกับ</a></li>
                        <li><a href="#">เพิ่มเติม</a></li>
                        <li><a href="/project end/php/navbar.php">navbar</a></li>
                        <li><a href="/project end/php/provider/confirm_room.php">con</a></li>
                        <li><a href="footer.php">footer</a></li>
                    </ul>              
                </li>
            </ul>
            <div class="circleprofile">
                <img src="/project end/img/user.png" alt="profile" class="profileimg">
                <ul class="profiledropd">
                    <li><a href="#"><img src="/project end/img/user.png" alt="profile" class="profileimg"></a></li>
                    <li><input type="button" class="editprofile" value="edit profile" onclick="location.href='#'"></li>
                    <li class="name">
                    <!-- logged in user information -->
                            <?php if (isset($_SESSION['email'])): ?>
                                <p><strong><?php echo $_SESSION['email']; ?></strong></p>
                                <!-- <p><a href="home.php?logout='1'">logout</a></p> --> 
                            <?php endif ?>
                            </li>        
                    <!-- <li class="name">Name Lastname</li> -->
                    <div class="line"></div>
                    <li><a href="#">profile 1</a></li>
                    <li><a href="#">profile 2</a></li>
                    <li><a href="/project end/php/provider/meetingroom.php">ห้องประชุม</a></li>
                    <li><a href="/project end/php/tutor/tutor.php">ติวเตอร์</a></li>
                    <li><a href="login.php">log in</a></li>
                    <?php if (isset($_SESSION['email'])): ?>
                        <li><a class="logout_red" href="/project end/php/home.php?logout='1'">log out</a></li>
                        <!-- <p><a href="home.php?logout='1'">Logout</a></p> -->
                    <?php endif; ?>
                </ul>
            </div>    
        </div>
    </nav> 
</body>
</html>
