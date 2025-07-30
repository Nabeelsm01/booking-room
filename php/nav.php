
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>navbar</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 2000; /* ทำให้แน่ใจว่า Navbar จะอยู่ด้านบนของเนื้อหาอื่น */
        }
        .text-p{
            font-size:19px;
            font-family: sans-serif;
            font-weight:400;
            font-style: normal;
            color: #7888b2;
        }
        .navbar-end svg {
            width: 24px; /* ปรับขนาดของ SVG icon */
            height: 24px;
        }
        .navbar-end .badge {
            font-size: 0.75rem; /* ปรับขนาดของ badge */
            padding: 0.25rem 0.3rem;
        }
        .justify-between{
            font-size:19px;
            font-family: "Prompt", sans-serif;
            font-weight:400;
            font-style: normal;
            color: #7888b2;
            padding:10px;
        }
        .li-a{
            font-size:18px;
            font-family: "Prompt", sans-serif;
            font-weight:400;
            font-style: normal;
            color: #7888b2;
            margin-top:10px;
            padding:10px;
        }
        .li-a-logout {
            font-size: 18px;
            font-family: "Prompt", sans-serif;
            font-weight: 400;
            font-style: normal;
            color: #7888b2;
            margin-top: 10px;
            padding:10px;
            border-radius:10px;
            cursor:pointer;
        }

        .li-a-logout:hover {
            background-color:  #ffb2b2;
            color: #fff;
            font-weight:400;
            cursor:pointer;
            transition:0.3s;
        }
        .lines{
            width: 100%;
            height: 0;
            margin-bottom: 10px;
            margin-top: 10px;
            box-shadow: 0 -1px 0 0.5px #c5d4fa; /* ใช้ box-shadow เพื่อสร้างเส้นบางๆ */
        }
        .name{
            font-size:15px;
            font-family: "Prompt", sans-serif;
            font-weight:400;
            font-style: normal;
            color: #7888b2;
        }
        .name:hover{
            background:none;
        }
            /* สีพื้นหลังเมื่อ hover สำหรับปุ่มใน navbar */
        .navbar .btn:hover {
            background-color: #e0e7ff; /* เปลี่ยนเป็นสีที่คุณต้องการ */
            color: #1e3a8a; /* เปลี่ยนเป็นสีข้อความที่คุณต้องการ */
        }

        /* สีพื้นหลังเมื่อ hover สำหรับเมนู dropdown */
        .dropdown-content a:hover {
            background-color: #e0e7ff; /* เปลี่ยนเป็นสีที่คุณต้องการ */
            color: #1e3a8a; /* เปลี่ยนเป็นสีข้อความที่คุณต้องการ */
        }

        /* สีพื้นหลังเมื่อ hover สำหรับ badge */
        .badge:hover {
            background-color: #cbd5e1; /* เปลี่ยนเป็นสีที่คุณต้องการ */
            color: #1e3a8a; /* เปลี่ยนเป็นสีข้อความที่คุณต้องการ */
        }

        /* สีพื้นหลังเมื่อ hover สำหรับเมนูใน dropdown */
        .menu li a:hover {
            background-color: #e0e7ff; /* เปลี่ยนเป็นสีที่คุณต้องการ */
            color: #1e3a8a; /* เปลี่ยนเป็นสีข้อความที่คุณต้องการ */
        }
        .dropdown-toggle {
            display: flex;
            align-items: center;
        }
        .dropdown-icon {
            transition: transform 0.3s;
        }
        .dropdown-open .dropdown-icon {
            transform: rotate(180deg);
        }
        .dropdown-content a {
            color: #7888b2;
        }
        .dropdown-content a:hover {
            background-color: #e0e7ff;
            color: #1e3a8a;
        }
        .submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -10px;
            margin-top: 0;
            margin-left: 0;
        }
        .menu-item:hover .submenu {
            display: block;
        }
        .custom-dropdown {
            width: 230px; /* ความกว้างของ dropdown */
        }
    </style>
    
</head>
<body>
        <div class="navbar bg-base-100 shadow-lg " style="height: 40px;">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl p-0" style="margin-left: 35px;">
               <img src="/project end/img/meetingroom.png" alt="Logo" class="h-11">
            </a>
        </div>
        <div class="flex-none" style="margin-right: 35px;">
            <div tabindex="0" role="button" class="btn btn-ghost " style="margin-left:15px;">
                <div class="indicator">
                    <p class="text-p">หน้าหลัก</p>
                </div>
            </div>
            <div tabindex="0" role="button" class="btn btn-ghost " style="margin-left:15px;">
                <div class="indicator">
                    <p class="text-p">แชทสนทนา</p>
                </div>
            </div>
            <div class="dropdown dropdown-end ">
                <div tabindex="0" role="button" class="btn btn-ghost " style="margin-left:15px;">
                    <div class="indicator">
                        <p class="text-p">การจอง"</p>
                <!-- <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg> -->
                <span class="badge badge-sm indicator-item "style="color:red; padding:0 2px; font-weight:bold;">1</span>
                </div>
            </div>
            <div
                tabindex="0"
                class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-52 shadow">
                <div class="card-body">
                <span class="text-lg font-bold">8 Items</span>
                <span class="text-info">Subtotal: $999</span>
                <div class="card-actions">
                    <button class="btn btn-primary btn-block">View cart</button>
                </div>
                </div>
            </div>
            </div>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost" style="margin-left:15px;">
                    <div class="indicator">
                        <p class="text-p">เมนู</p>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a class="li-a"  href="#">หน้าหลัก</a></li>
                    <li><a class="li-a" href="#">ติดต่อ</a></li>
                    <li><a class="li-a" href="/project end/php/meeting.php">เกี่ยวกับ</a></li>
                    <li class="menu-item">
                        <a class="li-a" href="#"><p >เพิ่มเติม</p></a>
                        <ul class="submenu bg-base-100 rounded-box shadow mt-2 w-44 p-2">
                            <li><a class="li-a" href="#">Submenu 1</a></li>
                            <li><a class="li-a" href="/project end/php/navbar.php">navbar</a></li>
                            <li><a class="li-a" href="/project end/php/navbar_tap.php">navbar tap</a></li>
                            <li><a class="li-a" href="/project end/php/login.php">login</a></li>
                            <li><a class="li-a" href="/project end/php/provider/confirm_room.php">con</a></li>
                            <li><a class="li-a" href="footer.php">footer</a></li>
                        </ul>
                    </li>
                    <!-- คุณสามารถเพิ่มลิงก์เมนูเพิ่มเติมที่นี่ -->
                </ul>
            </div>
            
            <div class="navbar-end ">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle" style="margin-left:15px; margin-right:15px;">
                <div class="indicator">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="badge badge-xs badge-primary indicator-item"></span>
                </div>
                </button>
            </div>
            </div>

            <div class="dropdown dropdown-end" style="margin-left:15px;">
                
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                <img
                    alt="Tailwind CSS Navbar component"
                    src="/project end/img/user.png" />
                </div>
            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow ">
                <div class="w-10 rounded-full" style="margin:auto; margin-bottom:10px; margin-top:10px; padding:0;">
                    <img
                        alt="Tailwind CSS Navbar component"
                        src="/project end/img/user.png" />
                    </div>
                <li>
                <div class="name">
                    <!-- logged in user information -->
                            <?php if (isset($_SESSION['email'])): ?>
                                <p class="name"><strong><?php echo $_SESSION['email']; ?></strong></p>
                                <!-- <p><a href="home.php?logout='1'">logout</a></p> --> 
                            <?php endif ?>  
                </div>
                <div class="name">
                            <?php if (isset($_SESSION['name_lastname']) || isset($_SESSION['name_lastname_tutor']) || isset($_SESSION['name_provider'])): ?>
                                <p class="name">ชื่อ : 
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
                                <p class="name">ผู้ใช้สถานะ : <?php echo $_SESSION['user_type']; ?></p>
                                <?php endif ?>
                </div>         

                <div class="lines"></div>    
                <a class="justify-between">
                    Profile
                    <span class="badge">New</span>
                </a>
                </li>
                <li><a class="li-a" href="/project end/php/provider/meetingroom.php">ห้องประชุม</a></li>
                <li><a class="li-a" href="/project end/php/tutor/tutor.php">ติวเตอร์</a></li>
                <li><a class="li-a">Settings</a></li>
                <?php if (isset($_SESSION['email'])): ?>
                        <div class="li-a-logout" onclick="location.href='/project end/php/home.php?logout=1'"><label  style="cursor:pointer;">Logout</label></div>
                        <!-- <p><a href="home.php?logout='1'">Logout</a></p> -->
                    <?php endif; ?>
            </ul>
            </div>
        </div>
        </div>

</html>