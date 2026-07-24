<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Auth.php';

if (Auth::check()) {
    header("Location: " . getBaseUrl() . "/Dashboard/dashboard.php");
} else {
    header("Location: " . getBaseUrl() . "/Auth/login.php");
}
exit();
