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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
        <!-- ลิงก์ JS ของ FullCalendar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
       .calendar {
            width:100%; /* ความกว้าง */
            height: auto; /* ใช้ auto หรือกำหนดค่าความสูงที่เหมาะสม */
            margin: 0; /* จัดกึ่งกลาง */
            overflow: hidden; /* เอา scrollbar ออก */
            border-radius:10px;
            padding:5px;

        }
        .fc-daygrid {
            height: 100%; /* ปรับความสูงตามต้องการ */
            overflow: hidden; /* เอา scrollbar ออก */
            text-decoration:none;
            
        }
        .fc {
            background-color: #ffffff; /* พื้นหลังขาวสะอาด */
            border-radius: 10px; /* มุมโค้ง */
            padding: 15px; /* ช่องว่างรอบปฏิทิน */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* เงา */
            overflow: hidden; /* หรือ auto */
        }
        .header-message {
            width:100%;
            font-size: 18px; /* ขนาดของตัวอักษร */
            background-color:#e9edf1;
            color: #31708f;
            border-left: 6px solid #90a7dc;;
            border-radius:5px;
            padding:3px;
        }
        .bg-header-block{
            width:100%;
            padding:10px;
            border-radius: 30px;
            margin-bottom: 15px;
            height: auto;
            justify-content: flex-end; /* Align right */
            align-items: center;
            overflow: hidden;
            margin-left: auto; /* Move to the right */
            margin-top: 10px;"
        }

    </style>
</head>
<body>
    <?php include('../navbar_tap.php'); ?>
    <div class="allblockcenter">
        <div class="blockcenter_2">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content_2">ห้องประชุม</h2>
             <div class="buttonmanage">
                <!-- <button class="btn btn-add" onclick="window.location.href='form_add_room.php'"><i class="bi bi-plus-circle"></i> เพิ่มห้องประชุม</button>
                <button class="btn btn-edit"><i class="bi bi-pencil-square"></i> แก้ไขห้องประชุม</button>
                <button class="btn btn-delete"><i class="bi bi-trash3"></i> ลบห้องประชุม</button> -->
            </div>
            <!-- <div class="buttonmanage-2">
            <button class="btn-food" onclick="window.location.href='all_meetingroom.php'" style="border:2px solid #90a7dc;"><i class="bi bi-box-arrow-in-up-right"></i> ห้องประชุม</button>
            <button class="btn-food" onclick="window.location.href='#.php'"style="background-color: #dce6ff;"><i class="bi bi-egg-fried"></i> อาหารหลัก</button>
            <button class="btn-food" onclick="window.location.href='#.php'"><i class="bi bi-stack-overflow"></i> อาหารว่าง</button>
            <button class="btn-food" onclick="window.location.href='#.php'"><i class="bi bi-cup-straw"></i> เครื่องดื่ม</button>
        </div> -->
        </div>     
        <div class="three-block">
            <!-- <div class="block"></div> -->

            <?php
                // สร้างคำสั่ง SQL
                $sql = "SELECT * FROM meetingroom";
                $result = $conn->query($sql);         
                // เช็คผลลัพธ์
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id_room = $row["id_room"];
                        echo "<div class='custom-block' data-id='" . $row["id_room"] . "' data-name='" . htmlspecialchars($row["name_room"], ENT_QUOTES) . "' data-detail='" . htmlspecialchars($row["detail_room"], ENT_QUOTES) . "'>";
                        echo "<img src='" . $row["image_room"] . "' alt='" . $row["name_room"] . "'>";
                        echo "<div class='overlay'>ดูรายละเอียดเพิ่มเติม/จอง</div>";
                        echo "<h2>" . $row["name_room"] . "</h2>";
                        echo "<div class='inline-wrapper'>";
                        echo "<div class='inline_1'>";
                        echo "<p class='details'>รายละเอียด: " . $row["detail_room"] . "</p>";
                        echo "<button class='toggle-details'>..อ่านเพิ่มเติม</button>"; 
                
                        // คำนวณคะแนนรีวิวโดยรวม
                        $sql_avg = "SELECT AVG(rating_room) as avg_rating, COUNT(*) as review_count FROM review_room WHERE id_room = $id_room";
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
                        echo "<p class='details'>ความจุ: " . $row["opacity_room"] . " คน</p>";
                        echo "<p class='details'>ราคา: " . $row["price_room"] . " บาท/วัน</p>";
                        echo "<p class='details'>ประเภท: " . $row["room_type"] . "</p>";
                        echo "</div>";
                        echo "</div>"; // ปิด inline-wrapper
                        echo "</div>"; // ปิด block
                    }
                } else {
                    echo "<p>ไม่มีห้องประชุมที่จะแสดง</p>";
                }
         
                    $conn->close();
               ?>
        
            <!-- เพิ่มบล็อกได้ตามต้องการ -->

            <!-- Modal Structure Template -->
            <div id="custom-modal" class="custom-modal" style="display: none;">
                <div class="custom-modal-content" >
                    <span class="custom-close">&times;</span>
                    <div class="custom-layout-modal">
                        <div class="custom-layout-text" >
                            <h2 id="custom-modal-title">รายละเอียดข้อมูล</h2>
                            <p id="custom-modal-details">ข้อมูลเต็ม...</p>
                        </div>    
                        <div class="custom-layout-calender">
                            <div class="custom-image-content" style="margin-bottom:270px; ">
                                <img id="custom-modal-img">
                            </div>               
                            <div class="bg-header-block" >
                                <div class="header-message">
                                    <p> ดูรายละเอียดด้านล่าง แล้วเช็คห้องประชุมว่างได้ทันที!</p>
                                </div>
                                <div id="calendar" class="calendar" ></div>
                            </div>
                        </div>    
                    </div>
                    <div class="custom-btn-modal" >
                        <button class="btn-apply" id="custom-modal-add-to-cart"><i class="bi bi-bookmark"></i> บันทึกไว้</button>
                         <!-- ปรับปุ่มจองให้ทำงานร่วมกับฟอร์ม -->
                        <form id="booking-form" action="/project end/php/provider/booking_room.php" method="POST" style="display: inline;">
                            <input type="hidden" name="image_room" id="image_room">
                            <input type="hidden" name="room_id" id="room_id">
                            <input type="hidden" name="room_name" id="room_name">
                            <input type="hidden" name="room_details" id="room_details">
                            <input type="hidden" name="room_price" id="room_price">
                            <input type="hidden" name="room_type" id="room_type">
                            <input type="hidden" name="opacity_room" id="opacity_room">
                            <input type="hidden" name="address_room" id="address_room"> 
                            <input type="hidden" name="id_provider" id="id_provider"> 

                            <button class="btn-apply" type="submit"><i class="bi bi-pencil"></i> จอง</button>
                        </form>
                    </div>
                    <!-- <div class="bg-header-block" style="border:5px solid red;">
                        <div class="header-message">
                            <p>ดูรายละเอียดด้านล่าง แล้วเช็คห้องประชุมว่างได้ทันที!</p>
                        </div>
                        <div id="calendar" class="calendar" ></div>
                    </div> -->
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
// 
document.querySelectorAll('.custom-block').forEach(block => {
    block.addEventListener('click', function() {
        const roomId = this.getAttribute('data-id');
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/project end/php/provider/get_room_details.php?id=' + roomId, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    document.getElementById('custom-modal-img').src = data.image_room;
                    document.getElementById('custom-modal-title').textContent = "ชื่อห้องประชุม: " + data.name_room;
                    
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
                        <div class="custom-line"></div>
                        <p><strong>รายละเอียด :</strong> ${data.detail_room}</p>
                        <p><i class="bi bi-people"></i><strong> ความจุ :</strong> ${data.opacity_room}</p>
                        <p><i class="bi bi-cash-coin"></i><strong> ราคา :</strong> ${data.price_room} บาท/วัน</p>
                        <p><i class="bi bi-house"></i><strong> ประเภท :</strong> ${data.room_type}</p>
                        <p><i class="bi bi-telephone"></i><strong> เบอร์โทร :</strong> ${data.number_room}</p>
                        <p><i class="bi bi-house"></i><strong> ที่อยู่ :</strong> ${data.address_room}</p>
                        <div class="custom-line"></div>
                        <p><i class="bi bi-person"></i><strong> ชื่อผู้ให้บริการ :</strong> ${data.name_provider}</p>
                        <p><i class="bi bi-envelope-at"></i><strong> อีเมลผู้ให้บริการ :</strong> ${data.email_provider}</p>
                        <p><i class="bi bi-telephone"></i><strong> เบอร์โทรผู้ให้บริการ :</strong> ${data.number_provider}</p>
                        <p><i class="bi bi-house"></i><strong> ที่อยู่ผู้ให้บริการ :</strong> ${data.address_provider}</p>
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
                                    <h3 style="font-size:18px;">ห้อง: ${review.name_room}</h3>
                                    <span class="stars">
                                        <p style='display:inline; margin-right:10px; color:#b9b9b9;'>คะแนนรีวิว: </p>
                                        <p style='display:inline; margin-right:10px; color:black;'>${review.rating_room}/5.0</p>
                                        ${getStars(review.rating_room)}
                                    </span>
                                </div>
                                <div class="review-content">
                                    <p>${review.review_room}</p>
                                </div>
                                <div class="review-footer">
                                    <span>โดย: ${review.reviewer_name ? review.reviewer_name : 'ชื่อผู้รีวิวไม่สามารถแสดงได้'}</span>
                                    <span>วันที่: ${review.review_date_room}</span>
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
                    // Set hidden form values
                    document.getElementById('image_room').value = data.image_room;
                    document.getElementById('room_id').value = data.id_room;
                    document.getElementById('room_name').value = data.name_room;
                    document.getElementById('room_details').value = data.detail_room;
                    document.getElementById('room_price').value = data.price_room; 
                    document.getElementById('room_type').value = data.room_type; 
                    document.getElementById('opacity_room').value = data.opacity_room;
                    document.getElementById('address_room').value = data.address_room;
                    document.getElementById('id_provider').value = data.id_provider;
                    
                    // Show modal
                    const modal = document.getElementById('custom-modal');
                    modal.style.display = 'block';

                    // Close modal when clicking close button or outside
                    document.querySelector('.custom-close').onclick = function() {
                        modal.style.display = 'none';
                    };

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    };

                    // Initialize calendar
                    const calendarEl = document.getElementById('calendar');
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        events: 'fetch_bookings.php?room_id=' + roomId,

                        dateClick: function(info) {
                            if (isDateBooked(new Date(info.dateStr))) {
                                alert("วันที่ " + info.dateStr + " ไม่สามารถจองได้ เนื่องจากมีการจองแล้ว");
                            } else {
                                alert('Date: ' + info.dateStr);
                            }
                        },
                    });

                    function isDateBooked(date) {
                        return calendar.getEvents().some(event => {
                            let eventDate = new Date(event.start);
                            return eventDate.toDateString() === date.toDateString() && event.title === 'ไม่ว่าง';
                        });
                    }

                    calendar.render();

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

</script>
</body>
</html>
