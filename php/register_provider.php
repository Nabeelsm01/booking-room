<?php 
    session_start();
    include('connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sign Up</title>

    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>

    <div class="navbar">
        <?php include('navbar_tap.php'); ?>
        
    </div>
    <div class="container-sign">
        <h2>Create Account Provider</h2>
        <form action="register_providerdb.php" method="post">
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
            <!-- <input type="text" name="username" placeholder="Username" required><br> -->
            <div class="form__group">
            <input type="text" name="name_provider" class="form__input" placeholder="Name Provider" required="" />
            <label for="name" class="form__label">Name Provider</label>
            </div>

            <div class="form__group">
            <input type="text" name="number_provider" class="form__input" placeholder="Number" required="" />
            <label for="name" class="form__label">Number</label>       
            </div>
            
            <div class="form__group">
            <input type="text" name="address_provider" class="form__input" placeholder="Address   subdistrict / district / province" required="" />
            <label for="name" class="form__label">Address   subdistrict / district / province</label>
            </div>

            <div class="form__group">
            <input type="email" name="email_provider" class="form__input" placeholder="Email" required="" />
            <label for="name" class="form__label">Email</label>
            </div>

            <div class="form__group">
            <input type="password" name="password_provider" class="form__input" placeholder="Password" required="" />
            <label for="name" class="form__label">Password</label>
            </div>

            <div class="form__group">
            <input type="password" name="confirm_password_provider" class="form__input" placeholder="Confirm Password" required="" />
            <label for="name" class="form__label">Confirm Password</label>
            </div>
            <button type="submit" name="reg_provider">Sign Up</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Log In</a>
    </div>
    <div class="button-group">
            <button onclick="window.location.href='signin.php'">ผู้ใช้ทั่วไป</button>
            <button onclick="window.location.href='register_tutor.php'">ติวเตอร์</button>
            <button onclick="window.location.href='register_provider.php'" style="background-color: #fff; color:#90a7dc;">ผู้ให้บริการ</button>
        </div>
        <?php include('bubble_animation.php')?>
</body>
</html>
