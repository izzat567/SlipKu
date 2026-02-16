<?php 
session_start();

include '../config/connect.php';
include './functions/auth.php';
include './functions/user.php';


if(isset($_POST['create'])) {
    // Get form data

    $id_kelas = $_POST['id_kelas'];
    $nama = $_POST['nama'];
    $no_kp = $_POST['no_kp'];
    $jantina = $_POST['jantina'];
    
    // Validate inputs
    $result = createPelajar($conn, $id_kelas, $nama, $no_kp, $jantina);
    
    if ($result['success']) {
        // Redirect to dashboard
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        $error = $result['message'];
        // header("Location: ../admin/");
        exit();
    }
}

else if(isset($_POST['logout'])) {

    logout();
}

else{

}

?>