<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $table = anti_xss(trim($_POST['table'] ?? ''));
    $column = anti_xss(trim($_POST['column'] ?? ''));
    $value = ($_POST['value'] ?? '') === '1' ? 1 : 0;

    $allowedTables = ['pack_service', 'groups_service', 'category_service', 'accounts', 'category_account', 'groups_account', 'menu_quick_acces', 'informations', 'menu', 'sliders', 'news', 'news_cats'];
    $allowedColumns = ['status', 'active', 'is_blocked'];

    if ($id > 0 && in_array($table, $allowedTables, true) && in_array($column, $allowedColumns, true)) {
        if ($db->site('status_demo') != 0) {
            die(json_encode(['status' => 'error', 'msg' => 'Trang web DEMO không thể thao tác']));
        }

        $check = $db->get_row("SELECT * FROM `{$table}` WHERE `id` = '{$id}' LIMIT 1");
        if (!$check) {
            die(json_encode(['status' => 'error', 'msg' => 'Dữ liệu không tồn tại.']));
        }

        $updated = $db->update($table, [$column => $value], "id = '{$id}'");
        if ($updated) {
            die(json_encode(['status' => 'success', 'msg' => 'Thay đổi trạng thái thành công']));
        }
    }

    die(json_encode(['status' => 'error', 'msg' => 'Thay đổi trạng thái không thành công']));
}
