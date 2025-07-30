<?php
header('Content-Type: application/json');
include('../connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = " SELECT course.*, tutor.name_lastname_tutor, tutor.email_tutor, tutor.address_tutor
                        FROM course
                        JOIN tutor ON course.id_tutor = tutor.id_tutor
                        WHERE course.id_course = ?";
                        
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // สร้างอาร์เรย์สำหรับการตอบกลับ
    $response = [];

    // ตรวจสอบว่ามีข้อมูลห้องประชุมหรือไม่
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = $row; // ใช้ข้อมูลจากห้องประชุม

        // คำสั่ง SQL สำหรับคำนวณค่าเฉลี่ยคะแนนและจำนวนรีวิว
        $sql_avg = "SELECT AVG(rating_course) as avg_rating, COUNT(*) as review_count FROM review_course WHERE id_course = ?";
        $stmt_avg = $conn->prepare($sql_avg);
        $stmt_avg->bind_param("i", $id);
        $stmt_avg->execute();
        $result_avg = $stmt_avg->get_result();
        $row_avg = $result_avg->fetch_assoc();

        // เพิ่มค่าเฉลี่ยคะแนนและจำนวนรีวิวในอาร์เรย์การตอบกลับ
        $response['avg_rating'] = round($row_avg['avg_rating'], 1);
        $response['review_count'] = $row_avg['review_count'];

        // คำสั่ง SQL สำหรับดึงรีวิวที่มีอยู่
        $sql_reviews = "SELECT r.id_review_course, r.rating_course, r.review_course, r.review_date_course, u.name_lastname, 
                        c.name_course 
                        FROM review_course r 
                        JOIN user u ON r.id = u.id 
                        JOIN course c ON r.id_course = c.id_course 
                        WHERE c.id_course = ?
                        ORDER BY r.review_date_course DESC";                

        $stmt_reviews = $conn->prepare($sql_reviews);
        $stmt_reviews->bind_param("i", $id);
        $stmt_reviews->execute();
        $result_reviews = $stmt_reviews->get_result();

        // สร้างอาร์เรย์เพื่อเก็บรีวิว
        $reviews = [];
        
        if ($result_reviews->num_rows > 0) {
            while ($row_review = $result_reviews->fetch_assoc()) {
                $reviews[] = $row_review; // เพิ่มรีวิวลงในอาร์เรย์
            }
        }

        // เพิ่มรีวิวลงใน response
        $response['reviews'] = $reviews;

        // ปิด statement สำหรับการคำนวณค่าเฉลี่ยและรีวิว
        $stmt_avg->close();
        $stmt_reviews->close();
    } else {
        $response['error'] = "No room found";
    }

    // ปิด statement สำหรับห้องประชุม
    $stmt->close();
    } else {
    $response['error'] = "Invalid ID";
}

// ส่งข้อมูลการตอบกลับเป็น JSON
echo json_encode($response);

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

