<?php
header('Content-Type: application/json');
include('../connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // คำสั่ง SQL สำหรับดึงข้อมูลห้องประชุมและข้อมูลผู้ให้บริการ
    $sql = "SELECT meetingroom.*, provider.id_provider, provider.name_provider, provider.email_provider, provider.number_provider, provider.address_provider
            FROM meetingroom
            JOIN provider ON meetingroom.id_provider = provider.id_provider
            WHERE meetingroom.id_room = ?";
    
    // เตรียมและดำเนินการคำสั่ง SQL
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
        $sql_avg = "SELECT AVG(rating_room) as avg_rating, COUNT(*) as review_count FROM review_room WHERE id_room = ?";
        $stmt_avg = $conn->prepare($sql_avg);
        $stmt_avg->bind_param("i", $id);
        $stmt_avg->execute();
        $result_avg = $stmt_avg->get_result();
        $row_avg = $result_avg->fetch_assoc();

        // เพิ่มค่าเฉลี่ยคะแนนและจำนวนรีวิวในอาร์เรย์การตอบกลับ
        $response['avg_rating'] = round($row_avg['avg_rating'], 1);
        $response['review_count'] = $row_avg['review_count'];

        // คำสั่ง SQL สำหรับดึงรีวิวที่มีอยู่
        $sql_reviews = "SELECT r.id_review_room, r.rating_room, r.review_room, r.review_date_room, 
                        COALESCE(u.name_lastname, t.name_lastname_tutor) AS reviewer_name, 
                        m.name_room 
                        FROM review_room r 
                        LEFT JOIN user u ON r.id = u.id 
                        LEFT JOIN tutor t ON r.id_tutor = t.id_tutor 
                        JOIN meetingroom m ON r.id_room = m.id_room 
                        WHERE m.id_room = ?
                        ORDER BY r.review_date_room DESC";                

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
