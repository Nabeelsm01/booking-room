<?php 
session_start();
include('../connect.php');

// ดึงข้อมูลล่าสุดจากฐานข้อมูล
$sql = "SELECT * FROM meetingroom ORDER BY id_room DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // แสดงข้อความถ้าการ query ล้มเหลว
}

$room = mysqli_fetch_assoc($result);

if (!$room) {
    $_SESSION['errors'] = "ไม่พบข้อมูลห้องประชุม";
    header('Location: meetingroom_add.php');
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
        <h2 class="text_confirm">ยืนยันข้อมูลห้องประชุม</h2>
        <div class="detail_text">
            <p><strong>ชื่อห้องประชุม:</strong> <?php echo htmlspecialchars($room['name_room']); ?></p>
            <p><strong>รายละเอียดห้องประชุม:</strong> <?php echo htmlspecialchars($room['detail_room']); ?></p>
            <p><strong>ความจุ:</strong> <?php echo htmlspecialchars($room['opacity_room']); ?></p> 
            <p><strong>ราคา:</strong> <?php echo htmlspecialchars($room['price_room']); ?></p>
            <p><strong>ประเภทห้องประชุม:</strong> <?php echo htmlspecialchars($room['room_type']); ?></p>
            <p><strong>หมายเลขห้อง:</strong> <?php echo htmlspecialchars($room['number_room']); ?></p>
            <p><strong>ที่อยู่:</strong> <?php echo htmlspecialchars($room['address_room']); ?></p>
        </div>    
        <img src="<?php echo htmlspecialchars($room['image_room']); ?>" alt="Room Image" style="max-width: 100%; height: auto;"><br>
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
                            window.location.href = "meetingroom_admin.php"; // เปลี่ยนเป็น URL ที่ต้องการ
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
            window.location.href = 'meetingroom_cancel.php'; // เปลี่ยนเส้นทางไปยังสคริปต์ลบ

    }
</script>

</body>
</html>
