<?php 
    session_start();
    include('../connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account Provider</title>
    <link rel="stylesheet" href="/project end/css/form_addroom.css">
</head>
<body>
    <div class="container">
        <h2 class="text-content">เพิ่มข้อมูลอาหารและเครื่องดื่ม</h2>
        <form action="form_add_fooddb.php" method="post" enctype="multipart/form-data">
            <?php if (isset($_SESSION['errors'])) : ?>
                <div class="errors">
                    <h3>
                        <?php 
                            echo $_SESSION['errors'];
                            unset($_SESSION['errors']);
                        ?>
                    </h3>
                </div>    
            <?php endif ?>
            <div class="form__group">
            <input type="text" name="name_food" class="form__input" placeholder="ชื่ออาหารและเครื่องดื่ม" required="" />
            <label for="name" class="form__label">ชื่ออาหารและเครื่องดื่ม</label>
            </div>

            <div class="form__group">
            <textarea name="detail_food" class="form__input" placeholder="รายละเอียดอาหารและเครื่องดื่ม" required=""></textarea>
            <label for="name" class="form__label">รายละเอียดอาหารและเครื่องดื่ม</label>
            </div>

            <div class="form__group">
                <select name="type_food" class="form__select" required>
                    <option value="" disabled selected>เลือกประเภทเมนู</option>
                    <option value="อาหารหลัก">อาหารหลัก</option>
                    <option value="อาหารว่าง">อาหารว่าง</option>
                    <option value="เครื่องดื่ม">เครื่องดื่ม</option>
                </select>
                <label for="name" class="form__label">เลือกประเภทเมนู</label>
            </div>
            
            <div class="form__group">
            <input type="number" name="price_food" class="form__input" placeholder="ราคา" required="" />
            <label for="name" class="form__label">ราคา</label>
            </div>

            <div class="form__group">
            <img id="imagePreview" class="preview" src="#" alt="Image Preview" style="display: none;" /><br>
            <input type="file" name="image_food" class="form__input" accept="/project end/image/*" id="imageInput" required="" />
            <label for="name" class="form__label">prevew</label>
            </div>

            <button type="submit" class="submit" name="form_food">Submit</button>
            <button type="reset" class="cancel" onclick="window.location.href='meeting_food.php'">Cancel</></button>
        </form>
       
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
</script>
</body>
</html>
