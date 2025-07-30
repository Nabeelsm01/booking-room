<?php 
    session_start();
    include('connect.php');

    $errors = array();

    if (isset($_POST['reg_tutor'])) {
        // $username = mysqli_real_escape_string($conn, $_POST['username']);
        $name_lastname_tutor = mysqli_real_escape_string($conn, $_POST['name_lastname_tutor']);
        $gender_tutor = mysqli_real_escape_string($conn, $_POST['gender_tutor']);
        $address_tutor = mysqli_real_escape_string($conn, $_POST['address_tutor']);
        $email_tutor = mysqli_real_escape_string($conn, $_POST['email_tutor']);
        $password_tutor = mysqli_real_escape_string($conn, $_POST['password_tutor']);
        $confirm_password_tutor = mysqli_real_escape_string($conn, $_POST['confirm_password_tutor']);

    //     if (empty($username)) {
    //         array_push($errors, "Username is required");
    //     }
    // }
    $user_check_query = "SELECT * FROM tutor WHERE email_tutor = '$email_tutor' ";
    $query = mysqli_query($conn, $user_check_query);
    $result = mysqli_fetch_assoc($query);

    if ($result) {
        // if ($result['email'] == $email) {
        //     array_push($errors, "Username already exists");
        // }
        if ($result['email_tutor'] == $email_tutor) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $password_tutor = md5($password_tutor);

        $sql = "INSERT INTO tutor (name_lastname_tutor, gender_tutor, address_tutor, email_tutor, password_tutor) VALUES (
        '$name_lastname_tutor', '$gender_tutor', '$address_tutor', '$email_tutor', '$password_tutor')";
        mysqli_query($conn, $sql);

        $_SESSION['email_tutor'] = $email_tutor;
        $_SESSION['success'] = "You are now logged in";
        header('location: home.php');
    } else {
        array_push($errors, "Email already exists");
        $_SESSION['errors'] = "อีเมลนี้มีบัญชีอยู่แล้วครับ";
        header("location: register_tutor.php");
    }
    
}