<?php 
    session_start();
    include('connect.php');

    $errors = array();

    if (isset($_POST['reg_user'])) {
        // $username = mysqli_real_escape_string($conn, $_POST['username']);
        $name_lastname = mysqli_real_escape_string($conn, $_POST['name_lastname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    //     if (empty($username)) {
    //         array_push($errors, "Username is required");
    //     }
    // }

    $user_check_query = "SELECT * FROM user WHERE email = '$email' ";
    $query = mysqli_query($conn, $user_check_query);
    $result = mysqli_fetch_assoc($query);

    if ($result) {
        // if ($result['email'] == $email) {
        //     array_push($errors, "Username already exists");
        // }
        if ($result['email'] == $email) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $password = md5($password);

        $sql = "INSERT INTO user (name_lastname, gender, address, status, email, password) VALUES (
        '$name_lastname', '$gender', '$address', '$status', '$email', '$password')";
        mysqli_query($conn, $sql);

        $_SESSION['email'] = $email;
        $_SESSION['success'] = "You are now logged in";
        header('location: home.php');
    } else {
        array_push($errors, "Email already exists");
        $_SESSION['errors'] = "อีเมลนี้มีบัญชีอยู่แล้วครับ";
        header("location: signin.php");
    }
    
}