<?php 
session_start();

include '../config/connect.php';
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
            header("Location: ../admin/");
            exit();
        }
    }
}

if(isset($_POST['kemaskini_guru'])){
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telefon = mysqli_real_escape_string($conn, $_POST['no_telefon']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE guru SET 
            nama = '$nama',
            email = '$email',
            no_telefon = '$no_telefon',
            status = '$status'
            WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: ../admin/pengurusan-guru.php");
    } else {
        header("Location: ../admin/pengurusan-guru.php");
    }
}

if (isset($_POST['tambah_guru'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telefon = mysqli_real_escape_string($conn, $_POST['no_telefon']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash kata laluan

    $query = "INSERT INTO guru (nama, email, no_telefon, status, password) 
              VALUES ('$nama', '$email', '$no_telefon', '$status', '$password')";

    if (mysqli_query($conn, $query)) {
        header('Location: ../tambah-guru.php?created=success');
    } else {
        header('Location: ../tambah-guru.php?error=db');
    }
}

else if(isset($_POST['logout'])) {

    logout();
}

else{

}

?>