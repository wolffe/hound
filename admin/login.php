<?php
session_start();
include '../core/config.php';
include 'includes/functions.php';

$ps = filter_var(trim($_POST['ps']), FILTER_SANITIZE_STRING);

if ((string) $ps === HOUND_PASS) {
    $_SESSION['temppass'] = $ps;

    houndLogAccess();

    php_redirect('dashboard.php');
} else {
    php_redirect('index.php?err=1');
}
