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
<?php include('bubble_animation.php')?>
    <div class="navbar">
        <?php include('navbar_tap.php'); ?>
        
    </div>
    <div class="main-container">
        <div class="container-sign">
            <h2>Create Account Customer</h2>
            <form action="registerdb.php" method="post">
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
                <input type="text" name="name_lastname" class="form__input" placeholder="Name and Last Name" required="" />
                <label for="name" class="form__label">Name and Last Name</label>
                </div>

                <div class="form__group">
                <select name="gender" class="form__select" required>
                    <option value="" disabled selected>Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <label for="name" class="form__label">gender</label>
                </div>

                <div class="form__group">
                <input type="text" name="address" class="form__input" placeholder="Address   subdistrict / district / province" required="" />
                <label for="name" class="form__label">Address   subdistrict / district / province</label>
                </div>

                <div class="form__group">
                <select name="status" class="form__select" required>
                    <option value="" disabled selected>Status</option>
                    <option value="student">นักเรียน/นักศึกษา</option>
                    <option value="workage">คนทำงาน</option>
                    <option value="other">ทั่วไป</option>
                </select>
                <label for="name" class="form__label">status</label>
                </div>

                <div class="form__group">
                <input type="email" name="email" class="form__input" placeholder="Email" required="" />
                <label for="name" class="form__label">Email</label>
                </div>

                <div class="form__group">
                <input type="password" name="password" class="form__input" placeholder="Password" required="" />
                <label for="name" class="form__label">Password</label>
                </div>

                <div class="form__group">
                <input type="password" name="confirm_password" class="form__input" placeholder="Confirm Password" required="" />
                <label for="name" class="form__label">Confirm Password</label>
                </div>
                <button type="submit" name="reg_user">Sign Up</button>
            </form>
            <a href="login.php" class="login-link">Already have an account? Log In</a>
        </div>
        <div class="button-group">
            <button onclick="window.location.href='signin.php'" style="background-color: #fff; color:#90a7dc;">ผู้ใช้ทั่วไป</button>
            <button onclick="window.location.href='register_tutor.php'" >ติวเตอร์</button>
            <button onclick="window.location.href='register_provider.php'" >ผู้ให้บริการ</button>
        </div>
    </div>
    
</body>
</html>
