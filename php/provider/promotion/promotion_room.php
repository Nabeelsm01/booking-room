<?php
session_start();
include('../../connect.php');
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ และตรวจสอบประเภทผู้ใช้
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'ผู้ให้บริการ') {
    // ถ้าไม่ใช่ติวเตอร์ จะถูกเปลี่ยนเส้นทางไปยังหน้า "unauthorized.php"
    header("Location: /project end/php/unauthorized.php");
    exit();
}
// สมมติว่ามี id_provider ใน session
$id_provider = $_SESSION['id_provider']; 

$sql = "SELECT promotion_room.*, meetingroom.name_room 
        FROM promotion_room 
        JOIN meetingroom ON promotion_room.id_room = meetingroom.id_room
        WHERE meetingroom.id_provider = ?";  // เพิ่มเงื่อนไข id_provider

// ใช้ prepared statement เพื่อความปลอดภัย
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_provider); // ผูก id_provider กับ query
$stmt->execute();
$result = $stmt->get_result();

// เก็บข้อมูลในอาเรย์
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row; // เก็บข้อมูลแต่ละแถวในอาเรย์
}
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
    <?php include('../../navbar_tap.php'); ?>
    <div class="allblockcenter">
        <div class="blockcenter" style="padding:0px 0 10px 0; height:auto;">
            <!-- <h1 class="texthead">เว็บให้บริการห้องประชุมและคอร์สติว</h1> -->
             <h2 class="text-content">จัดการโปรโมชั่น</h2>
             <!-- <div class="buttonmanage">
                <button class="btn-apply-add" onclick="window.location.href='form_add_room.php'"><i class="bi bi-plus-circle"></i> เพิ่มห้องประชุม</button>
                <button class="btn-apply-edit" onclick="window.location.href='edit.php'"><i class="bi bi-pencil-square"></i> แก้ไขห้องประชุม</button>
                <button class="btn-apply-delete" onclick="window.location.href='delete.php'"><i class="bi bi-trash3"></i> ลบห้องประชุม</button>
            </div> -->
            <div class="buttonmanage-2">
            <button class="btn-food" onclick="window.location.href='../meetingroom.php'" style="border:2px solid #90a7dc;"><i class="bi bi-box-arrow-in-up-right"></i></i> ห้องประชุม</button>
        </div>
        </div>

        <div class="blockcenter" style="padding:20px; height:auto;">
        <div class="container mt-5">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPromotionModal">เพิ่มโปรโมชั่นใหม่</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ห้องประชุม</th>
                <th>ชื่อโปรโมชั่น</th>
                <th>ประเภท</th>
                <th>มูลค่าส่วนลด</th>
                <th>วันที่เริ่มต้น</th>
                <th>วันที่สิ้นสุด</th>
                <th>สถานะ</th>
                <th>การดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if (isset($_SESSION['id_provider'])) {
                $id_provider = $_SESSION['id_provider'];

                if (count($data) > 0) {  // ใช้เช็คจากข้อมูลใน $data แทนการตรวจสอบ $result อีกครั้ง
                    foreach ($data as $row) {
                        echo "<tr>";
                        // แสดงผลข้อมูล
                        echo "<td>" . htmlspecialchars($row['id_room']) . " - " . htmlspecialchars($row['name_room']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['promotion_title_room']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['promotion_type_room']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['discount_value_room']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_date_room']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['end_date_room']) . "</td>";
                        echo "<td>";
                        if ($row['status'] == 'active') {
                            echo '<span class="badge bg-success">ใช้งาน</span>';
                        } else {
                            echo '<span class="badge bg-secondary">ไม่ใช้งาน</span>';
                        }
                        echo "</td>";
                        echo "<td>";
                        echo '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPromotionModal" data-id="'.$row['id_promotion_room'].'">แก้ไข</button> ';
                        echo '<a href="delete_pro.php?id_promotion_room=' . $row['id_promotion_room'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'คุณแน่ใจหรือไม่ที่จะลบโปรโมชั่นนี้?\')">ลบ</a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>ไม่มีโปรโมชั่น</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal เพิ่มโปรโมชั่น -->

<!-- Modal เพิ่มโปรโมชั่น -->
<div class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="add_pro.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addPromotionModalLabel">เพิ่มโปรโมชั่นใหม่</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <?php
          if (isset($_SESSION['id_provider'])) {
              $id_provider = $_SESSION['id_provider'];

              // คำสั่ง SQL ที่เลือกห้องประชุมเฉพาะของผู้ให้บริการนั้น
              $sql = "SELECT id_room, name_room FROM meetingroom WHERE id_provider = ?";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("i", $id_provider);
              $stmt->execute();
              $result = $stmt->get_result();
              ?>

              <div class="mb-3">
                  <label for="roomSelect" class="form-label">ห้องประชุม</label>
                  <select class="form-select" id="roomSelect" name="id_room" required>
                      <?php
                      if ($result->num_rows > 0) {
                          // วน loop เพื่อแสดงผลแต่ละห้องประชุม
                          while ($row = $result->fetch_assoc()) {
                              echo '<option value="'.$row['id_room'].'">'.$row['name_room'].'</option>';
                          }
                      } else {
                          echo '<option value="">ไม่มีห้องประชุมสำหรับผู้ให้บริการนี้</option>';
                      }
                      ?>
                  </select>
              </div>

              <?php
              $stmt->close();
          } else {
              echo "ไม่พบข้อมูลผู้ให้บริการ";
          }
          ?>
              <div class="mb-3">
                  <label for="promotionTitle" class="form-label">ชื่อโปรโมชั่น</label>
                  <input type="text" class="form-control" id="promotionTitle" name="promotion_title_room" required>
              </div>
              <div class="mb-3">
                  <label for="promotionType" class="form-label">ประเภทโปรโมชั่น</label>
                  <select class="form-select" id="promotionType" name="promotion_type_room" required>
                      <option value="percentage">ส่วนลดเปอร์เซ็นต์ %</option>
                      <option value="fixed">ส่วนลดแบบคงที่</option>
                      <!-- เพิ่มประเภทโปรโมชั่นเพิ่มเติมถ้าจำเป็น -->
                  </select>
              </div>
              <div class="mb-3">
                  <label for="discountValue" class="form-label">มูลค่าส่วนลด</label>
                  <input type="text" class="form-control" id="discountValue" name="discount_value_room" required>
              </div>
              <div class="mb-3">
                  <label for="startDate" class="form-label">วันที่เริ่มต้น</label>
                  <input type="date" class="form-control" id="startDate" name="start_date_room" required>
              </div>
              <div class="mb-3">
                  <label for="endDate" class="form-label">วันที่สิ้นสุด</label>
                  <input type="date" class="form-control" id="endDate" name="end_date_room" required>
              </div>
              <div class="mb-3">
                  <label for="promoCode" class="form-label">รหัสโปรโมชั่น</label>
                  <input type="text" class="form-control" id="promoCode" name="promo_code_room">
              </div>
              <div class="mb-3">
                  <label for="terms" class="form-label">เงื่อนไข</label>
                  <textarea class="form-control" id="terms" name="terms" rows="3" required></textarea>
              </div>
              <!-- เพิ่มฟิลด์อื่นๆ ตามต้องการ -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-primary">เพิ่มโปรโมชั่น</button>
          </div>
        </div>
    </form>
  </div>
</div>


<!-- Modal แก้ไขโปรโมชั่น -->
<div class="modal fade" id="editPromotionModal" tabindex="-1" aria-labelledby="editPromotionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="edit_pro.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPromotionModalLabel">แก้ไขโปรโมชั่น</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- ฟิลด์ซ่อนเพื่อเก็บค่า id ของโปรโมชั่น -->
          <input type="hidden" id="editPromotionId" name="id_promotion_room" value="">
          
          <?php
          if (isset($_SESSION['id_provider'])) {
              $id_provider = $_SESSION['id_provider'];

              // คำสั่ง SQL ที่เลือกห้องประชุมเฉพาะของผู้ให้บริการนั้น
              $sql = "SELECT id_room, name_room FROM meetingroom WHERE id_provider = ?";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("i", $id_provider);
              $stmt->execute();
              $result = $stmt->get_result();
              ?>

              <div class="mb-3">
                  <label for="roomSelect" class="form-label">ห้องประชุม</label>
                  <select class="form-select" id="roomSelect" name="id_room" required>
                      <?php
                      if ($result->num_rows > 0) {
                          // วน loop เพื่อแสดงผลแต่ละห้องประชุม
                          while ($row = $result->fetch_assoc()) {
                              echo '<option value="'.$row['id_room'].'">'.$row['name_room'].'</option>';
                          }
                      } else {
                          echo '<option value="">ไม่มีห้องประชุมสำหรับผู้ให้บริการนี้</option>';
                      }
                      ?>
                  </select>
              </div>

              <?php
              $stmt->close();
          } else {
              echo "ไม่พบข้อมูลผู้ให้บริการ";
          }
          ?>

          <div class="mb-3">
            <label for="editPromotionTitle" class="form-label">ชื่อโปรโมชั่น</label>
            <input type="text" class="form-control" id="editPromotionTitle" name="promotion_title_room" required>
          </div>
          
          <div class="mb-3">
            <label for="editPromotionType" class="form-label">ประเภทโปรโมชั่น</label>
            <select class="form-select" id="editPromotionType" name="promotion_type_room" required>
              <option value="percentage">ส่วนลดเปอร์เซ็นต์ %</option>
              <option value="fixed">ส่วนลดแบบคงที่</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="editDiscountValue" class="form-label">ค่าลด</label>
            <input type="number" class="form-control" id="editDiscountValue" name="discount_value_room" required>
          </div>
          
          <div class="mb-3">
            <label for="editStartDate" class="form-label">วันเริ่มต้น</label>
            <input type="date" class="form-control" id="editStartDate" name="start_date_room" required>
          </div>
          
          <div class="mb-3">
            <label for="editEndDate" class="form-label">วันสิ้นสุด</label>
            <input type="date" class="form-control" id="editEndDate" name="end_date_room" required>
          </div>
          
          <div class="mb-3">
            <label for="editPromoCode" class="form-label">รหัสโปรโมชั่น</label>
            <input type="text" class="form-control" id="editPromoCode" name="promo_code_room">
          </div>
          
          <div class="mb-3">
            <label for="editTerms" class="form-label">ข้อกำหนด</label>
            <textarea class="form-control" id="editTerms" name="terms"></textarea>
          </div>
          
          <div class="mb-3">
            <label for="editStatus" class="form-label">สถานะ</label>
            <select class="form-select" id="editStatus" name="status" required>
              <option value="active">เปิดใช้งาน</option>
              <option value="inactive">ปิดใช้งาน</option>
            </select>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
          <button type="submit" class="btn btn-warning">บันทึกการแก้ไข</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- คุณสามารถใช้ JavaScript เพื่อเติมข้อมูลปัจจุบันของโปรโมชั่นใน Modal -->
<script>
    var editModal = document.getElementById('editPromotionModal')
    editModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget
      var promotionId = button.getAttribute('data-id')

      // ใช้ AJAX เพื่อดึงข้อมูลโปรโมชั่นและเติมในฟอร์ม
      fetch('get_promotion.php?id_promotion_room=' + promotionId)
        .then(response => response.json())
        .then(data => {
            document.querySelector('#editPromotionModal input[name="id_promotion_room"]').value = data.id_promotion_room
            document.querySelector('#editPromotionModal input[name="promotion_title_room"]').value = data.promotion_title_room
            document.querySelector('#editPromotionModal select[name="promotion_type_room"]').value = data.promotion_type_room
            document.querySelector('#editPromotionModal input[name="discount_value_room"]').value = data.discount_value_room
            document.querySelector('#editPromotionModal input[name="start_date_room"]').value = data.start_date_room
            document.querySelector('#editPromotionModal input[name="end_date_room"]').value = data.end_date_room
            document.querySelector('#editPromotionModal input[name="promo_code_room"]').value = data.promo_code_room
            document.querySelector('#editPromotionModal textarea[name="terms"]').value = data.terms
            document.querySelector('#editPromotionModal select[name="status"]').value = data.status

        })
    })
</script>

<!-- Bootstrap JS และ dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- <div class="promo-card">
    <img src="promo-image.jpg" alt="โปรโมชั่นสุดคุ้ม">
    <div class="promo-info">
        <h3>ส่วนลด 50% สำหรับสินค้าทุกชนิด</h3>
        <p>ใช้รหัส: SAVE50</p>
        <p>หมดเขต: 31 ธันวาคม 2024</p>
        <button class="btn-claim">รับโปรโมชั่น</button>
    </div>
</div> -->
        
        </div>





</body>
</html>

