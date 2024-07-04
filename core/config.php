<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Path/URI details
 */
define('HOUND_URL', 'https://getbutterfly.com/demo/hound'); // No trailing slash
define('HOUND_DIR', str_replace('/core', '', __DIR__));
define('HOUND_DIR_SINGLE', str_replace('core', '', basename(dirname(__DIR__))));
define('HOUND_PATH', '/');
define('HOUND_PASS', '123456');

define('HOUND_DB_PATH', __DIR__ . '/hound.db');

/**
 * Stop editing! Have fun!
 */
define('HOUND_VERSION', '0.8.4');

include 'db.php';
include 'plugins.php';
include 'hound.php';
include 'template.class.php';

include 'db-functions.php';
