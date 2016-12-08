<?php
include 'config.php';
include 'libs/hound.php';
include 'libs/template.class.php';

$hound = new hound($path, $urlwebsite);
$hound->start();