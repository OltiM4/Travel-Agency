<?php
session_start();

// Fshij të gjitha të dhënat e sesionit
$_SESSION = [];

// Shkatërro sesionin
session_destroy();

// Ridrejto përdoruesin te faqja e login
header('Location: ../../../Models/web-design/pages/login.php');
exit();
?>
