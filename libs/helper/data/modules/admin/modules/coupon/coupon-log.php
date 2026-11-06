<?php
if (isset($_GET['id'])) {
        $id = check_string($_GET['id']);
        $row = $db->get_row("SELECT * FROM `coupons` WHERE `id` = '$id' ");
        if (!$row) {
            msg_redirect('admin/modules/coupon-list', 'error', 'Mã giảm giá không tồn tại.');
        }
    } else {
        msg_redirect('admin/modules/coupon-list', 'error', 'ID Mã giảm giá không hợp lệ.');
    }

$coupon_logs = $db->get_list("SELECT * FROM `coupon_used` WHERE `coupon_id` = '" . $row['id'] . "' ORDER BY id DESC");

foreach ($coupon_logs as $key => $log) {
    $coupon_logs[$key]['coupon_code'] = anti_xss(getRowRealtime('coupons', $log['coupon_id'], 'code') ?? 'Không rõ');
    $coupon_logs[$key]['username'] = anti_xss(getRowRealtime('users', $log['user_id'], 'username') ?? 'Không rõ');
}
