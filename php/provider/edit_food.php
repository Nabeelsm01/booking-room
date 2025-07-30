<?php
session_start();
include('../connect.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(158deg, rgba(241,217,188,0.5) 0%, rgba(248,229,181,0.5) 100%);
            padding: 40px;
            padding-top:20px;
        }
        h2{
            margin-left:20px;
            font-size:30px;
            font-family: "Prompt", sans-serif;
            font-style: normal;
            font-weight: 600;
        }
        h3{
            font-size:20px;
            font-family: "Prompt", sans-serif;
            font-style: normal;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius:25px;
            overflow: hidden; /* ซ่อนส่วนที่เกินออกจากขอบมน */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
        }

        table, th, td {
            border: 1px solid #ddd;
            border-radius:25px;
            
        }

        th, td {
            padding: 12px;
            text-align: left;
            background-color: #f1ede9;
            border-radius:5px;
            
        }

        th {
            background-color: #f4f4f4;
            
        }

        td img {
            max-width: 100px;
            border-radius: 10px;
        }

        .edit-button {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: darkgreen;
            color: white;
            text-decoration: none;
        }
        .turn-btn {
            background-color: blue; /* Green */
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom:20px;
        }

        .turn-btn:hover {
            background-color: darkblue; 
            color: white;
            text-decoration: none;
        }
    </style>
    <title>Meeting Rooms</title>
</head>
<body>
    <a href="meeting_food.php" class="turn-btn">ย้อนกลับ</a>
    
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success">
           <div class="alert alert-success " role="alert">
            <h3><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></h3>
          </div> 
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['errors'])) : ?>
        <div class="alert alert-danger " role="alert">
        <div class="errors">
            <h3><?php echo $_SESSION['errors']; unset($_SESSION['errors']); ?></h3>
        </div>
        </div> 
    <?php endif; ?>
    <h2>แก้ไขอาหารและเครื่องดื่ม Food/Drink</h2>
    <?php

if (isset($_SESSION['id_provider'])) {
    $id_provider = $_SESSION['id_provider'];

    // ดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT * FROM food WHERE id_provider = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_provider); // Bind id_provider เป็นประเภท integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // แสดงข้อมูลในรูปแบบของ HTML Table
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>รูปภาพ</th>";
        echo "<th>ชื่อ</th>";
        echo "<th>รายละเอียด</th>";
        echo "<th>ประเภท </th>";
        echo "<th>ราคา (บาท)</th>";
        echo "<th>การจัดการ</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><img src='" . $row["image_food"] . "' alt='" . $row["name_food"] . "'></td>";
            echo "<td>" . $row["name_food"] . "</td>";
            echo "<td>" . $row["detail_food"] . "</td>";
            echo "<td>" . $row["type_food"] . "</td>";
            echo "<td>" . $row["price_food"] . "</td>";
            echo "<td><a href='edit_form_food.php?id=" . $row["id_food"] . "' class='edit-button'>แก้ไข</a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "ไม่มีอาหารที่จะแสดง";
    }
    $stmt->close();
} else {
    echo "<p>ไม่มี id_provider ในเซสชัน</p>";
}     
    $conn->close();
    ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
