<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }
    check_demo_mode();

    $blockedKeys = ['csrf_token', 'domain'];
    foreach ($_POST as $name => $value) {
        if (in_array($name, $blockedKeys, true)) {
            continue;
        }
        $db->set_site($name, anti_xss($value));
    }

    uploadAndSaveOption('favicon','favicon');
    uploadAndSaveOption('logo_light','logo_light');
    uploadAndSaveOption('logo_dark','logo_dark');
    uploadAndSaveOption('anhbia','anhbia');

    msg_redirect('reload', 'success', 'Lưu cấu hình thành công.');
}

$settingsRows = $db->get_list("SELECT `key`, `value` FROM `settings`");
$settings = [];
foreach ($settingsRows as $row) {
    $settings[$row['key']] = $row['value'];
}
