<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

$username = anti_xss($_POST['user'] ?? '');
$token = anti_xss($_POST['token'] ?? '');

if (empty($username) || empty($token)) {
    msg_redirect('', 'error', 'Thiếu thông tin xác thực.');
}
check_demo_mode();
// Tìm user theo username + token
$getUser = $db->get_row("SELECT * FROM `users` WHERE `username` = '{$username}' AND `tmp_token` = '{$token}'");
if (!$getUser) {
    msg_redirect('', 'error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
}
if ($getUser['banned']) {
    msg_redirect('', 'error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.');
}
// Tạo mật khẩu mới
$new_password = substr(md5(time() . rand(1000, 9999)), 0, 12);
$hashed = hash_password($new_password);
$new_token = generate_token();
$expiry_time = time() + $db->site('session_login');

// Cập nhật DB
$updated = $db->update("users", [
    'token' => $new_token,
    'token_expiry' => $expiry_time,
    'password' => $hashed,
    'tmp_token' => null
], "id = '{$getUser['id']}'");

if (!$updated) {
    msg_redirect('', 'error', 'Không thể cập nhật mật khẩu. Vui lòng thử lại.');
}

// Chuẩn bị và gửi email
$title = 'Mật khẩu mới của bạn';
$name = $getUser['username'];
$content = [
    'description' => 'Vui lòng không chia sẻ mật khẩu với bất kỳ ai và hãy đổi lại mật khẩu để bảo vệ an toàn cho tài khoản.',
    'secret' => 'Mật khẩu mới của bạn: ' . $new_password
];

ob_start();
require $_SERVER['DOCUMENT_ROOT'] . '/libs/mails/notification.php';
$html = ob_get_clean();

if (sendCSM($getUser['email'], $name, $title, $html)) {
    msg_redirect('', 'success', 'Mật khẩu mới đã được gửi đến email của bạn!');
} else {
    msg_redirect('', 'error', 'Có lỗi xảy ra. Vui Lòng Thử Lại Sau.');
}