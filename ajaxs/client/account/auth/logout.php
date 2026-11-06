<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(JsonMsg('error', 'CSRF token không hợp lệ.'));
    }

    if (isset($_COOKIE['token'])) {
        $token = anti_xss($_COOKIE['token']);

        // Xóa token khỏi DB
        $db->update("users", [
            'token' => null,
            'token_expiry' => 0
        ], "token = '$token'");

        // Xoá cookie
        setcookie("token", "", time() - 3600, "/", "", true, true);
    }

    // Xoá session
    $_SESSION = [];
    session_destroy();

    msg_redirect('');
}
