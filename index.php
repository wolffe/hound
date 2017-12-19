<?php
error_reporting(0);
ini_set('display_errors', 0);

include 'config.php';
include 'libs/plugins.php';
include 'libs/hound.php';
include 'libs/template.class.php';

$hound = new hound($path, $urlwebsite);
$hound->start();
