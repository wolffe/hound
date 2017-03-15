<?php
session_start();
include '../config.php';
include 'includes/functions.php';

$ps = trim(filter_var($_POST['ps'], FILTER_SANITIZE_STRING));

if((string) $ps === (string) $password) {
    $_SESSION['temppass'] = $ps;
    php_redirect('dashboard.php');
} else {
    php_redirect('index.php?err=1');
}
