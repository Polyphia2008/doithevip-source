<?php
$title = "Trang Chủ";
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
load_client_data('home');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
?>
<?php
    include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/modules/doithe-slider.php';
    include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/modules/other/noti-sell.php';
    include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/modules/other/menu-quick-access.php';
    include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/modules/other/flash-sale.php';
    
    $order = $location_danhmuc_order;
    foreach ($order as $block) {
        switch ($block) {
            case 'muanick':
                include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/modules/categories/mua-nick.php';
                break;
            case 'dichvu':
                include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/modules/categories/dich-vu.php';
                break;
        }
    }
    
?>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>
