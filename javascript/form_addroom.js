<?php
session_start();
$errors = array(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_provider = $_POST['name_provider'];
    $number_provider = $_POST['number_provider'];
    $address_provider = $_POST['address_provider'];
    $email_provider = $_POST['email_provider'];
    $password_provider = $_POST['password_provider'];
    $confirm_password_provider = $_POST['confirm_password_provider'];
    $image_provider = $_FILES['image_provider'];

    // Validate file upload
    if ($image_provider['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image_provider["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($image_provider["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($image_provider["tmp_name"], $target_file)) {
                // File uploaded successfully
            } else {
                $errors[] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    } else {
        $errors[] = "No file uploaded or upload error.";
    }

    if (empty($errors)) {
        // Save the form data and image path into the database
        // Database connection and insertion code here
    } else {
        $_SESSION['errors'] = implode('<br>', $errors);
        header('location: register_provider.php');
        exit();
    }
}
?>
