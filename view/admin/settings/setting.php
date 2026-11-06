<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Cấu hình hệ thống";
load_admin_data('settings/setting');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/admin/settings/config.php';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');

?>
