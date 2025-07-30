<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่ง `id` มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลห้องประชุมตาม `id`
    $sql = "SELECT * FROM food WHERE id_food = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลอาหารนี้";
        exit();
    }
} else {
    echo "ไม่พบ ID ของอาหาร";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account Provider</title>
    <link rel="stylesheet" href="/project end/css/form_addroom.css">
    <link rel="stylesheet" href="/project end/css/modal.css">

    
</head>
<body>
    <div class="container">
        <h2 class="text-content">แก้ไขข้อมูลอาหารและเครื่องดื่ม</h2>
        <form action="update_food.php" method="post" enctype="multipart/form-data">
        <!-- ฟิลด์ที่มีอยู่แล้ว -->
        <input type="hidden" name="id_food" value="<?php echo $room['id_food']; ?>">

        <div class="form__group">
        <input type="text" name="name_food" class="form__input" placeholder="ชื่ออาหารและเครื่องดื่ม" value="<?php echo $room['name_food']; ?>" required="" />
        <label for="name" class="form__label">ชื่ออาหารและเครื่องดื่ม</label>
        </div>

        <div class="form__group">
        <textarea name="detail_food" class="form__input" placeholder="รายละเอียด" value="" required=""><?php echo $room['detail_food']; ?></textarea>
        <label for="name" class="form__label">รายละเอียด</label>
        </div>
        
        <div class="form__group">
                <select name="type_food" class="form__select" required="">
                    <option value="" disabled selected >เลือกประเภทเมนู</option>
                    <option value="อาหารหลัก" <?php echo ($room['type_food'] == 'อาหารหลัก') ? 'selected' : ''; ?>>อาหารหลัก</option>
                    <option value="อาหารว่าง" <?php echo ($room['type_food'] == 'อาหารว่าง') ? 'selected' : ''; ?>>อาหารว่าง</option>
                    <option value="เครื่องดื่ม" <?php echo ($room['type_food'] == 'เครื่องดื่ม') ? 'selected' : ''; ?>>เครื่องดื่ม</option>
                </select>
                <label for="name" class="form__label">เลือกประเภทเมนู</label>
            </div>
        
        <div class="form__group">
        <input type="number" name="price_food" class="form__input" placeholder="ราคา" value="<?php echo $room['price_food']; ?>" required="" />
        <label for="name" class="form__label">ราคา</label>
        </div>   

        <div class="form__group">
        <img id="imagePreview" class="preview" src="<?php echo $room['image_food']; ?>" alt="Image Preview" /><br>
        <input type="file" name="image_food" class="form__input" accept="image/*" id="imageInput"  />
        <label for="name" class="form__label">preview</label>
        </div>

        <!-- <button type="submit" class="submit">Submit</button> -->
        <button type="button" class="submit" data-toggle="modal" data-target="#confirmModal">Submit</button>
        <button type="reset" class="cancel" onclick="window.location.href='edit_food.php'">Cancel</button>
</form>
    </div>
    <!-- Modal Confirmation -->
    <div class="modal" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ยืนยันการดำเนินการ</h5>
                    <button id="closeModal" class="close">&times;</button>
                </div>
                <div class="modal-body">
                    คุณต้องการแก้ไขข้อมูลนี้ใช่ไหม?
                </div>
                <div class="modal-footer">
                    <button id="cancel" class="btn-secondary">ยกเลิก</button>
                    <button id="confirm" class="btn-primary">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    document.getElementById('imageInput').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : 'ไม่มีไฟล์ที่เลือก';
    document.querySelector('.form__label').textContent = fileName;
    });
</script>
<script>
        const modal = document.getElementById('confirmModal');
        const form = document.querySelector('form');

        document.querySelector('.submit').onclick = function() {
            modal.classList.add('show'); // เพิ่มคลาส show เพื่อแสดง modal
        }

        document.getElementById('closeModal').onclick = function() {
            modal.classList.remove('show'); // ลบคลาส show เพื่อปิด modal
        }

        document.getElementById('cancel').onclick = function() {
            modal.classList.remove('show'); // ลบคลาส show เพื่อปิด modal
        }

        document.getElementById('confirm').onclick = function() {
            form.submit(); // ส่งฟอร์ม
        }
    </script>
</body>
</html>
