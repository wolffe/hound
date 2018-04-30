<?php
include 'core/config.php';

$hound = new hound($path, HOUND_URL);
$hound->start();
