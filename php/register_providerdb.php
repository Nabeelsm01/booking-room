<?php 
    session_start();
    include('connect.php');

    $errors = array();

    if (isset($_POST['reg_provider'])) {
        // $username = mysqli_real_escape_string($conn, $_POST['username']);
        $name_provider = mysqli_real_escape_string($conn, $_POST['name_provider']);
        $number_provider = mysqli_real_escape_string($conn, $_POST['number_provider']);
        $address_provider = mysqli_real_escape_string($conn, $_POST['address_provider']);
        $email_provider = mysqli_real_escape_string($conn, $_POST['email_provider']);
        $password_provider = mysqli_real_escape_string($conn, $_POST['password_provider']);
        $confirm_password_provider = mysqli_real_escape_string($conn, $_POST['confirm_password_provider']);

    //     if (empty($username)) {
    //         array_push($errors, "Username is required");
    //     }
    // }
    $user_check_query = "SELECT * FROM provider WHERE email_provider = '$email_provider' ";
    $query = mysqli_query($conn, $user_check_query);
    $result = mysqli_fetch_assoc($query);

    if ($result) {
        // if ($result['email'] == $email) {
        //     array_push($errors, "Username already exists");
        // }
        if ($result['email_provider'] == $email_provider) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $password_provider = md5($password_provider);

        $sql = "INSERT INTO provider (name_provider, number_provider, address_provider, email_provider, password_provider) VALUES (
        '$name_provider', '$number_provider', '$address_provider', '$email_provider', '$password_provider')";
        mysqli_query($conn, $sql);

        $_SESSION['email_provider'] = $email_provider;
        $_SESSION['success'] = "You are now logged in";
        header('location: home.php');
    } else {
        array_push($errors, "Email already exists");
        $_SESSION['errors'] = "อีเมลนี้มีบัญชีอยู่แล้วครับ";
        header("location: register_provider.php");
    }
    
}