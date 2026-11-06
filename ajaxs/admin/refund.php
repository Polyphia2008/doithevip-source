<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = intval($_POST['id'] ?? 0);
    $table  = anti_xss($_POST['table'] ?? '');
    $column = anti_xss($_POST['column'] ?? 'id');

    // Danh sách bảng và cột cho phép thao tác
    $allowedTables  = ['orders'];
    $allowedColumns = ['id'];

    if ($id > 0 && in_array($table, $allowedTables, true) && in_array($column, $allowedColumns, true)) {

        if ($db->site('status_demo') != 0) {
            die(json_encode(['status' => 'error', 'msg' => 'Trang web DEMO không thể thao tác hoàn tiền.']));
        }

        // Lấy thông tin đơn hàng
        $order = $db->get_row("SELECT * FROM `{$table}` WHERE `{$column}` = '{$id}' LIMIT 1");
        if (!$order) {
            die(json_encode(['status' => 'error', 'msg' => 'Đơn hàng không tồn tại.']));
        }

        // Nếu đơn đã refund
        if ($order['status'] == 4) {
            die(json_encode(['status' => 'error', 'msg' => 'Đơn hàng này đã được hoàn tiền trước đó.']));
        }

        // Nếu đơn có status = 4 thì không cho hoàn
        if ($order['status'] == 5) {
            die(json_encode(['status' => 'error', 'msg' => 'Đơn hàng có trạng thái khóa, không thể hoàn tiền.']));
        }

        $userId = intval($order['user_id']);
        $amount = floatval($order['amount']);

        // Thực hiện hoàn tiền
        $reason  = "Hoàn tiền đơn hàng #{$id}";
        $transid = generate_code_with_prefix('RF', 14);
        $isRefund = CongTien($userId, $amount, $reason, $transid);

        if ($isRefund) {
            // Update trạng thái đơn hàng thành refunded
            $db->update($table, ['status' => 4], "`{$column}` = '{$id}'");

            die(json_encode(['status' => 'success', 'msg' => "Đã hoàn " . format_number($amount) . "đ cho user #{$userId}."]));
        } else {
            die(json_encode(['status' => 'error', 'msg' => 'Không thể cộng tiền cho user.']));
        }
    }

    die(json_encode(['status' => 'error', 'msg' => 'Hoàn tiền không thành công.']));
}
