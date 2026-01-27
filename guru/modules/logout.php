<?php
session_start();
session_destroy();
header('Location: login-guru.php');
exit();
?>