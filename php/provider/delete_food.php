<?php
session_start();
include('../connect.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project end/css/alert.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            margin-top:20px;
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

        .delete-button {
            background-color: red; /* Green */
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

        .delete-button:hover {
            background-color: darkred;
            color:#fff;
            text-decoration: none;
        }.turn-btn {
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
        }

        .turn-btn:hover {
            background-color: darkblue;
            color:#fff;
            text-decoration: none;
        }
    </style>
    <title>delete Meeting Rooms</title>
</head>
<body>
    <a href="meeting_food.php" class="turn-btn">ย้อนกลับ</a>
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success1">
        <div class="alert alert-success " role="alert">
            <h3><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></h3>
        </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['errors'])) : ?>
        <div class="errors1">
        <div class="alert alert-danger " role="alert">
            <h3><?php echo $_SESSION['errors']; unset($_SESSION['errors']); ?></h3>
        </div>
        </div>
    <?php endif; ?>
    <h2>ลบอาหารและเครื่องดื่ม Food/Drink</h2>
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
            
           // echo "<td><a href='delete_db.php?id=" . $row["id_room"] . "' class='delete-button'>ลบ</a></td>";
           echo "<td>
           <a href='#' class='delete-button' data-toggle='modal' data-target='#deleteModal" . $row["id_food"] . "'>ลบ</a>
   
           <div class='modal fade' id='deleteModal" . $row["id_food"] . "' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
               <div class='modal-dialog' role='document'>
                   <div class='modal-content'>
                       <div class='modal-header'>
                           <h5 class='modal-title' id='deleteModalLabel'>ยืนยันการลบ</h5>
                           <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                               <span aria-hidden='true'>&times;</span>
                           </button>
                       </div>
                       <div class='modal-body'>
                           คุณต้องเมนูนี้ใช่ไหม?
                       </div>
                       <div class='modal-footer'>
                           <button type='button' class='btn btn-secondary' data-dismiss='modal'>ยกเลิก</button>
                           <a href='delete_fooddb.php?id=" . $row["id_food"] . "' class='btn btn-danger'>ลบ</a>
                       </div>
                   </div>
               </div>
           </div>
         </td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "ไม่มีเมนูที่จะแสดง";
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
