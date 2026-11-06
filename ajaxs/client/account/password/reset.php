<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error'] = null;
    $_SESSION['success'] = null;

    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        msg_redirect('account/password/reset', 'error', 'CSRF token không hợp lệ.');
    }
    check_demo_mode();
    
    if ($db->site('cf_enable') == 1) {
        $token = $_POST['cf-turnstile-response'] ?? '';
        if (empty($token)) {
            msg_redirect('reload', 'error', 'Vui lòng xác minh captcha.');
        }
        $secret = $db->site('cf_secret_key');
        $ch = curl_init("https://challenges.cloudflare.com/turnstile/v0/siteverify");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ])
        ]);
        $result = json_decode(curl_exec($ch), true);
        if (!$result['success']) {
            msg_redirect('reload', 'error', 'Captcha không hợp lệ, vui lòng thử lại.');
        }
    }
    
    $email = anti_xss($_POST["email"] ?? '');
    if (!check_email($email)) {
        msg_redirect('account/password/reset', 'error', 'Email không hợp lệ.');
    }


    $getUser = $db->get_row("SELECT * FROM `users` WHERE `email` = '{$email}'");
    if (!$getUser) {
        msg_redirect('account/password/reset', 'error', 'Không tìm thấy email này trong hệ thống.');
    }
    if ($getUser['banned']) {
        msg_redirect('account/password/reset', 'error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.');
    }
    $now = time();
    $cooldown = 60;
    $last = intval($getUser['last_reset_request'] ?? 0);
    if ($last > ($now - $cooldown)) {
        msg_redirect('account/password/reset', 'error', 'Bạn đã yêu cầu gần đây, vui lòng thử lại sau 1 phút.');
    }

    // Tạo token reset
    $tmp_token = generate_token();
    $db->update('users', [
        'tmp_token' => $tmp_token
    ], "id = '{$getUser['id']}'");

    // Tạo URL xác nhận
    $reset_link = BASE_URL("account/password/verify?user=" . urlencode($getUser['username']) . "&token=" . $tmp_token);

    // Chuẩn bị nội dung email
    $title = 'Yêu cầu khôi phục mật khẩu';
    $name = $getUser['username'];
    $content = [
        'description' => 'Để khôi phục mật khẩu bạn hãy nhấn vào đường dẫn bên dưới hoặc copy đường dẫn vào trình duyệt.',
        'url' => $reset_link,
        'button_name' => 'Đặt Lại Mật Khẩu'
    ];

    // Render nội dung email từ template
    ob_start();
    require $_SERVER['DOCUMENT_ROOT'] . '/libs/mails/notification.php';
    $html = ob_get_clean();

    // Gửi mail
    $sent = sendCSM($email, $name, $title, $html);

    if ($sent) {
        $db->update('users', [
            'last_reset_request' => $now
        ], "id = '{$getUser['id']}'");
        msg_redirect('account/password/reset', 'success', 'Một email khôi phục mật khẩu đã được gửi đến bạn!');
    } else {
        msg_redirect('account/password/reset', 'error', 'Không thể gửi email. Vui lòng thử lại.');
    }

}
