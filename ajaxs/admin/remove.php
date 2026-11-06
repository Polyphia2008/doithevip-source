<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = intval($_POST['id'] ?? 0);
    $table  = anti_xss($_POST['table'] ?? '');
    $column = 'id';

    $allowedTables  = [
        'pack_service', 'groups_service', 'category_service',
        'flash_sale_products', 'flash_sales', 'coupons',
        'accounts', 'category_account', 'groups_account',
        'menu_quick_acces', 'orders', 'users', 'invoices',
        'bankings', 'seo', 'informations', 'menu',
        'sliders', 'news', 'news_cats'
    ];
    $allowedColumns = ['id'];

    if ($id > 0 && in_array($table, $allowedTables, true) && in_array($column, $allowedColumns, true)) {
        if ($db->site('status_demo') != 0) {
            die(json_encode(['status' => 'error', 'msg' => 'Trang web DEMO không thể thao tác xóa.']));
        }

        $check = $db->get_row("SELECT * FROM `{$table}` WHERE `id` = '{$id}' LIMIT 1");
        if (!$check) {
            die(json_encode(['status' => 'error', 'msg' => 'Dữ liệu cần xóa không tồn tại.']));
        }

        $deleted = $db->remove($table, "`id` = '{$id}'");
        if ($deleted) {
            die(json_encode(['status' => 'success', 'msg' => 'Xóa thành công.']));
        }
    }

    die(json_encode(['status' => 'error', 'msg' => 'Xóa không thành công.']));
}
