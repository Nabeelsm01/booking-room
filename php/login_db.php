<?php 
    session_start();
    include('connect.php');

    $errors = array();

    if (isset($_POST['login_user'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']); 
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Encrypt the password
        $password = md5($password);

        // Static credentials for admin (admin email and password)
        $admin_email = 'admin';
        $admin_password = 'admin123'; // This should be encrypted in real scenarios (md5, etc.)

        // Check if login is admin
        if ($email === $admin_email && $password === md5($admin_password)) {
            $_SESSION['name_admin'] = 'Admin';
            $_SESSION['id_admin'] = '1'; // You can set a static admin ID here
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = 'แอดมิน';
            $_SESSION['success'] = "You are now logged in as admin";
            header("location: /project end/php/dashboard/dashboard_admin.php"); // Redirect to admin page
        }
        else {
            // Check in user table
            $query_user = "SELECT id, email, name_lastname FROM user WHERE email = '$email' AND password = '$password'";
            $result_user = mysqli_query($conn, $query_user);

            // Check in tutor table
            $query_tutor = "SELECT id_tutor, email_tutor, name_lastname_tutor FROM tutor WHERE email_tutor = '$email' AND password_tutor = '$password'";
            $result_tutor = mysqli_query($conn, $query_tutor);
            
            // Check in provider table
            $query_provider = "SELECT id_provider, email_provider, name_provider FROM provider WHERE email_provider = '$email' AND password_provider = '$password'";
            $result_provider = mysqli_query($conn, $query_provider);

            if (mysqli_num_rows($result_user) == 1) {
                $row = mysqli_fetch_assoc($result_user);
                $_SESSION['name_lastname'] = $row['name_lastname'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = 'ผู้ใช้ทั่วไป';
                $_SESSION['success'] = "You are now logged in as user";
                header("location: home.php");
            } elseif (mysqli_num_rows($result_tutor) == 1) {
                $row = mysqli_fetch_assoc($result_tutor);
                $_SESSION['name_lastname_tutor'] = $row['name_lastname_tutor'];
                $_SESSION['id_tutor'] = $row['id_tutor'];
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = 'ติวเตอร์';
                $_SESSION['success'] = "You are now logged in as tutor";
                header("location: home.php");
            } elseif (mysqli_num_rows($result_provider) == 1) {
                $row = mysqli_fetch_assoc($result_provider);
                $_SESSION['name_provider'] = $row['name_provider'];
                $_SESSION['id_provider'] = $row['id_provider'];
                $_SESSION['email'] = $email;
                $_SESSION['user_type'] = 'ผู้ให้บริการ';
                $_SESSION['success'] = "You are now logged in as provider";
                header("location: home.php");
            } else {
                array_push($errors, "Wrong email/password combination");
                $_SESSION['errors'] = "Wrong email or password, try again";
                header("location: login.php");
            }
        }
    }
?>
