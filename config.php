<?php
/**
 * Global Configuration & Base URL Helper
 */
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

function getBaseUrl() {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $dir = dirname($scriptName);
    
    // If script is in a subdirectory (Auth, Dashboard, etc), go up one level
    $subdirs = ['/Auth', '/Dashboard', '/Buku', '/Kategori', '/Peminjaman', '/Laporan', '/auth', '/dashboard', '/buku', '/kategori', '/peminjaman', '/laporan'];
    foreach ($subdirs as $sd) {
        if (substr_compare($dir, $sd, -strlen($sd)) === 0) {
            $dir = substr($dir, 0, -strlen($sd));
            break;
        }
    }
    
    $dir = rtrim($dir, '/');
    return $dir;
}
