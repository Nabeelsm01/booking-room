<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
            html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
            /* Footer Styles */
            .footer {
            background-color: #1a2a38;
            color: #fff;
            width: 100%; /* ทำให้ความกว้างเต็มหน้าจอ */
            padding: 40px 0;
            font-family: "Prompt", sans-serif;
            box-sizing: border-box; /* รวม padding และ border ในการคำนวณขนาด */
            box-shadow: 0px -5px 15px 5px rgba(0, 0, 0, 0.1);
        }

        .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            padding: 0 50px;
            box-sizing: border-box;
            width: 100%;

        }

        .footer-column h4 {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 600;
        }

        .footer-column p, 
        .footer-column ul {
            margin: 0;
            font-size: 14px;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
            display:flex;
        }

        .footer-column ul li {
            margin-bottom: 5px;
        }

        .footer-column ul li a {
            color: #d3e0ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-column ul li a:hover {
            color: #ffffff;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icons img {
            width: 32px;
            height: 32px;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #90a7dc;
            padding-top: 15px;
            font-size: 14px;
        }

        .footer-bottom p {
            margin: 0;
        }
        /* .space{
            height:50%;
        } */
</style>
<body>
    <!-- เนื้อหาของหน้าเว็บทั้งหมด -->
    <!-- Footer section -->
     <!-- <div class="space"></div> -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>เกี่ยวกับเรา</h4>
                <p>เราให้บริการห้องประชุมและคอร์สติวที่มีคุณภาพสูง</p>
            </div>
            <div class="footer-column">
                <h4>ลิงค์สำคัญ</h4>
                <ul>
                    <li><a href="#">หน้าหลัก</a></li>
                    <li><a href="#">ห้องประชุม</a></li>
                    <li><a href="#">คอร์สติว</a></li>
                    <li><a href="#">ติดต่อเรา</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>ติดตามเรา</h4>
                <div class="social-icons">
                    <a href="#"><img src="../img/facebook-icon.png" alt="Facebook"></a>
                    <a href="#"><img src="../img/twitter-icon.png" alt="Twitter"></a>
                    <a href="#"><img src="../img/instagram-icon.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 My Site. All Rights Reserved.</p>
        </div>
    </footer>
    <!-- End of Footer section -->

    <script>
        // Your JavaScript code here
    </script>    
</body>
</html>
