<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(JsonMsg('error', "The requested resource does not support GET."));
}

// CSRF CHECK
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    exit(JsonMsg('error', 'CSRF token không hợp lệ!'));
}

$type = $_POST['type'] ?? '';
$modalId = '';

// MAP MODAL
$map = [
    'dang_nhap' => [
        'id' => 'dang_nhap_modal',
        'path' => realpath($_SERVER["DOCUMENT_ROOT"]) . '/ajaxs/client/modal/modals/dang-nhap-modal.php'
    ],
    'dang_ky' => [
        'id' => 'dang_ky_modal',
        'path' => realpath($_SERVER["DOCUMENT_ROOT"]) . '/ajaxs/client/modal/modals/dang-ky-modal.php'
    ],
    'phan_thuong' => [
        'id' => 'phanthuong_popup',
        'path' => realpath($_SERVER["DOCUMENT_ROOT"]) . '/ajaxs/client/modal/modals/phan-thuong-modal.php'
    ],
    'nap_the_cao' => [
        'id' => 'nap_the_cao',
        'path' => realpath($_SERVER["DOCUMENT_ROOT"]) . '/ajaxs/client/modal/modals/nap-the-cao.php'
    ],
    'nap_ngan_hang' => [
        'id' => 'nap_ngan_hang',
        'path' => realpath($_SERVER["DOCUMENT_ROOT"]) . '/ajaxs/client/modal/modals/nap-ngan-hang.php'
    ],
];

// TYPE KHÔNG TỒN TẠI
if (!isset($map[$type])) {
    exit(JsonMsg('error', 'Modal không tồn tại'));
}

// ĐÃ LOGIN → KHÔNG CHO MỞ LOGIN
if (($type === 'dang_nhap' || $type === 'dang_ky') && isset($_COOKIE['token'])) {
    exit(JsonMsg('error', 'Không thể mở modal'));
}


// BẮT HTML MODAL
ob_start();
require $map[$type]['path'];
$html = ob_get_clean();

// TRẢ JSON CHUẨN CHO JS
exit(json_encode([
    'status'    => 'success',
    'msg'       => 'Load modal thành công',
    'modal_id'  => $map[$type]['id'],
    'html'      => $html
]));
