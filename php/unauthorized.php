<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(158deg, rgba(184,201,245,0.7) 0%, rgba(181,232,248,0.7) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .container h1 {
            font-size: 2em;
            margin-bottom: 30px;
            color: #2980b9;
        }

        .container p {
            font-size: 1.2em;
            margin-bottom: 50px;
        }

        .container a {
            display: inline-block;
            padding: 12px 25px;
            font-size: 1.1em;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .container a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- <h1>จำกัดการเข้าถึง!</h1> -->
        <h1>บัญชีคุณไม่สามารถเข้าหน้านี้ได้!</h1>
        <p>หน้านี้สงวนไว้สำหรับติวเตอร์และผู้ให้บริการเท่านั้น กรุณาเข้าสู่ระบบด้วยบัญชีที่เหมาะสมเพื่อเข้าถึงส่วนนี้.</p>
        <a href="/project end/php/login.php">login</a>
        <a href="/project end/php/home.php">กลับไปหน้าหลัก</a>
    </div>
</body>
</html>
