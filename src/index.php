<?php
require_once('config/config.php');

$banking = new \Allbanking\Banking();
$banking->login();
$banking->index();
echo json_encode($banking->message);
die;
