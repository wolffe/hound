<?php
session_start();
session_unset();
session_destroy();

include 'includes/functions.php';

php_redirect('index.php');
