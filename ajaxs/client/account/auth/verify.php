<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('account/login', 'error', 'CSRF token không hợp lệ.');
    }
    check_demo_mode();
    if (empty($_POST['secret']) || empty($_POST['stoken']) || $_POST['stoken'] !== ($_SESSION['stoken'] ?? '')) {
        msg_redirect('account/login', 'error', 'Xác thực thất bại.');
    }

    $userId = $_SESSION['user_pending'] ?? null;
    if (!$userId) {
        msg_redirect('account/login', 'error', 'Không xác định người dùng.');
    }

    $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '" . intval($userId) . "' AND `admin` = 1 LIMIT 1");
    if (!$user || $user['twofactor'] !== 'GoogleAuth' || empty($user['twofactor_secret'])) {
        msg_redirect('account/login', 'error', 'Tài khoản không hợp lệ hoặc chưa bật xác thực Google Auth.');
    }

    if (!$google2fa->verifyKey(decrypt_data($user['twofactor_secret']), anti_xss($_POST['secret']))) {
        if ($user['failed'] >= 5) {
            Banned($user['id'], "Tài khoản của bạn đã bị khoá do nhập sai mã xác thực nhiều lần");
            msg_redirect('', 'error', 'Tài khoản của bạn đã bị khoá do nhập sai mã 2fa nhiều lần, vui lòng liên hệ admin');
        }
        $db->cong('users', 'failed', 1, " `id` = '" . $user['id'] . "' ");
        msg_redirect('account/login', 'error', 'Mã xác minh không chính xác!');
    }

    $token = generate_token();
    $expiry = time() + intval($db->site('session_login'));
    $db->update('users', [
        'failed' => 0,
        'token' => $token,
        'token_expiry' => $expiry,
        'update_date' => gettime()
    ], "id = '{$user['id']}'");
    insert_log($user['id'], "Đăng nhập vào hệ thống");
    setcookie("token", $token, $expiry, "/", "", true, true);

    unset($_SESSION['verify'], $_SESSION['stoken'], $_SESSION['user_pending']);

    msg_redirect('admin');
}
