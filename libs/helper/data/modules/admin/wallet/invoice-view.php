<?php
$order_code = anti_xss($_GET['order_code']);
    if (empty($order_code)) {
        msg_redirect('admin/wallet/invoices', 'error', 'Thiếu mã đơn.');
    }

    $row = $db->get_row("SELECT * FROM `invoices` WHERE `order_code` = '{$order_code}'");
    if (!$row) {
        msg_redirect('admin/wallet/invoices', 'error', 'Hóa đơn không tồn tại.');
    }

    /* ================== XỬ LÝ UPDATE INVOICE ================== */
    if (isset($_POST['submit'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
        }

        check_demo_mode();

        $paygate        = anti_xss($_POST['paygate'] ?? '');
        $pay_amount     = (int)($_POST['pay_amount'] ?? 0);
        $status         = anti_xss($_POST['status'] ?? $row['status']);
        $status_service = anti_xss($_POST['status_service'] ?? $row['status_service']);

        $updateData = [
            'paygate'        => $paygate,
            'pay_amount'     => $pay_amount,
            'status'         => $status,
            'status_service' => $status_service,
            'updated_at'     => gettime(),
        ];

        $update = $db->update('invoices', $updateData, " `id` = '{$row['id']}' ");

        if ($update) {
            insert_log($_SESSION['user_id'] ?? 0, "Admin cập nhật hóa đơn {$row['order_code']}");
            msg_redirect('reload', 'success', 'Cập nhật hóa đơn thành công.');
        } else {
            msg_redirect('reload', 'error', 'Không có thay đổi hoặc cập nhật thất bại.');
        }
    }

$deposit_banks = $db->get_list("SELECT * FROM `bankings` WHERE `invoice_pay` = 1");
