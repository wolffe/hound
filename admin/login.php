<?php
session_start();
include '../config.php';
include 'includes/functions.php';

$ps = trim(filter_var($_POST['ps'], FILTER_SANITIZE_STRING));

if((string) $ps === (string) $password) {
    $_SESSION['temppass'] = $ps;
    php_redirect('dashboard.php');
//  echo"<script language=javascript>";
//  echo"document.location.href='dashboard.php'";
//  echo"</script>";
} else {
    php_redirect('index.php?err=1');
//  echo"<script language=javascript>";
//  echo"document.location.href='index.php?err=1'";
//  echo"</script>";
}