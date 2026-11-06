<?php
if (isset($_POST['saveBanking'])) {
    check_demo_mode();

    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'typeApi'      => anti_xss($_POST['typeApi'] ?? ''),
        'bankName'     => anti_xss($_POST['bankName'] ?? ''),
        'textNameBank' => anti_xss($_POST['textNameBank'] ?? ''),
        'apiToken'     => anti_xss($_POST['apiToken'] ?? ''),
        'bankAccount'  => anti_xss($_POST['bankAccount'] ?? ''),
        'bankUsername' => anti_xss($_POST['bankUsername'] ?? ''),
        'bankPassword' => anti_xss($_POST['bankPassword'] ?? ''),
        'bankOwner'    => anti_xss($_POST['bankOwner'] ?? ''),
        'walletCookie' => anti_xss($_POST['walletCookie'] ?? ''),
        'fixed_fee'    => (int)($_POST['fixed_fee'] ?? 0),
        'fees_percent' => (int)($_POST['fees_percent'] ?? 0),
        'invoice_pay'  => (int)($_POST['invoice_pay'] ?? 1),
    ];

    if ($id > 0) {
        $db->update('bankings', $data, "id = '{$id}'");
        msg_redirect('reload', 'success', 'Cập nhật ngân hàng thành công!');
    } else {
        $check = $db->get_row("SELECT id FROM `bankings` WHERE `bankName` = '".$data['bankName']."' LIMIT 1");
        if ($check) {
            $db->update('bankings', $data, "id = '".$check['id']."'");
            msg_redirect('reload', 'success', 'Đã cập nhật ngân hàng trùng tên!');
        } else {
            $db->insert('bankings', $data);
            msg_redirect('reload', 'success', 'Thêm ngân hàng thành công!');
        }
    }
}

$bankApiProviders = [
    [
        'code' => 'RPAY',
        'name' => 'RPay',
        'url' => 'https://rpay.vn/ref/VU487312923F4',
        'steps' => [
            'Truy cập và đăng ký/đăng nhập tài khoản RPay: <a href="https://rpay.vn/ref/VU487312923F4" target="_blank" rel="noopener">https://rpay.vn/ref/VU487312923F4</a>.',
            'Nạp tiền để thuê dịch vụ API: <a href="https://rpay.vn/auth/naptien" target="_blank" rel="noopener">https://rpay.vn/auth/naptien</a>.',
            'Thuê gói dịch vụ API: <a href="https://rpay.vn/auth/upgrade" target="_blank" rel="noopener">https://rpay.vn/auth/upgrade</a>.',
            'Truy cập <a href="https://rpay.vn/dashboard" target="_blank" rel="noopener">Dashboard RPay</a> để chọn ví cần kết nối API.',
            'Sau khi cấu hình trên RPay, lấy <b>Token API</b> và thông tin ví, nhập vào modal <b>Thêm Ngân Hàng</b> — Loại API <b>RPAY</b>, chọn <b>Mã ví</b> tương ứng.',
            'Riêng <b>VIETTELPAY</b>: nhập <b>Username</b> + <b>Password</b> (PIN) + Token, không cần số tài khoản.',
        ],
    ],
    [
        'code' => 'STC',
        'name' => 'Sieuthicode',
        'url' => 'https://api.sieuthicode.net/reffer/3487',
        'steps' => [
            'Truy cập và đăng ký/đăng nhập tài khoản Sieuthicode: <a href="https://api.sieuthicode.net/reffer/3487" target="_blank" rel="noopener">https://api.sieuthicode.net/reffer/3487</a>.',
            'Nạp tiền và thuê gói API tại Sieuthicode.',
            'Lấy <b>Token API</b> và thông tin ví, nhập vào modal — Loại API <b>STC</b>, chọn <b>Mã ví</b> tương ứng.',
        ],
    ],
    [
        'code' => 'PINO_API',
        'name' => 'PinoCloud',
        'url' => 'https://pinocloud.vn/',
        'steps' => [
            'Truy cập <a href="https://pinocloud.vn/" target="_blank" rel="noopener">PinoCloud</a> và lấy API Key tại Merchant.',
            'Nhập vào modal — Loại API <b>PINO_API</b>, chọn <b>Mã ví</b> tương ứng.',
            '<b>Bank Wallet</b> (ACB): nhập API Key, Username, Password, Số tài khoản.',
            '<b>Card Wallet</b> (webthe): nhập API Key và <b>Wallet Cookie</b>.',
        ],
    ],
];

$walletCodeList = $db->get_list("SELECT * FROM `inter_wallet_code` ORDER BY `wallet_name` ASC");
if (!is_array($walletCodeList)) {
    $walletCodeList = [];
}

$bankings = $db->get_list("SELECT * FROM `bankings` WHERE 1=1 ORDER BY `id` DESC");
