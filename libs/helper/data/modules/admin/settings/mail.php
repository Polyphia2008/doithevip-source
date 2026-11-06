<?php
// Lấy config hiện tại
$setting = $db->get_row("SELECT * FROM sendmail_setting LIMIT 1");
$driver  = $db->get_row("SELECT * FROM sendmail_driver WHERE driver = '".($setting['driver'] ?? 'Smtp')."' LIMIT 1");
$configs = !empty($driver['configs']) ? json_decode($driver['configs'], true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }
    check_demo_mode();
    
    $from_email = anti_xss($_POST['from_email'] ?? '');
    $from_name  = anti_xss($_POST['from_name'] ?? '');
    $driverName = anti_xss($_POST['driver'] ?? 'Smtp');

    $configs = [
        'host'       => anti_xss($_POST['host'] ?? ''),
        'username'   => anti_xss($_POST['username'] ?? ''),
        'password'   => anti_xss($_POST['password'] ?? ''),
        'port'       => anti_xss($_POST['port'] ?? ''),
        'encryption' => anti_xss($_POST['encryption'] ?? '')
    ];
    $configs_json = json_encode($configs, JSON_UNESCAPED_UNICODE);

    $setting = $db->get_row("SELECT * FROM sendmail_setting LIMIT 1");

    if (!$setting) {
        $db->insert("sendmail_setting", [
            'from_email' => $from_email,
            'from_name'  => $from_name,
            'driver'     => $driverName,
            'created_at' => gettime(),
            'updated_at' => gettime()
        ]);
    } else {
        $db->update("sendmail_setting", [
            'from_email' => $from_email,
            'from_name'  => $from_name,
            'driver'     => $driverName,
            'updated_at' => gettime()
        ], "`id` = '{$setting['id']}'");
    }

    $driver = $db->get_row("SELECT * FROM sendmail_driver WHERE driver = '$driverName' LIMIT 1");

    if (!$driver) {
        $db->insert("sendmail_driver", [
            'driver'     => "$driverName",
            'configs'    => $configs_json,
            'status'     => 1,
            'created_at' => gettime(),
            'updated_at' => gettime()
        ]);
    } else {
        $db->update("sendmail_driver", [
            'configs'    => $configs_json,
            'status'     => 1,
            'updated_at' => gettime()
        ], "`id` = '{$driver['id']}'");
    }

    msg_redirect('reload', 'success', 'Lưu cấu hình mail thành công.');
}
