<?php
check_not_login_redirect();

$order_code = anti_xss($_GET['order_code'] ?? null);
if (!$order_code) {
    msg_redirect('', 'error', 'Mã đơn hàng không hợp lệ.');
}

$invoice = $db->get_row("SELECT * FROM `invoices` WHERE `order_code` = '{$order_code}' AND `user_id` = '{$data_user['id']}'");
if (!$invoice) {
    msg_redirect('', 'error', 'Không tìm thấy đơn hàng.');
}

$bank_info = json_decode($invoice['bank_info'] ?? '[]', true) ?: [];
$imgSrc = '';
$excludePaygates = ['doithevip.com', 'card357.com', 'gachthe1s.com', 'thenhanh.com', 'thesieure.com', 'trumthe.com', 'momo', 'paypal'];
$showQR = false;

if ($invoice['paygate'] !== 'balance') {
    if (!in_array($invoice['paygate'], $excludePaygates, true)) {
        if (!empty($bank_info['bankAccount'])) {
            $qr = make_sepay_qr_with_logo($invoice['paygate'], $bank_info['bankAccount'], intval($invoice['pay_amount']), strval($invoice['order_code']), 'qronly', 0, BASE_URL($db->site('favicon')), 0.15, 6);
            $imgSrc = $qr['data_uri'] ?? $qr['url'];
            $showQR = true;
        }
    }
}
