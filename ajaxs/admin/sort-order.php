<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true);

    $table = anti_xss($payload['table'] ?? '');
    $column = anti_xss($payload['column'] ?? '');
    $data = $payload['data'] ?? [];

    if (empty($table) || empty($column) || !is_array($data)) {
        die(json_encode(['status' => 'error', 'msg' => 'Dữ liệu không hợp lệ']));
    }

    $allowed_tables = ['pack_service', 'groups_service', 'category_service', 'category_account', 'groups_account', 'menu', 'menu_quick_acces', 'sliders'];
    $allowed_columns = ['sort', 'sort_order'];

    if (!in_array($table, $allowed_tables) || !in_array($column, $allowed_columns)) {
        die(json_encode(['status' => 'error', 'msg' => 'Không được phép thay đổi bảng hoặc cột này']));
    }
    if ($db->site('status_demo') != 0) {
        die(json_encode(['status' => 'error', 'msg' => 'Trang web DEMO không thể thao tác']));
    }
    foreach ($data as $item) {
        $id = intval($item['id']);
        $position = intval($item['position']);

        if ($id > 0) {
            $update = $db->update($table, [$column => $position], "id = '{$id}'");

            if (!$update) {
                die(json_encode(['status' => 'error', 'msg' => "Không thể cập nhật ID {$id}"]));
            }
        }
    }

    die(json_encode(['status' => 'success', 'msg' => 'Sắp xếp thành công']));
}
