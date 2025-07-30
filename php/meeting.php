<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Meeting</title>
    
    <style>
        body {      
            font-family: Arial, sans-serif;
            background: linear-gradient(158deg, rgba(184,201,245,0.5) 0%, rgba(181,232,248,0.5) 100%);
            overflow: auto;   
        }
        .bodytwo{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        .video-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 1200px;
        }

        video {
            width: 45%;
            border: 5px solid #b8c9f5;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        button {
            background-color: #b5e8f8;
            border: none;
            border-radius: 5px;
            color: #4a4a4a;
            margin: 5px;
            padding: 15px 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #b8c9f5;
            
        }
    </style>
</head>
<body>
    <?php include('navbar_tap.php');?>
    <div class="bodytwo">
        <h1>Video Meeting</h1>
        <div class="video-container">
            <video id="localVideo" autoplay playsinline></video>
            <video id="remoteVideo" autoplay playsinline></video>
        </div>
        <button id="startButton">Start Meeting</button>
        <button id="joinButton">Join Meeting</button>
        <button id="stopButton">Stop Meeting</button>
    </div>
    <script src="../javascript/script.js"></script>
</body>
</html>
