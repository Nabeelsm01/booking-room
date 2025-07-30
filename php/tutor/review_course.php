<?php
    session_start();
    include('../connect.php');

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id']; // สำหรับผู้ใช้ทั่วไป
    } elseif (isset($_SESSION['id_tutor'])) {
        $id_tutor = $_SESSION['id_tutor']; // สำหรับติวเตอร์
    } elseif (isset($_SESSION['id_provider'])) {
        $id_provider = $_SESSION['id_provider']; // สำหรับผู้ให้บริการ
    }

    // รับค่าจากฟอร์ม
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // รับค่าจากฟอร์มและเก็บใน session
        $_SESSION['id_course'] = isset($_POST['id_course']) ? $_POST['id_course'] : 0;   
        $id_course = isset($_POST['id_course']) ? intval($_POST['id_course']) : 0; 

        $_SESSION['id_register_course'] = isset($_POST['id_register_course']) ? $_POST['id_register_course'] : 0;   
        $id_register_course = isset($_POST['id_register_course']) ? intval($_POST['id_register_course']) : 0; 

        // แสดงผลข้อมูลที่เก็บไว้
        // echo "ID Room: " . $id_room . "<br>";
        // echo "ID Booking Room: " . $id_bookingroom . "<br>";
    } else {
        // เมื่อไม่พบ POST data ให้ใช้ค่าจาก session
        $id_course = isset($_SESSION['id_course']) ? intval($_SESSION['id_course']) : 0; 
        $id_register_course = isset($_SESSION['id_register_course']) ? intval($_SESSION['id_register_course']) : 0;

        // แสดงผลข้อมูลจาก session
        // echo "No POST data. Using session values:<br>";
        // echo "ID Room: " . $id_room . "<br>";
        // echo "ID Booking Room: " . $id_bookingroom . "<br>";
    }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีวิวห้องประชุม</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #50c878;
            --background-color: #f0f4f8;
            --text-color: #333;
            --border-color: #e0e0e0;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 30px;
        }

        h1, h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }

        .review-form, .reviews-container {
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 25px;
            margin-bottom: 30px;
        }

        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            margin-bottom: 20px;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .rating-stars label:hover,
        .rating-stars label:hover ~ label,
        .rating-stars input:checked ~ label {
            color: #ffd700;
        }

        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
        }

        input[type="submit"] {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .review-box {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .review-box:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .review-header h3 {
            margin: 0;
            color: var(--primary-color);
        }

        .stars {
            color: #ffd700;
        }

        .review-content {
            background-color: white;
            border-radius: 4px;
            padding: 15px;
            margin-top: 10px;
        }

        .review-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            color: #777;
            font-size: 0.9em;
        }
        /* ... (existing styles) ... */

        .overall-rating {
            text-align: center;
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .overall-rating h2 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .overall-stars {
            font-size: 2.5rem;
            color: #ffd700;
            margin-bottom: 10px;
        }

        .overall-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-color);
        }

        .review-count {
            font-size: 1rem;
            color: #777;
            margin-top: 5px;
        }
        .turn-btn {
            background-color: white; /* Green */
            color: #717FA1;
            padding: 13px 20px;
            text-align: center;
            text-decoration: none; 
            font-size: 16px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-left: 10px;
            margin-top: -10px;
            box-shadow: 0 5px 10px 7px rgba(0, 0, 0, 0.05);
            position:fixed;
        }

        .turn-btn:hover {
            background-color:#90a7dc;
            color:#fff;
            text-decoration: none;
            transition: 0.5s;
            box-shadow: 2px 2px 5px #8892a8;
        }
    </style>
</head>
<body>
<a href="register_course_summary.php" class="turn-btn">ย้อนกลับ</a>
    <div class="container">
        <h1>รีวิวห้องประชุม</h1>
    <?php
        // คำนวณคะแนนรีวิวโดยรวม
        $sql_avg = "SELECT AVG(rating_course) as avg_rating, COUNT(*) as review_count FROM review_course WHERE id_course = $id_course";
        $result_avg = $conn->query($sql_avg);
        $row_avg = $result_avg->fetch_assoc();
        $avg_rating = round($row_avg['avg_rating'], 1);
        $review_count = $row_avg['review_count'];       
    ?>
       <div class="overall-rating">
            <h2>คะแนนรีวิวโดยรวม</h2>
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


        <!-- ฟอร์มการรีวิว -->
        <div class="review-form">
            <h2>ให้รีวิวห้อง</h2>
            <form action="review_submit_course.php" method="post">
                <input type="hidden" name="id_course" value="<?php echo ($id_course); ?>">   <!--ส่งไอดีห้องประชุม value34-->
                <label for="rating">คะแนนการรีวิว:</label>
                <div class="rating-stars">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1"><i class="fas fa-star"></i></label>
                </div>

                <label for="review">ความคิดเห็นของคุณ:</label>
                <textarea name="review_course" required></textarea>
                <!-- <input type="hidden" name="id" value="<?php echo ($id); ?>">   ส่งไอดีผู้ใช้ทั่วไป -->
                <input type="hidden" name="id_register_course" value="<?php echo ($id_register_course); ?>">   <!--ส่งไอดีการจอง-- value416 -->
                <input type="submit" value="ส่งรีวิว">
            </form>     
        </div>
   
        <!-- แสดงรีวิวที่มีอยู่ -->
        <h2>รีวิวที่มีอยู่</h2>
        <div class="reviews-container">
            <?php
            if (!empty($id_course)) {
                $sql = "SELECT r.id_review_course, r.rating_course, r.review_course, r.review_date_course, c.name_course, u.name_lastname
                        FROM review_course r 
                        JOIN user u ON r.id = u.id 
                        JOIN course c ON r.id_course = c.id_course 
                        WHERE c.id_course = $id_course
                        ORDER BY r.review_date_course DESC";
            
                $result = $conn->query($sql);
            } else {
                echo "Room ID is required.";
            }
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='review-box'>";
                    echo "<div class='review-header'>";
                    echo "<h3>คอร์ส: " . htmlspecialchars($row['name_course']) . "</h3>";
                    echo "<span class='stars'>";
                    echo "<p style='display:inline; margin-right:10px; color:#b9b9b9;'>" ."คะแนนรีวิว  ". "</p>";
                    echo "<p style='display:inline; margin-right:10px; color:black;'>" . htmlspecialchars($row['rating_course']) . "/5.0</p>";
                    $rating = intval($row['rating_course']);
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo "<i class='fas fa-star'></i>";
                        } else {
                            echo "<i class='far fa-star'></i>"; 
                        }
                    }
                    echo "</span>";
                    echo "</div>";
                    echo "<div class='review-content'>";
                    echo "<p>" . htmlspecialchars($row['review_course']) . "</p>";
                    echo "</div>";
                    echo "<div class='review-footer'>";
                    if (!empty($row['name_lastname'])) {
                        echo "<span>โดย: " . htmlspecialchars($row['name_lastname']) . "</span>";
                    } else {
                        echo "ชื่อผู้รีวิวไม่สามารถแสดงได้";
                    }                    
                    // echo "<span>โดย: " . htmlspecialchars($row['name_lastname']) . "</span>";
                    echo "<span>วันที่: " . htmlspecialchars($row['review_date_course']) . "</span>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-reviews'>ยังไม่มีรีวิว</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>