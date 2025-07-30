<?php
session_start();
include('../connect.php');

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

    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php include('../navbar_tap.php'); ?>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">คอร์สติว</h2>
             <div class="buttonmanage">
                <!-- <button class="btn btn-add" onclick="window.location.href='form_add_room.php'"><i class="bi bi-plus-circle"></i> เพิ่มห้องประชุม</button>
                <button class="btn btn-edit"><i class="bi bi-pencil-square"></i> แก้ไขห้องประชุม</button>
                <button class="btn btn-delete"><i class="bi bi-trash3"></i> ลบห้องประชุม</button> -->
            </div>
        </div>     
        <div class="three-block">
            <!-- <div class="block"></div> -->

            <?php
                // สร้างคำสั่ง SQL
                $sql = " SELECT * FROM course ";
                $result = $conn->query($sql);         
                // เช็คผลลัพธ์
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id_course = $row["id_course"];
                        echo "<div class='custom-block' data-id='" . $row["id_course"] . "' data-name='" . htmlspecialchars($row["name_course"], ENT_QUOTES) . "' data-detail='" . htmlspecialchars($row["detail_course"], ENT_QUOTES) . "'>";
                        echo "<img src='" . $row["image_course"] . "' alt='" . $row["name_course"] . "'>";
                        echo "<div class='overlay'>ดูรายละเอียดเพิ่มเติม/ลงทะเบียน</div>";
                        echo "<h2>" . $row["name_course"] . "</h2>";
                        echo "<div class='inline-wrapper'>";
                        echo "<div class='inline_1'>";
                        echo "<p class='details'>รายละเอียด: " . $row["detail_course"] . "</p>";
                        echo "<button class='toggle-details'>..อ่านเพิ่มเติม</button>"; 
                        // คำนวณคะแนนรีวิวโดยรวม
                        $sql_avg = "SELECT AVG(rating_course) as avg_rating, COUNT(*) as review_count FROM review_course WHERE id_course = $id_course";
                        $result_avg = $conn->query($sql_avg);
                        $row_avg = $result_avg->fetch_assoc();
                        $avg_rating = round($row_avg['avg_rating'], 1);
                        $review_count = $row_avg['review_count'];       
                        ?>
                       <div class="overall-rating">
                            <div class="overall-stars">
                                <?php
                                $full_stars = floor($avg_rating);
                                $half_star = $avg_rating - $full_stars >= 0.5;
                                
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $full_stars) {
                                        echo "<i class='fas fa-star'></i>";
                                    } elseif ($i == $full_stars + 1 && $half_star) {
                                        echo "<i class='fas fa-star-half-alt'></i>";
                                    } else {
                                        echo "<i class='far fa-star'></i>";
                                    }
                                }
                                ?>
                            </div>
                            <div class="overall-number"><?php echo $avg_rating; ?>/5</div>
                            <div class="review-count"><?php echo $review_count; ?> รีวิว</div>
                        </div>
                        <?php
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
                $conn->close();
               ?>

               <!-- Modal Structure Template -->
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
                                <div class="calendar" ></div>
                            </div>
                        </div>    
                        </div>
                        <div class="custom-btn-modal">
                            <button class="btn-apply" id="custom-modal-add-to-cart"><i class="bi bi-bookmark"></i> บันทึกไว้</button>
                             <!-- ปรับปุ่มจองให้ทำงานร่วมกับฟอร์ม -->
                        <form id="booking-form" action="/project end/php/tutor/register_stepone.php" method="POST" style="display: inline;">
                            <input type="hidden" name="image_course" id="image_course">
                            <input type="hidden" name="id_course" id="id_course">
                            <input type="hidden" name="name_course" id="name_course">
                            <input type="hidden" name="detail_course" id="detail_course">
                            <input type="hidden" name="price_course" id="price_course">
                            <input type="hidden" name="meeting_type" id="meeting_type">
                            <input type="hidden" name="room_course" id="room_course">
                            <input type="hidden" name="day_course" id="day_course">
                            <input type="hidden" name="date_course" id="date_course">
                            <input type="hidden" name="date_course_end" id="date_course_end">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">
                            <input type="hidden" name="duration_hour" id="duration_hour">
                            <input type="hidden" name="id_tutor" id="id_tutor">

                            <button class="btn-apply" onclick="window.location.href='#'" id="add-to-cart"><i class="bi bi-pencil"></i> ลงทะเบียน</button>
                        </form>
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
                    // Calculate and display reviews
                    const avgRating = parseFloat(data.avg_rating).toFixed(1);
                    const reviewCount = data.review_count;
                    const fullStars = Math.floor(avgRating);
                    const halfStar = avgRating - fullStars >= 0.5;
                    
                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        if (i <= fullStars) {
                            starsHtml += "<i class='fas fa-star'></i>";
                        } else if (i == fullStars + 1 && halfStar) {
                            starsHtml += "<i class='fas fa-star-half-alt'></i>";
                        } else {
                            starsHtml += "<i class='far fa-star'></i>";
                        }
                    }
                    document.getElementById('custom-modal-details').innerHTML = `
                        <p><strong>รายละเอียด :</strong> ${data.detail_course}</p>
                        <p><i class="bi bi-cash-coin"></i><strong> ราคา :</strong> ${data.price_course} บาท</p>
                        <p><i class="bi bi-file-check"></i><strong> ช่องทาง :</strong> ${data.meeting_type}</p>
                        <p><i class="bi bi-clock"></i><strong> ระยะเวลา :</strong> ${data.day_course} วัน</p> 
                        <p><i class="bi bi-clock"></i><strong> วันที่เริ่มเรียน :</strong> ${data.date_course}</p>
                        <p><i class="bi bi-clock"></i><strong> วันที่สิ้นสุดเรียน :</strong> ${data.date_course_end}</p>
                        <p>_________________________</p>
                        <p><i class="bi bi-clock"></i><strong> เวลาเรียน :</strong> ${data.start_time} น.</p> 
                        <p><i class="bi bi-clock"></i><strong> เวลาสิ้นสุดเรียน :</strong> ${data.end_time} น.</p> 
                        <p><i class="bi bi-clock"></i><strong> ชั่วโมงรวม :</strong> ${data.duration_hour} </p> 
                        <p>_________________________</p>
                        <p><i class="bi bi-person"></i><strong> ชื่อติวเตอร์ :</strong> ${data.name_lastname_tutor}</p>
                        <p><i class="bi bi-envelope-at"></i><strong> อีเมลติวเตอร์ :</strong> ${data.email_tutor}</p>
                        <p><i class="bi bi-house"></i><strong> ที่อยู่ :</strong> ${data.address_tutor}</p>
                         <div class="custom-line"></div>
                        <div class="overall-rating">
                            <div class="overall-stars">${starsHtml}</div>
                            <div class="overall-number">${avgRating}/5</div>
                            <div class="review-count">${reviewCount} รีวิว</div>
                        </div>
                        <div class="reviews-container">
                        <h2 style="font-size:20px;">ความคิดเห็นทั้งหมด</h2>
                        ${data.reviews.map(review => `
                            <div class="review-box">
                                <div class="review-header">
                                    <h3 style="font-size:18px;">ห้อง: ${review.name_course}</h3>
                                    <span class="stars">
                                        <p style='display:inline; margin-right:10px; color:#b9b9b9;'>คะแนนรีวิว: </p>
                                        <p style='display:inline; margin-right:10px; color:black;'>${review.rating_course}/5.0</p>
                                        ${getStars(review.rating_course)}
                                    </span>
                                </div>
                                <div class="review-content">
                                    <p>${review.review_course}</p>
                                </div>
                                <div class="review-footer">
                                    <span>โดย: ${review.name_lastname ? review.name_lastname : 'ชื่อผู้รีวิวไม่สามารถแสดงได้'}</span>
                                    <span>วันที่: ${review.review_date_course}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div> 
                    `;
                    function getStars(rating) {
                        let starsHtml = '';
                        for (let i = 1; i <= 5; i++) {
                            if (i <= rating) {
                                starsHtml += "<i class='fas fa-star'></i>";
                            } else {
                                starsHtml += "<i class='far fa-star'></i>"; 
                            }
                        }
                        return starsHtml;
                    }

                      // กำหนดค่าข้อมูลที่ซ่อนอยู่ในฟอร์ม
                    document.getElementById('image_course').value = data.image_course;
                    document.getElementById('id_course').value = data.id_course;
                    document.getElementById('name_course').value = data.name_course;
                    document.getElementById('detail_course').value = data.detail_course;
                    document.getElementById('price_course').value = data.price_course; 
                    document.getElementById('meeting_type').value = data.meeting_type; 
                    document.getElementById('room_course').value = data.room_course; 
                    document.getElementById('day_course').value = data.day_course;
                    document.getElementById('date_course').value = data.date_course;
                    document.getElementById('date_course_end').value = data.date_course_end;
                    document.getElementById('start_time').value = data.start_time;
                    document.getElementById('end_time').value = data.end_time;
                    document.getElementById('duration_hour').value = data.duration_hour;
                    document.getElementById('id_tutor').value = data.id_tutor;

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

