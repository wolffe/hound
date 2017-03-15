<?php
session_start();
session_unset();
session_destroy();

php_redirect('index.php');
