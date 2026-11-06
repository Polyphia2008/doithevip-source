<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error'] = null;
    $_SESSION['success'] = null;

    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        msg_redirect('', 'error', 'CSRF token không hợp lệ.');
    }
    if($db->site("status_demo") == 1){
        die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
    }
    if (!check_login()) {
        msg_redirect('');
    }

    $old_pwd = anti_xss($_POST["old-pwd"] ?? '');
    $new_pwd = anti_xss($_POST["new-pwd"] ?? '');
    $new_pwd_confirmation = anti_xss($_POST["new-pwd-confirmation"] ?? '');
    if (empty($old_pwd) || empty($new_pwd) || empty($new_pwd_confirmation)) {
        msg_redirect('account/change-password', 'error', 'Vui lòng nhập đầy đủ thông tin.');
    }
    if (!verify_password($old_pwd, $getUser['password'])) {
        if ($getUser['failed'] >= 5) {
            Banned($getUser['id'], "Tài khoản của bạn đã bị khoá do nhập sai mật khẩu nhiều lần");
            msg_redirect('account/change-password', 'error', 'Tài khoản của bạn đã bị khoá do nhập sai mật khẩu nhiều lần');
        }
        $db->cong('users', 'failed', 1, " `id` = '" . $getUser['id'] . "' ");
        msg_redirect('account/change-password', 'error', 'Mật khẩu cũ không chính xác');
    }
    if (strlen($new_pwd) < 4) {
        msg_redirect('account/change-password', 'error', 'Mật khẩu phải từ 4 ký tự trở lên');
    }
    if ($old_pwd == $new_pwd) {
        msg_redirect('account/change-password', 'error', 'Mật khẩu mới không được giống mật khẩu cũ');
    } 
    if ($new_pwd != $new_pwd_confirmation) {
        msg_redirect('account/change-password', 'error', 'Nhập lại mật khẩu mới không chính xác');
    }   
    $realPass = hash_password($new_pwd_confirmation);
    $new_token = generate_token();
    $expiry_time = time() + $db->site('session_login');
    $db->update('users', [
        'password' => $realPass,
        'failed' => 0,
        'token' => $new_token,
        'token_expiry' => $expiry_time
    ], " `id` = '{$getUser['id']}'");
    insert_log($getUser['id'], "Cập nhật mật khẩu mới");
    setcookie("token", $new_token, $expiry_time, "/", "", true, true);
    msg_redirect('account/change-password', 'success', 'Cập nhật thông tin thành công.');
}
