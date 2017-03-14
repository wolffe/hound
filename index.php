<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
include 'libs/hound.php';
include 'libs/template.class.php';

$hound = new hound($path, $urlwebsite);
$hound->start();
