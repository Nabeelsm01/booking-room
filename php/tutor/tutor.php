<?php
session_start();
include('../connect.php');
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ และตรวจสอบประเภทผู้ใช้
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'ติวเตอร์') {
    // ถ้าไม่ใช่ติวเตอร์ จะถูกเปลี่ยนเส้นทางไปยังหน้า "unauthorized.php"
    header("Location: /project end/php/unauthorized.php");
    exit();
}

// หากผู้ใช้เป็นติวเตอร์ จะสามารถเข้าถึงเนื้อหาด้านล่างได้
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/project end/css/meet_room_pro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php include('../navbar_tap.php'); ?>
    <div class="allblockcenter">
        <div class="blockcenter">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content">สำหรับผู้ใช้ติวเตอร์</h2>
             <div class="buttonmanage">
                <button class="btn-apply-add" onclick="window.location.href='add_course.php'"><i class="bi bi-plus-circle"></i> เพิ่มคอร์สติว</button>
                <button class="btn-apply-edit" onclick="window.location.href='edit_course.php'"><i class="bi bi-pencil-square"></i> แก้ไขคอร์สติว</button>
                <button class="btn-apply-delete" onclick="window.location.href='delete_course.php'"><i class="bi bi-trash3"></i> ลบคอร์สติว</button>
            </div>
        </div>     
        <div class="three-block">
            <!-- <div class="block"></div> -->
            <!-- <div class="block"></div>
            <div class="block"></div>
            <div class="block"></div> -->
            <?php
               if (isset($_SESSION['id_tutor'])) {
                $id_tutor = $_SESSION['id_tutor'];
            
                // สร้างคำสั่ง SQL
                $sql = "SELECT * FROM course WHERE id_tutor = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_tutor); // ใช้ i สำหรับ integer
                $stmt->execute();
                $result = $stmt->get_result();
            
                // เช็คผลลัพธ์
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='custom-block' data-id='" . $row["id_course"] . "' data-name='" . htmlspecialchars($row["name_course"], ENT_QUOTES) . "' data-detail='" . htmlspecialchars($row["detail_course"], ENT_QUOTES) . "'>";
                        echo "<img src='" . $row["image_course"] . "' alt='" . $row["name_course"] . "'>";
                        echo "<div class='overlay'>ดูรายละเอียดเพิ่มเติม/ลงทะเบียน</div>";
                        echo "<h2>" . $row["name_course"] . "</h2>";
                        echo "<div class='inline-wrapper'>";
                        echo "<div class='inline_1'>";
                        echo "<p class='details'>รายละเอียด: " . $row["detail_course"] . "</p>";
                        echo "<button class='toggle-details'>..อ่านเพิ่มเติม</button>"; 
                        echo "</div>";
                        echo "<div class='divider'></div>"; 
                        echo "<div class='inline_2'>";
                        echo "<p class='details'>ระยะเวลา: " . $row["day_course"] . " วัน</p>";
                        echo "<p class='details'>ราคา: " . $row["price_course"] . " บาท</p>";
                        echo "<p class='details'>ช่องทาง: " . $row["meeting_type"] . "</p>";
                        echo "</div>";
                        echo "</div>"; // ปิด inline-wrapper
                        echo "</div>"; // ปิด block
                    }
                } else {
                    echo "<p>ไม่มีคอร์สติวที่จะแสดง</p>";
                }
            
                $stmt->close();
            } else {
                echo "<p>ไม่มี id_tutor ในเซสชัน</p>";
            }
            
            $conn->close();
               ?>
            
            <!-- เพิ่มบล็อกได้ตามต้องการ -->

              <!-- Modal Structure Template -->
              <div id="custom-modal" class="custom-modal" style="display: none;">
                    <div class="custom-modal-content">
                        <span class="custom-close">&times;</span>
                        <div class="custom-layout-modal">
                            <div class="custom-layout-text">
                                <h2 id="custom-modal-title">รายละเอียดข้อมูล</h2>
                                <p id="custom-modal-details">ข้อมูลเต็ม...</p>
                            </div>    
                            <div class="custom-layout-calender">
                            <div class="custom-image-content" style="margin-bottom:270px; ">
                                <img id="custom-modal-img">
                            </div>               
                            <div class="bg-header-block" >
                                <div class="header-message">
                                    <p></p>
                                </div>
                                <div></div>
                            </div>
                        </div>    
                        </div>
                        <div class="custom-btn-modal">
                            <button class="btn btn-add" id="custom-modal-add-to-cart">เพิ่มตะกร้า</button>
                            <button class="btn btn-add" onclick="window.location.href='#'">ลงทะเบียน</button>
                        </div>
                    </div>
                </div>
    <script>
    document.querySelectorAll('.toggle-details').forEach(button => {
    button.addEventListener('click', () => {
        const inlineWrapper = button.closest('.inline-wrapper'); // ดึง wrapper
        const details1 = inlineWrapper.querySelector('.inline_1 .details'); // ดึงรายละเอียดใน inline_1
        const details2 = inlineWrapper.querySelectorAll('.inline_2 .details'); // ดึงรายละเอียดใน inline_2

        const isExpanded = details1.classList.contains('expanded'); // เช็คว่าขยายอยู่หรือไม่

        // ซ่อนหรือแสดงรายละเอียดใน inline_1
        details1.classList.toggle('expanded'); // สลับคลาสเพื่อขยาย/ย่อ

        // ซ่อนหรือแสดงรายละเอียดใน inline_2
        details2.forEach(detail => {
            detail.classList.toggle('expanded'); // สลับคลาส
        });

        // เปลี่ยนข้อความปุ่ม
        button.textContent = isExpanded ? '..อ่านเพิ่มเติม' : 'ซ่อน';
    });
});

</script>
<script>
document.querySelectorAll('.custom-block').forEach(block => {
    block.addEventListener('click', function() {
        const courseId = this.getAttribute('data-id');
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/project end/php/tutor/get_course_details.php?id=' + courseId, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    document.getElementById('custom-modal-img').src = data.image_course; // แสดงรูปภาพ
                    document.getElementById('custom-modal-title').textContent = "ชื่อคอร์ส: " + data.name_course;
                    document.getElementById('custom-modal-details').innerHTML = `
                        <p><strong>รายละเอียด :</strong> ${data.detail_course}</p>
                        <p><i class="bi bi-cash-coin"></i><strong> ราคา :</strong> ${data.price_course} บาท</p>
                        <p><i class="bi bi-file-check"></i><strong> ช่องทาง :</strong> ${data.meeting_type}</p>
                        <p><i class="bi bi-clock"></i><strong> ระยะเวลา :</strong> ${data.day_course} วัน</p>
                        <p><i class="bi bi-clock"></i><strong> วันที่เริ่มเรียน :</strong> ${data.date_course}</p>
                        <p>_________________________</p>
                        <p><i class="bi bi-person"></i><strong> ชื่อติวเตอร์ :</strong> ${data.name_lastname_tutor}</p>
                        <p><i class="bi bi-envelope-at"></i><strong> อีเมลติวเตอร์ :</strong> ${data.email_tutor}</p>
                        <p><i class="bi bi-house"></i><strong> ที่อยู่ :</strong> ${data.address_tutor}</p>
                    `;

                    const modal = document.getElementById('custom-modal');
                    modal.style.display = 'block';

                    document.querySelector('.custom-close').onclick = function() {
                        modal.style.display = 'none';
                    };

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    };
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }
            } else {
                console.error('Request failed with status:', xhr.status);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    });
});

</script>
</body>
</html>

