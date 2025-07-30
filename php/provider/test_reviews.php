<?php
session_start();
include('../connect.php');


// ดึงข้อมูลรีวิวจากฐานข้อมูล
$sql = "SELECT r.id_review_room, r.rating_room, r.review_room, r.review_date_room, u.name_lastname, m.name_room 
        FROM review_room r 
        JOIN user u ON r.id = u.id 
        JOIN meetingroom m ON r.id_room = m.id_room 
        ORDER BY r.review_date_room DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='review-box'>";
        echo "<h3>Room: " . htmlspecialchars($row['name_room']) . "</h3>";
        echo "<p><strong>User:</strong> " . htmlspecialchars($row['name_lastname']) . "</p>";

        echo "<p><strong>Rating:</strong> " . htmlspecialchars($row['rating_room']) . "/5.0 stars</p>";
        // แสดงดาวตามคะแนนที่ได้รับ
        $rating = intval($row['rating_room']);
        echo "<p><strong>Rating:</strong> ";
        // แสดงดาวเต็มตามคะแนน
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                echo "<i class='fas fa-star' style='color: #f7b731;'></i>"; // ดาวเต็มสีทอง
            } else {
                echo "<i class='far fa-star' style='color: #ddd;'></i>"; // ดาวว่างสีเทา
            }
        }
        echo "</p>";
        echo "<p><strong>Review:</strong> " . htmlspecialchars($row['review_room']) . "</p>";
        echo "<p><small><em>Reviewed on: " . htmlspecialchars($row['review_date_room']) . "</em></small></p>";
        echo "</div><hr>";
    }
} else {
    echo "No reviews found.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <title>Document</title>
</head>
<body>
    
</body>
</html>
