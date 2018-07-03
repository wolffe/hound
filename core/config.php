<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Path/URI details
 */
define('HOUND_URL', 'https://getbutterfly.com/demo/hound'); // No trailing slash
define('HOUND_DIR', str_replace('/core', '', __DIR__));
define('HOUND_PATH', '/');
define('HOUND_PASS', '123456');

/**
 * Stop editing! Have fun!
 */
define('HOUND_VERSION', '0.8.4');

include 'plugins.php';
include 'hound.php';
include 'template.class.php';
