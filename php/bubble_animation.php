<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  
    <title>`substitute(Filename('', 'Page Title'), '^.', '\u&', '')`</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background: linear-gradient(158deg, rgba(184,201,245,0.5) 0%, rgba(181,232,248,0.5) 100%);

}

.bubbles {
    position: fixed; /* ใช้ fixed แทน absolute */
    width: 100%;
    height: 100%;
    overflow: hidden;
    top: 0;
    left: 0;
    z-index: -1;
}

.bubble {
    position: absolute;
    bottom: -150px;
    width: 100px; /* ขยายขนาดฟองอากาศ */
    height: 100px;
    background: radial-gradient(circle at 10% 30%, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.001), rgba(255, 255, 255, 0.5)); /* แสงกระทบ */
    border-radius: 50%;
    animation: move 25s infinite;
    box-shadow: -10px -10px 20px rgba(255, 255, 255, 0.2), /* เงาสะท้อน */
                10px 10px 30px rgba(0, 0, 0, 0.1); /* เงาทางด้านล่าง */
    opacity: 0.5;
    backdrop-filter: blur(50px); /* เพิ่มความเบลอ */
}

.bubble:nth-child(2) {
    width: 150px;
    height: 150px;
    left: 20%;
    animation-duration: 25s;
}

.bubble:nth-child(3) {
    width: 80px;
    height: 80px;
    left: 35%;
    animation-duration: 18s;
}

.bubble:nth-child(4) {
    width: 200px;
    height: 200px;
    left: 50%;
    animation-duration: 30s;
}

.bubble:nth-child(5) {
    width: 120px;
    height: 120px;
    left: 65%;
    animation-duration: 22s;
}

.bubble:nth-child(6) {
    width: 90px;
    height: 90px;
    left: 80%;
    animation-duration: 15s;
}

/* ขนาดใหญ่ */
.bubble.large {
    width: 220px; /* ขยายขนาดฟองอากาศ */
    height: 220px;
    left: 5%;
    animation-duration: 45s; /* ระยะเวลาเคลื่อนที่ */
}

@keyframes move {
    0% {
        transform: translateY(0) translateX(0);
    }
    25% {
        transform: translateY(-200px) translateX(-100px);
    }
    50% {
        transform: translateY(-700px) translateX(250px);
    }
    75% {
        transform: translateY(-200px) translateX(-200px);
    }
    100% {
        transform: translateY(0) translateX(0);
    }
}
    </style>
  </head>
  <body>
  
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble large"></div> <!-- เพิ่มฟองอากาศขนาดใหญ่ -->
    </div>
  </body>
</html>