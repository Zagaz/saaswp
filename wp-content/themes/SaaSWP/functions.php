<?php
if (!defined('ABSPATH')) {
    exit;
}

// Boot theme modules
$saaswp_inc = __DIR__ . '/inc';
if (file_exists($saaswp_inc . '/custom-types.php')) {
    require_once $saaswp_inc . '/custom-types.php';
}
if (file_exists($saaswp_inc . '/mail.php')) {
    require_once $saaswp_inc . '/mail.php';
}
if (is_admin() && file_exists($saaswp_inc . '/mail-admin.php')) {
    require_once $saaswp_inc . '/mail-admin.php';
}
