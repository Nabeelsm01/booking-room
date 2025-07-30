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
        <h2 class="text-content">เพิ่มข้อมูลห้องประชุมของคุณ</h2>
        <form action="form_add_roomdb.php" method="post" enctype="multipart/form-data">
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
            <input type="text" name="name_room" class="form__input" placeholder="Name Meetingroom" required="" />
            <label for="name" class="form__label">Name Meetingroom</label>
            </div>

            <div class="form__group">
            <textarea name="detail_room" class="form__input" placeholder="Detail Meetingroom" required=""></textarea>
            <label for="name" class="form__label">Detail Meetingroom</label>
            </div>
            
            <div class="form__group">
            <input type="number" name="opacity_room" class="form__input" placeholder="Opacity" required="" />
            <label for="name" class="form__label">Opacity</label>
            </div>

            <div class="form__group">
            <input type="number" name="price_room" class="form__input" placeholder="Price" required="" />
            <label for="name" class="form__label">Price</label>
            </div>

            <div class="form__group">
                <select name="room_type" class="form__select" required>
                    <option value="" disabled selected>Select Meetingroom Type</option>
                    <option value="ห้องประชุมขนาดใหญ่ -Large">ห้องประชุมขนาดใหญ่ -Large</option>
                    <option value="ห้องประชุมขนาดเล็ก-กลาง -Medium">ห้องประชุมขนาดเล็ก-กลาง -Medium</option>
                    <option value="ห้องประชุมธุรกิจ -Business">ห้องประชุมธุรกิจ -Business</option>
                </select>
                <label for="name" class="form__label">Select Meetingroom Type</label>
            </div>
            
            <div class="form__group">
            <input type="text" name="number_room" class="form__input" placeholder="Number" required="" />
            <label for="name" class="form__label">Number</label>
            </div>
            
            <div class="form__group">
            <img id="imagePreview" class="preview" src="#" alt="Image Preview" style="display: none;" /><br>
            <input type="file" name="image_room" class="form__input" accept="/project end/image/*" id="imageInput" required="" />
            <label for="name" class="form__label">prevew</label>
            </div>

            <div class="form__group">
            <input type="text" name="address_room" class="form__input" placeholder="Address (subdistrict / district / province)" required="" />
            <label for="name" class="form__label">Address (subdistrict / district / province)</label>
            </div>

            <button type="submit" class="submit" name="form_room">Submit</button>
            <button type="reset" class="cancel" onclick="window.location.href='meetingroom.php'">Cancel</></button>
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
