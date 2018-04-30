<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 * Path/URI details
 */
define('HOUND_URL', 'https://example.com'); // no trailing slash
define('HOUND_PATH', '/');
define('HOUND_PASS', '123456');

$password = '123456';
$path = '/';

// Stop editing! Have fun!
include 'plugins.php';
include 'hound.php';
include 'template.class.php';
