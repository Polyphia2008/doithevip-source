<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
use PragmaRX\Google2FA\Google2FA;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $_POST['type'] === 'GoogleAuth' && $_POST['status'] === 'active') {

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('account/security', 'error', 'CSRF token không hợp lệ.');
        }

        check_demo_mode();

        if (!check_login()) {
            msg_redirect('');
        }

        $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '{$data_user['id']}'");

        if (!$user || $user['twofactor'] === 'GoogleAuth') {
            msg_redirect('account/security', 'error', 'Tài khoản đã bật xác thực Google Auth hoặc không hợp lệ.');
        }

        if (empty($user['twofactor_secret'])) {
            msg_redirect('account/security', 'error', 'Chưa tạo mã xác thực, vui lòng thử lại.');
        }

        $secret = anti_xss($_POST['secret']);
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey(decrypt_data($user['twofactor_secret']), $secret)) {
            msg_redirect('account/security', 'error', 'Mã xác thực không chính xác!');
        }

        $db->update('users', [
            'twofactor' => 'GoogleAuth'
        ], "id = '{$user['id']}'");

        insert_log($user['id'], "Kích hoạt xác thực Google Authenticator");
        msg_redirect('account/security', 'success', 'Kích hoạt thành công xác thực Google Authenticator.');
    }
    if (isset($_POST['type']) && $_POST['type'] === 'GoogleAuth' && $_POST['status'] === 'disable') {

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('account/security', 'error', 'CSRF token không hợp lệ.');
        }

        check_demo_mode();
        
        if (!check_login()) {
            msg_redirect('');
        }

        $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '{$data_user['id']}'");

        if (!$user || $user['twofactor'] !== 'GoogleAuth' || empty($user['twofactor_secret'])) {
            msg_redirect('account/security', 'error', 'Tài khoản chưa bật xác thực Google Auth hoặc không hợp lệ.');
        }

        $secret = anti_xss($_POST['secret']);
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey(decrypt_data($user['twofactor_secret']), $secret)) {
            msg_redirect('account/security', 'error', 'Mã xác thực không chính xác!');
        }

        $db->update('users', [
            'twofactor' => null
        ], "id = '{$user['id']}'");

        insert_log($user['id'], "Vô hiệu hoá xác thực Google Authenticator");
        msg_redirect('account/security', 'success', 'Tắt xác thực Google Authenticator thành công.');
    }
    if (isset($_POST['type']) && $_POST['type'] === 'send-code-to-mail') {

        header('Content-Type: application/json');

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die(json_encode(['status' => 'error', 'message' => 'CSRF token không hợp lệ.']));
        }

        check_demo_mode();

        if (!check_login()) {
            die(json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập.']));
        }

        $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '{$data_user['id']}'");
        if (!$user) {
            die(json_encode(['status' => 'error', 'message' => 'Tài khoản không tồn tại hoặc không hợp lệ.']));
        }

        $code = rand(100000, 999999);
        $_SESSION['verify_code'] = $code;
        $_SESSION['verify_time'] = time();

        $title = 'Mã xác thực tài khoản';
        $name = $user['username'];
        $content = [
            'description' => 'Đây là mã xác thực tài khoản, vui lòng không chia sẻ mã này với bất kỳ ai. Mã có hiệu lực trong vòng <b>5 phút</b> kể từ khi gửi.',
            'secret' => $code
        ];

        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/libs/mails/notification.php';
        $html = ob_get_clean();

        if (!sendCSM($user['email'], $name, $title, $html)) {
            die(json_encode(['status' => 'error', 'message' => 'Không thể gửi mã xác thực đến email.']));
        }

        die(json_encode(['status' => 'success', 'message' => 'Mã xác thực đã được gửi đến email của bạn!']));
    }
    if (isset($_POST['type']) && $_POST['type'] === 'Email' && $_POST['status'] === 'active') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('account/security', 'error', 'CSRF token không hợp lệ.');
        }

        if (!check_login()) {
            msg_redirect('');
        }

        $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '{$data_user['id']}'");

        if (!$user) {
            msg_redirect('account/security', 'error', 'Tài khoản không tồn tại hoặc không hợp lệ.');
        }

        $code = anti_xss($_POST['secret']);
        $validCode = $_SESSION['verify_code'] ?? null;
        $validTime = $_SESSION['verify_time'] ?? 0;

        if (!$validCode || time() - $validTime > 300) {
            msg_redirect('account/security', 'error', 'Mã xác thực đã hết hạn. Vui lòng gửi lại.');
        }

        if ($code != $validCode) {
            msg_redirect('account/security', 'error', 'Mã xác thực không đúng.');
        }
        $db->update('users', [
            'twofactor' => 'Email'
        ], "id = '{$user['id']}'");

        unset($_SESSION['verify_code'], $_SESSION['verify_time']);
        insert_log($user['id'], "Kích hoạt xác thực Email");

        msg_redirect('account/security', 'success', 'Kích hoạt thành công xác thực Email.');
    }
    if (isset($_POST['type']) && $_POST['type'] === 'Email' && $_POST['status'] === 'disable') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('account/security', 'error', 'CSRF token không hợp lệ.');
        }

        if (!check_login()) {
            msg_redirect('');
        }

        $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '{$data_user['id']}'");

        if (!$user || $user['twofactor'] !== 'Email') {
            msg_redirect('account/security', 'error', 'Tài khoản chưa bật xác thực Email hoặc không hợp lệ.');
        }

        $code = anti_xss($_POST['secret']);
        $validCode = $_SESSION['verify_code'] ?? null;
        $validTime = $_SESSION['verify_time'] ?? 0;

        if (!$validCode || time() - $validTime > 300) {
            msg_redirect('account/security', 'error', 'Mã xác thực đã hết hạn. Vui lòng gửi lại.');
        }

        if ($code != $validCode) {
            msg_redirect('account/security', 'error', 'Mã xác thực không đúng.');
        }

        $db->update('users', [
            'twofactor' => null
        ], "id = '{$user['id']}'");

        unset($_SESSION['verify_code'], $_SESSION['verify_time']);
        insert_log($user['id'], "Vô hiệu hóa xác thực Email");

        msg_redirect('account/security', 'success', 'Đã tắt xác thực Email thành công.');
    }

}
