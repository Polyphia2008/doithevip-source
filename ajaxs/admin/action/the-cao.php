<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if (isset($_POST['submit']) && $_POST['submit'] === 'gui_lai_the') {

    // CSRF
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        msg_redirect('admin/wallet/invoices', 'error', 'CSRF token không hợp lệ.');
    }

    if ($db->site("status_demo") == 1) {
        msg_redirect('admin/wallet/invoices', 'error', 'Đây là trang web demo, không thể thực hiện chức năng này!');
    }

    if (!check_login()) {
        msg_redirect('admin/wallet/invoices', 'error', 'Vui lòng đăng nhập!');
    }

    $id = intval($_POST['id'] ?? 0);
    if (empty($id)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu ID!');
    }

    $row = $db->get_row("
        SELECT * FROM `invoices`
        WHERE `id` = {$id}
        AND `card_info` IS NOT NULL
        AND `card_info` <> ''
    ");

    if (!$row) {
        msg_redirect('admin/wallet/invoices', 'error', 'Không tìm thấy hóa đơn trong hệ thống!');
    }

    if ($row['status'] == 'cancel' || $row['status'] == 'paid') {
        msg_redirect('admin/wallet/invoices', 'error', 'Thẻ này đã được xử lý trước đó rồi');
    }

    $payment_method = anti_xss($row['paygate']);
    $pay_amount     = anti_xss($row['pay_amount']);

    $pin  = '';
    $seri = '';

    if (!empty($row['card_info'])) {
        $tmp  = explode('|', $row['card_info'], 2);
        $pin  = $tmp[0] ?? '';
        $seri = $tmp[1] ?? '';
    }

    $request_id = anti_xss($row['request_id']);

    if (empty($payment_method)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu loại thẻ!');
    }

    if (empty($pay_amount)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu mệnh giá!');
    }

    if (empty($seri)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu seri thẻ!');
    }

    if (empty($pin)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu mã thẻ!');
    }

    if (empty($request_id)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu request_id!');
    }

    $checkFormat = check_format_card($payment_method, $seri, $pin);
    if ($checkFormat['status'] != true) {
        msg_redirect('admin/wallet/invoices', 'error', $checkFormat['msg']);
    }

    $data_napthe = napthe($payment_method, $pay_amount, $seri, $pin, $request_id);

    // Tính phí
    $feePercent = (float) ($db->site('fees_napthecao') ?? 0);
    $fees       = $pay_amount * ($feePercent / 100);
    $net_amount = $pay_amount - $fees;

    $invoiceData = [
        'pay_amount'     => $pay_amount,
        'updated_at'     => gettime(),
    ];
    if (!isset($data_napthe['status'])) {
        msg_redirect('admin/wallet/invoices', 'error', 'Đã xảy ra lỗi khi gửi dữ liệu!');
    }
    if ($data_napthe['status'] == '99') {
        $invoiceData['net_amount'] = $net_amount;
        $invoiceData['status'] = 'unpaid';
        $invoiceData['status_service'] ='100';
        $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
        if (!$UPDATE) {
            msg_redirect('admin/wallet/invoices', 'error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin');
        }
        insert_log($data_user['id'], 'Thực hiện gửi lại thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect("admin/wallet/invoices", 'success', 'Đã gửi thẻ lên hệ thống, vui lòng chờ xử lý.');
    } elseif ($data_napthe['status'] == 2 || $data_napthe['status'] == 3) {
        $invoiceData['net_amount'] = 0;
        $invoiceData['status'] = 'cancel';
        $invoiceData['status_service'] = '3';
        $db->update("invoices", $invoiceData, "`id` = {$id}");
        insert_log($data_user['id'], 'Thực hiện gửi lại thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect('admin/wallet/invoices', 'error', 'Thẻ lỗi');
    } elseif ($data_napthe['status'] == 4) {
        msg_redirect('admin/wallet/invoices', 'error', 'Hệ thống đang bảo trì, vui lòng thử lại sau');
    } elseif ($data_napthe['status'] == 100) {
        msg_redirect('admin/wallet/invoices', 'error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin');

    } else {
        $invoiceData['net_amount'] = $net_amount;
        $invoiceData['status'] = 'unpaid';
        $invoiceData['status_service'] = '100';
        $db->update("invoices", $invoiceData, "`id` = {$id}");
        insert_log($data_user['id'], 'Thực hiện gửi lại thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect('admin/wallet/invoices', 'error', $data_napthe['message'] ?? 'Nạp thẻ thất bại');
    }
}
if (isset($_POST['submit']) && $_POST['submit'] === 'lay_trang_thai_the') {
    
    // CSRF
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        msg_redirect('admin/wallet/invoices', 'error', 'CSRF token không hợp lệ.');
    }

    if ($db->site("status_demo") == 1) {
        msg_redirect('admin/wallet/invoices', 'error', 'Đây là trang web demo, không thể thực hiện chức năng này!');
    }

    if (!check_login()) {
        msg_redirect('admin/wallet/invoices', 'error', 'Vui lòng đăng nhập!');
    }

    $id = intval($_POST['id'] ?? 0);
    if (empty($id)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu ID!');
    }

    $row = $db->get_row("
        SELECT * FROM `invoices`
        WHERE `id` = {$id}
        AND `card_info` IS NOT NULL
        AND `card_info` <> ''
    ");
    if (!$row) {
        msg_redirect('admin/wallet/invoices', 'error', 'Không tìm thấy hóa đơn trong hệ thống!');
    }

    if ($row['status'] == 'cancel' || $row['status'] == 'paid') {
        msg_redirect('admin/wallet/invoices', 'error', 'Thẻ này đã được xử lý trước đó rồi');
    }

    $payment_method = anti_xss($row['paygate']);
    $pay_amount     = anti_xss($row['pay_amount']);

    $pin  = '';
    $seri = '';

    if (!empty($row['card_info'])) {
        $tmp  = explode('|', $row['card_info'], 2);
        $pin  = $tmp[0] ?? '';
        $seri = $tmp[1] ?? '';
    }

    $request_id = anti_xss($row['request_id']);

    if (empty($payment_method)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu loại thẻ!');
    }

    if (empty($pay_amount)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu mệnh giá!');
    }

    if (empty($seri)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu seri thẻ!');
    }

    if (empty($pin)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu mã thẻ!');
    }

    if (empty($request_id)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu request_id!');
    }

    $checkFormat = check_format_card($payment_method, $seri, $pin);
    if ($checkFormat['status'] != true) {
        msg_redirect('admin/wallet/invoices', 'error', $checkFormat['msg']);
    }

    $data_napthe = laytrangthai_thecao($payment_method, $pay_amount, $seri, $pin, $request_id);

    // Tính phí
    $feePercent = (float) ($db->site('fees_napthecao') ?? 0);
    $fees       = $pay_amount * ($feePercent / 100);
    $net_amount = $pay_amount - $fees;

    $invoiceData = [
        'pay_amount'     => $pay_amount,
        'updated_at'     => gettime(),
    ];
    if (!isset($data_napthe['status'])) {
        msg_redirect('admin/wallet/invoices', 'error', 'Đã xảy ra lỗi khi gửi dữ liệu!');
    }

    if ($data_napthe['status'] == '99') {
        $invoiceData['net_amount'] = $net_amount;
        $invoiceData['status'] = 'unpaid';
        $invoiceData['admin_note'] = 'Thẻ cào đang được xử lý';
        $invoiceData['client_note'] = 'Thẻ cào đang được xử lý';
        $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
        if (!$UPDATE) {
            msg_redirect('admin/wallet/invoices', 'error', 'Lấy trạng thái thất bại, vui lòng liên hệ Pino');
        }
        insert_log($data_user['id'], 'Thực hiện lấy trạng thái thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect("admin/wallet/invoices", 'success', 'Lấy trạng thái thành công.');
    }elseif ($data_napthe['status'] == 2 || $data_napthe['status'] == 3) {
        $invoiceData['net_amount'] = 0;
        $invoiceData['status'] = 'cancel';
        $invoiceData['status_service'] = '3';
        $invoiceData['admin_note'] = 'Thẻ cào không hợp lệ hoặc đã được sử dụng';
        $invoiceData['client_note'] = 'Thẻ cào không hợp lệ hoặc đã được sử dụng';
        $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
        if (!$UPDATE) {
            msg_redirect('admin/wallet/invoices', 'error', 'Lấy trạng thái thất bại, vui lòng liên hệ Pino');
        }
        insert_log($data_user['id'], 'Thực hiện lấy trạng thái thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect("admin/wallet/invoices", 'success', 'Lấy trạng thái thành công.');
    } elseif ($data_napthe['status'] == 4) {
        msg_redirect("admin/wallet/invoices", 'success', 'Hệ thống lấy trạng thái, vui lòng thử lại sau.');
    } elseif ($data_napthe['status'] == 100) {
        $invoiceData['net_amount'] = 0;
        $invoiceData['status'] = 'cancel';
        $invoiceData['status_service'] = '3';
        $invoiceData['admin_note'] = $data_napthe['message'];
        $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
        if (!$UPDATE) {
            msg_redirect('admin/wallet/invoices', 'error', 'Lấy trạng thái thất bại, vui lòng liên hệ Pino');
        }
        insert_log($data_user['id'], 'Thực hiện lấy trạng thái thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect("admin/wallet/invoices", 'success', 'Lấy trạng thái thất bại, có lí do đi kèm.');
    }elseif ($data_napthe['status'] == 1){
        $invoiceData['net_amount'] = $net_amount;
        $invoiceData['status'] = 'paid';
        $invoiceData['admin_note'] = 'Thẻ cào hợp lệ';
        $invoiceData['client_note'] = 'Thẻ cào hợp lệ';
        $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
        if (!$UPDATE) {
            msg_redirect('admin/wallet/invoices', 'error', 'Lấy trạng thái thất bại, vui lòng liên hệ Pino');
        }
        insert_log($data_user['id'], 'Thực hiện lấy trạng thái thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        CongTien($row['user_id'], $net_amount, $row['description'], $request_id);
        $db->cong("users", "total_money", $net_amount, " `id` = '{$row['user_id']}' ");
        msg_redirect("admin/wallet/invoices", 'success', 'Lấy trạng thái thành công.');
    }else{
        $invoiceData['net_amount'] = 0;
        $invoiceData['status'] = 'cancel';
        $invoiceData['status_service'] = '3';
        $invoiceData['admin_note'] = $data_napthe['message'];
        $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
        if (!$UPDATE) {
            msg_redirect('admin/wallet/invoices', 'error', 'Lấy trạng thái thất bại, vui lòng liên hệ Pino');
        }
        insert_log($data_user['id'], 'Thực hiện lấy trạng thái thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
        $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        insert_cards_log($id, $log);
        msg_redirect("admin/wallet/invoices", 'success', 'Lấy trạng thái thành công.');
    }
}
if (isset($_POST['submit']) && $_POST['submit'] === 'phuc_hoi_the') {
    
    // CSRF
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        msg_redirect('admin/wallet/invoices', 'error', 'CSRF token không hợp lệ.');
    }

    if ($db->site("status_demo") == 1) {
        msg_redirect('admin/wallet/invoices', 'error', 'Đây là trang web demo, không thể thực hiện chức năng này!');
    }

    if (!check_login()) {
        msg_redirect('admin/wallet/invoices', 'error', 'Vui lòng đăng nhập!');
    }

    $id = intval($_POST['id'] ?? 0);
    if (empty($id)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu ID!');
    }

    $row = $db->get_row("
        SELECT * FROM `invoices`
        WHERE `id` = {$id}
        AND `card_info` IS NOT NULL
        AND `card_info` <> ''
    ");
    if (!$row) {
        msg_redirect('admin/wallet/invoices', 'error', 'Không tìm thấy hóa đơn trong hệ thống!');
    }

    if ($row['status'] == 'unpaid') {
        msg_redirect('admin/wallet/invoices', 'error', 'Thẻ này đang trong tình trạng chờ xử lý, không thể sử dụng thao tác này!');
    }
    $payment_method = anti_xss($row['paygate']);
    // Tính phí
    $feePercent = (float) ($db->site('fees_napthecao') ?? 0);
    $pay_amount     = anti_xss($row['pay_amount']);
    $fees       = $pay_amount * ($feePercent / 100);
    $net_amount = $pay_amount - $fees;

    $invoiceData = [
        'net_amount' => $net_amount,
        'status'     => 'unpaid',
        'client_note'     => "Thẻ cào đang được xử lý",
        'updated_at'     => gettime(),
    ];
    $UPDATE = $db->update("invoices", $invoiceData, "`id` = {$id}");
    if (!$UPDATE) {
        msg_redirect('admin/wallet/invoices', 'error', 'Phục hồi thất bại, vui lòng liên hệ Pino');
    }
    insert_log($data_user['id'], 'Thực hiện phục hồi thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ');
    msg_redirect("admin/wallet/invoices", 'success', 'Phục hồi thành công.');
}