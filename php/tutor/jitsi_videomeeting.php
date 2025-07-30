<?php 
    session_start();
    include('../connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Room Number</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        #meeting-container {
            width: 80%;
            height: 80%;
            margin-top: 20px;
        }
        .turn-btn {
            background-color: white; /* Green */
            color: #717FA1;
            padding: 13px 20px;
            text-align: center;
            text-decoration: none; 
            font-size: 16px;
            border-radius: 20px;
            border: 2px solid #90a7dc;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 20px;
            left: 30px;
        }

        .turn-btn:hover {
            background-color:#90a7dc;
            color:#fff;
            text-decoration: none;
            transition: 0.3s;
          
        }
    </style>
</head>
<body>
<a href="../home.php" class="turn-btn">ย้อนกลับ</a>
    <?php if (isset($_GET['room']) && !empty($_GET['room'])): ?>
        <div id="meeting-container">
            <iframe
                src="https://meet.jit.si/<?php echo htmlspecialchars($_GET['room']); ?>"
                width="100%" height="600px" 
                allow="camera; microphone; fullscreen; display-capture"
                style="border:0; border-radius:10px;">
            </iframe>
        </div>
    <?php endif; ?>
</body>
</html>
