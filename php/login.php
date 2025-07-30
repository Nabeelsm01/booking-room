<?php 
    session_start();
    include('connect.php');

    // ปิด output เพื่อไม่ให้แสดงผลตอน setup
    ob_start();
    include 'database.php';
    ob_end_clean();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="../css/login.css">
    
</head>
<body>
    <?php include('navbar_tap.php');?>
    <?php include('bubble_animation.php')?>
    <div class="login-container">
        <h2>Log In</h2>
        <form action="login_db.php" method="post">
            <?php if (isset($_SESSION['errors'])) : ?>
                <div class="errors">
                    <h3 style="font-size:16px; color:red;">
                        <?php 
                            echo $_SESSION['errors'];
                            unset($_SESSION['errors']);
                        ?>
                    </h3>
                </div>    
            <?php endif ?>
            <div class="form__group">
            <input type="text" name="email" class="form__input" placeholder="Email" required="" />
            <label for="name" class="form__label">Email</label>
            </div>
            
            <div class="form__group">
            <input type="password" name="password" class="form__input" placeholder="Password" required="" />
            <label for="name" class="form__label">Password</label>
            </div>
            <button type="submit" name="login_user">Log In</button>
        </form>
        <div class="separator">or</div>
        <a href="signin.php" class="create-sign-in">Create new account</a>
        <div class="forgot-password">Forgot Password?</div>
    </div>
</body>
</html>
