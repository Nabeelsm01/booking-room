<?php 
   session_start();
   
   if (!isset($_SESSION['email'])) {
       $_SESSION['msg'] = "You must log in first";
       header('location: login.php');
   }

   if (isset($_GET['logout'])){
      session_destroy();
      unset($_SESSION['email']);
      header('location: login.php');
   }
   ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Site</title>
    <link rel="stylesheet" href="../css/home.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <?php include('navbar_tap.php'); ?>

    <div class="allblockcenter">
        <div class="blockcenter">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content" style=" position: relative; z-index: 2;">เว็บให้บริการห้องประชุมและคอร์สติว</h2>
             <!-- ค้นหา -->
             <!-- <div class="search-container">
                <input type="text" placeholder="Search..." class="search-input">
                <button class="search-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10,18 C14.4183,18 18,14.4183 18,10 C18,5.58172 14.4183,2 10,2 C5.58172,2 2,5.58172 2,10 C2,14.4183 5.58172,18 10,18 Z M22,22 L16.6569,16.6569" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div> -->
            <div class="intro-container">
                <div class="intro-text" style=" position: relative; z-index: 2;">
                    <p>" เรามุ่งเน้นการให้บริการห้องประชุมที่สะดวกสบายและคอร์สติวที่มีคุณภาพ เพื่อช่วยเสริมสร้างความรู้และทักษะที่จำเป็นในการทำงานและการเรียนรู้ "</p>
                </div>
                <div class="intro-images">
                    <!-- <img src="/project end/img/conference-room.png" alt="ห้องประชุม" class="animated-image"> -->
                    <img src="/project end/img/image_processing20200120-4206-go121q.gif" alt="การประชุมออนไลน์" class="animated-image">
                    <!-- <img src="/project end/img/tutorial.png" alt="คอร์สติว" class="animated-image"> -->
                </div>
            </div>
        </div>     
        <div class="three-block">
            <div class="block">
                <img src="../img/andjelka-tomasevic-S4Be6HDqyVk-unsplash.jpg" alt="">
                <h2>ห้องประชุม Conference</h2>
                <p>ห้องประชุมขนาดใหญ่, ห้องประชุมขนาดเล็ก, <br>ห้องประชุมธุรกิจ</p>
                <button class="btn-other" onclick="window.location.href='provider/all_meetingroom.php'">ดูเพิ่มเติม</button>
                </div>
                
            <div class="block">
                <img src="../img/12121515.jpg" alt="" style="">
                <h2>ห้องประชุมออนไลน์ Meeting Online</h2>
                <p>แพ็คเกจแบบฟรี, แบบจ่ายเงิน<br> .</p>
                <button class="btn-other" onclick="window.location.href='tutor/form_video_tutor.php'">ดูเพิ่มเติม</button>
                </div>

            <div class="block">
                <img src="../img/3d-render-books-fly-fall-blue-background_107791-17215.avif" alt="">
                <h2>คอร์สติว Tutoring course</h2>
                <p>ลงทะเบียนเพื่อเข้าเรียนวิชาที่ชอบ<br> .</p>
                <button class="btn-other" onclick="window.location.href='tutor/all_course.php'">ดูเพิ่มเติม</button>
                </div>
       </div>
       <div class="content">
    <!-- Notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success text-left position-fixed start-50 translate-middle-x" role="alert" style="z-index: 1050; top: 20px; font-size: 16px; color: darkgreen; width: 400px; padding: 15px; border-radius: 10px;">
            <strong style="font-size: 18px;"><?php echo $_SESSION['success']; ?></strong><br>
            WELCOME: <strong><?php echo $_SESSION['email']; ?></strong>
            <?php 
                // ลบ session ทันทีหลังแสดงผล
                unset($_SESSION['success']);
            ?>
        </div>

        <script>
            // ทำให้ alert เลื่อนลงมาจากข้างบนแล้วค่อยๆ แสดงผล
            document.addEventListener('DOMContentLoaded', function() {
                var alertBox = document.querySelector('.alert');
                setTimeout(function() {
                    alertBox.style.opacity = '1';
                    alertBox.style.transform = 'translateY(0)';
                }, 100); // เริ่มเลื่อนหลังจากโหลดหน้าแล้ว 100ms เพื่อให้ transition ทำงาน
            });

            // ซ่อน alert หลังจาก 5 วินาที
            setTimeout(function() {
                document.querySelector('.alert').style.display = 'none';
            }, 4000);
        </script>
    <?php endif ?>
</div>
</div>
<div class="space"></div>
<div class="footer_home"><?php include('footer.php');?></div>
<script>
 
</script>    
</body>

</html>

