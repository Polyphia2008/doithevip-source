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

    $name = anti_xss($_POST["name"] ?? '');
    $email = anti_xss($_POST["email"] ?? '');
    $phone = anti_xss($_POST["phone"] ?? '');

    if (!empty($email) && !check_email($email)) {
        msg_redirect('account/profile', 'error', 'Email không hợp lệ.');
    }
    if (!empty($phone) && !check_phone($phone)) {
        msg_redirect('account/profile', 'error', 'Số điện thoại không hợp lệ.');
    }

    // Kiểm tra trùng email
    if (!empty($email)) {
        $checkEmail = $db->get_row("SELECT `id` FROM `users` WHERE `email` = '{$email}' AND `id` != '{$data_user['id']}'");
        if ($checkEmail) {
            msg_redirect('account/profile', 'error', 'Email đã tồn tại.');
        }
    }

    // Kiểm tra trùng số điện thoại
    if (!empty($phone)) {
        $checkPhone = $db->get_row("SELECT `id` FROM `users` WHERE `phone` = '{$phone}' AND `id` != '{$data_user['id']}'");
        if ($checkPhone) {
            msg_redirect('account/profile', 'error', 'Số điện thoại đã tồn tại.');
        }
    }

    // Cập nhật dữ liệu
    $dataUpdate = [];
    if (!empty($name))  $dataUpdate['name'] = $name;
    if (!empty($email)) $dataUpdate['email'] = $email;
    if (!empty($phone)) $dataUpdate['phone'] = $phone;

    if (!empty($dataUpdate)) {
        $db->update('users', $dataUpdate, "id = '{$data_user['id']}'");
    }

    msg_redirect('account/profile', 'success', 'Cập nhật thông tin thành công.');
}
