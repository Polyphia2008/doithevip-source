<?php
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/users', 'error', 'Thiếu ID người dùng.');
}

$user = $db->get_row("SELECT * FROM `users` WHERE `id` = '{$id}'");
if (!$user) {
    msg_redirect('admin/users', 'error', 'Người dùng không tồn tại.');
}

/* ================== XỬ LÝ UPDATE USER ================== */
if (isset($_POST['submit_user'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $name     = anti_xss($_POST['name'] ?? '');
    $email    = anti_xss($_POST['email'] ?? '');
    $phone    = anti_xss($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $twofactor= anti_xss($_POST['twofactor'] ?? '');
    $admin    = (int)($_POST['admin'] ?? 0);
    $banned   = (int)($_POST['banned'] ?? 0);

    // Check unique email
    $check_email = $db->get_row("SELECT `id` FROM `users` WHERE `email` = '{$email}' AND `id` <> '{$id}'");
    if ($check_email) {
        msg_redirect('reload', 'error', 'Email đã tồn tại.');
    }

    // Check unique phone
    if (!empty($phone)) {
        $check_phone = $db->get_row("SELECT `id` FROM `users` WHERE `phone` = '{$phone}' AND `id` <> '{$id}'");
        if ($check_phone) {
            msg_redirect('reload', 'error', 'Số điện thoại đã tồn tại.');
        }
    }

    $updateData = [
        'name'     => $name,
        'email'    => $email,
        'phone'    => $phone,
        'twofactor'=> $twofactor,
        'admin'    => $admin,
        'banned'   => $banned,
    ];

    if (!empty($password)) {
        $updateData['password'] = hash_password($password);
    }

    $update = $db->update('users', $updateData, " `id` = '{$id}' ");

    if ($update) {
        msg_redirect('reload', 'success', 'Cập nhật thành viên thành công.');
    } else {
        msg_redirect('reload', 'error', 'Không có thay đổi hoặc cập nhật thất bại.');
    }
}
if (isset($_POST['submit_balance'])) {
    check_demo_mode();

    $blance_select = anti_xss($_POST['blance_select'] ?? '');
    $amountInput   = (int)($_POST['amountInput'] ?? 0);
    $reasonInput   = anti_xss($_POST['reasonInput'] ?? '');
    $addNap        = isset($_POST['addNap']) ? 1 : 0;

    if ($amountInput <= 0) {
        msg_redirect('reload', 'error', 'Số tiền phải lớn hơn 0.');
    }

    
    $success = false;

    if ($blance_select === 'congtien') {
        $transid = generate_code_with_prefix('D', 14);
        $success = CongTien($id, $amountInput, $reasonInput, $transid);
        if ($success && $addNap) {
            $db->cong("users", "total_money", $amountInput, " `id` = '{$id}' ");
        }
    } elseif ($blance_select === 'trutien') {
        if ($user['money'] < $amountInput) {
            msg_redirect('reload', 'error', 'Số dư hiện tại không đủ để trừ.');
        }
        $transid = generate_code_with_prefix('W', 14);
        $success = TruTien($id, $amountInput, $reasonInput, $transid);
    } else {
        msg_redirect('reload', 'error', 'Loại giao dịch không hợp lệ.');
    }

    if ($success) {
        msg_redirect('reload', 'success', 'Cập nhật số dư thành công.');
    } else {
        msg_redirect('reload', 'error', 'Có lỗi xảy ra khi cập nhật số dư.');
    }
}
