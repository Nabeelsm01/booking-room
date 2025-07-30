<?php 
session_start();
include('../connect.php');

// ดึงข้อมูลล่าสุดจากฐานข้อมูล
$sql = "SELECT * FROM food ORDER BY id_food DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // แสดงข้อความถ้าการ query ล้มเหลว
}

$food = mysqli_fetch_assoc($result);

if (!$food) {
    $_SESSION['errors'] = "ไม่พบข้อมูลห้องประชุม";
    header('Location: form_add_food.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Room Details</title>
    <link rel="stylesheet" href="/project end/css/form_addroom.css">
    <link rel="stylesheet" href="/project end/css/alert.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  
</head>
<body>
    <div class="container">
        <h2 class="text_confirm">ยืนยันข้อมูลอาหารและเครื่องดื่ม</h2>
        <div class="detail_text">
            <p><strong>ชื่อเมนู:</strong> <?php echo htmlspecialchars($food['name_food']); ?></p>
            <p><strong>รายละเอียด:</strong> <?php echo htmlspecialchars($food['detail_food']); ?></p>
            <p><strong>ประเภท:</strong> <?php echo htmlspecialchars($food['type_food']); ?></p>
            <p><strong>ราคา:</strong> <?php echo htmlspecialchars($food['price_food']); ?></p>
        </div>    
        <img src="<?php echo htmlspecialchars($food['image_food']); ?>" alt="food Image" style="max-width: 100%; height: auto;"><br>
        <button type="submit" class="submit" id="alertButton" >Submit</button>
        <button type="button" class="turn_but" onclick="deleteLatestRoom()">กลับไปเพิ่มห้องประชุมใหม่</button>
    </div>

    <script>
        document.getElementById('alertButton').addEventListener('click', function() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success btn-lg",
                    cancelButton: "btn btn-danger btn-lg"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                // title: "คุณแน่ใจหรือไม่?",
                title: "ยืนยันในการบันทึกข้อมูล?",
                text: "คุณจะไม่สามารถย้อนกลับได้!",
                icon: "info",
                showCancelButton: true,
                // confirmButtonText: "ใช่, ลบเลย!",
                // cancelButtonText: "ไม่, ยกเลิก!",
                cancelButtonText: "ยกเลิก!",
                confirmButtonText: "บันทึก!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        // title: "ถูกลบแล้ว!",
                        // text: "ไฟล์ของคุณถูกลบแล้ว.",
                        title: "บันทึกแล้ว!",
                        text: "ข้อมูลของคุณบันทึกสำเร็จ.",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // เปลี่ยนหน้าไปยัง URL ที่ต้องการ
                            window.location.href = "meeting_food.php"; // เปลี่ยนเป็น URL ที่ต้องการ
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        // title: "ถูกยกเลิก",
                        // text: "ไฟล์ในจินตนาการของคุณยังปลอดภัย :)",
                        title: "กลับมาแก้ไข",
                        icon: "error"
                    });
                }
            });
        });
    </script>
    <script>
    function deleteLatestRoom() {
        // ตรวจสอบว่าผู้ใช้ยืนยันการลบหรือไม่
        // if (confirm("คุณแน่ใจว่าต้องการลบข้อมูลห้องประชุมล่าสุด?")) {
            // ส่งคำขอไปยังสคริปต์ PHP ที่ทำการลบข้อมูล
            window.location.href = 'cancel_food.php'; // เปลี่ยนเส้นทางไปยังสคริปต์ลบ

    }
</script>

</body>
</html>
