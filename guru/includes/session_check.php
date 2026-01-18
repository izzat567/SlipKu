<?php
// includes/session_check.php
session_start();

function checkLogin() {
    if (!isset($_SESSION['guru_id'])) {
        header('Location: ../login.php');
        exit();
    }
}

function getGuruInfo() {
    if (isset($_SESSION['guru_id'])) {
        return [
            'id' => $_SESSION['guru_id'],
            'nama' => $_SESSION['guru_nama'],
            'email' => $_SESSION['guru_email']
        ];
    }
    return null;
}
?>