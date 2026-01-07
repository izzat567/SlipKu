<?php 
session_start();

require '../config/connect.php';
include './functions/auth.php';
include './functions/user.php';


if(isset($_POST['login'])) {
    // Get form data

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Attempt login
        $result = loginUser($conn, "admin", $email, $password);
        
        if ($result['success']) {
            // Redirect to dashboard
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

else if(isset($_POST['logout'])) {

    logout();
}

else{

}

?>