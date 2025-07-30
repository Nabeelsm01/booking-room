<?php
session_start();
include('../connect.php');

// ตรวจสอบว่ามีการส่ง `id` มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลห้องประชุมตาม `id`
    $sql = "SELECT * FROM meetingroom WHERE id_room = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลห้องประชุมนี้";
        exit();
    }
} else {
    echo "ไม่พบ ID ของห้องประชุม";
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
        <h2 class="text-content">แก้ไขข้อมูลห้องประชุมของคุณ</h2>
        <form action="meetingroom_update.php" method="post" enctype="multipart/form-data">
        <!-- ฟิลด์ที่มีอยู่แล้ว -->
        <input type="hidden" name="id_room" value="<?php echo $room['id_room']; ?>">

        <div class="form__group">
        <input type="text" name="name_room" class="form__input" placeholder="Name Meetingroom" value="<?php echo $room['name_room']; ?>" required="" />
        <label for="name" class="form__label">Name Meetingroom</label>
        </div>

        <div class="form__group">
        <textarea name="detail_room" class="form__input" placeholder="Detail Meetingroom" value="" required=""><?php echo $room['detail_room']; ?></textarea>
        <label for="name" class="form__label">Detail Meetingroom</label>
        </div>
        
        <div class="form__group">
        <input type="number" name="opacity_room" class="form__input" placeholder="Opacity" value="<?php echo $room['opacity_room']; ?>" required="" />
        <label for="name" class="form__label">Opacity</label>
        </div>

        <div class="form__group">
        <input type="number" name="price_room" class="form__input" placeholder="Price" value="<?php echo $room['price_room']; ?>" required="" />
        <label for="name" class="form__label">Price</label>
        </div>   

        <div class="form__group">
                <select name="room_type" class="form__select" required="">
                    <option value="" disabled selected >Select Meetingroom Type</option>
                    <option value="ห้องประชุมขนาดใหญ่ -Large" <?php echo ($room['room_type'] == 'ห้องประชุมขนาดใหญ่ -Large') ? 'selected' : ''; ?>>ห้องประชุมขนาดใหญ่ -Large</option>
                    <option value="ห้องประชุมขนาดเล็ก-กลาง -Medium" <?php echo ($room['room_type'] == 'ห้องประชุมขนาดเล็ก-กลาง -Medium') ? 'selected' : ''; ?>>ห้องประชุมขนาดเล็ก-กลาง -Medium</option>
                    <option value="ห้องประชุมธุรกิจ -Business" <?php echo ($room['room_type'] == 'ห้องประชุมธุรกิจ -Business') ? 'selected' : ''; ?>>ห้องประชุมธุรกิจ -Business</option>
                </select>
                <label for="name" class="form__label">Select Meetingroom Type</label>
            </div>
        
        <div class="form__group">
        <input type="text" name="number_room" class="form__input" placeholder="Number" value="<?php echo $room['number_room']; ?>" required="" />
        <label for="name" class="form__label">Number</label>
        </div>

        <div class="form__group">
        <img id="imagePreview" class="preview" src="<?php echo $room['image_room']; ?>" alt="Image Preview" /><br>
        <input type="file" name="image_room" class="form__input" accept="image/*" id="imageInput"  />
        <label for="name" class="form__label">preview</label>
        </div>

        <div class="form__group">
        <input type="text" name="address_room" class="form__input" placeholder="Address (subdistrict / district / province)" value="<?php echo $room['address_room']; ?>" required="" />
        <label for="name" class="form__label">Address (subdistrict / district / province)</label>
        </div>

        <!-- <button type="submit" class="submit">Submit</button> -->
        <button type="button" class="submit" data-toggle="modal" data-target="#confirmModal">Submit</button>
        <button type="reset" class="cancel" onclick="window.location.href='meetingroom_admin.php'">Cancel</button>
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
