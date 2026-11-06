<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

header('Content-Type: application/json; charset=utf-8');
$params = $_GET;

// === PHÂN LUỒNG MODULE ===
$type   = check_string($params['type']   ?? '');
$action = check_string($params['action'] ?? '');

switch ($type) {
    case 'wallet':
        include __DIR__ . '/wallet/index.php';
        break;

    default:
        echo json_encode(['status' => 'error', 'msg' => 'Module không tồn tại.']);
        break;
}
