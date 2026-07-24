<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$auth->logout();

header("Location: " . getBaseUrl() . "/Auth/login.php");
exit();
