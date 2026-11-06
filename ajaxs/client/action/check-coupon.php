<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if($_POST['action'] == 'coupon-acc'){
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ!'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if (!check_login()) {
            die(JsonMsg('error', 'Vui lòng đăng nhập !'));
        }
        if (!$db->get_row(" SELECT * FROM `users` WHERE `id` = '".$data_user['id']."' AND `banned` = '0'' ")) {
            die(JsonMsg('error', 'Dữ liệu người dùng không hợp lệ!'));
        }
        $id =  intval($_POST['id'] ?? 0);
        $couponCode = anti_xss($_POST['coupon']);
        $coupon = $db->get_row("SELECT * FROM `coupons` WHERE `code` = '".$couponCode."''");
        
        if (!$coupon) {
            die(json_encode(['status' => 'error', 'msg' => 'Mã giảm giá không tồn tại!']));
        }
        
        if ($coupon['type'] != '0') {
            die(json_encode(['status' => 'error', 'msg' => 'Mã giảm giá không hợp lệ hoặc không đủ điều kiện để sử dụng!']));
        }
        if ($row = $db->get_row("SELECT * FROM `accounts` WHERE `group_id` = '".$id."''")) {
            $amount = 1;
            $price = $amount * $row['price'];
            $price = $row['price'] - (($row['price'] * $row['sale']) / 100);
            $price = $price - getActiveProductFlashSale($id, 'account')['discountPrice'];
            if (isset($_POST['coupon'])) {
                $discount = checkCoupon($row["group_id"], anti_xss($_POST['coupon']), $price);
            }
            if (isset($discount)) {
                $price = $price - $price * $discount / 100;
            }
            $feePercent = (float) $db->site('fees');
            $fees       = $price * ($feePercent / 100);
            $price = $price + $fees;
            die(json_encode(['status' => 'success', 'msg' => 'Đã khuyến mãi giảm giá còn ' . format_number($price).'đ', 'price' => $price]));
        }
        die(json_encode(['status' => 'error', 'msg' => 'Mã giảm giá không hợp lệ hoặc không đủ điều kiện để sử dụng!']));
    }
    
    if($_POST['action'] == 'coupon-dichvu'){
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ!'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if (!check_login()) {
            die(JsonMsg('error', 'Vui lòng đăng nhập !'));
        }
        if (!$db->get_row(" SELECT * FROM `users` WHERE `id` = '".$data_user['id']."' AND `banned` = '0'' ")) {
            die(JsonMsg('error', 'Dữ liệu người dùng không hợp lệ!'));
        }
        $id =  intval($_POST['id'] ?? 0);
        $couponCode = anti_xss($_POST['coupon']);
        $coupon = $db->get_row("SELECT * FROM `coupons` WHERE `code` = '".$couponCode."''");
        
        if (!$coupon) {
            die(json_encode(['status' => 'error', 'msg' => 'Mã giảm giá không tồn tại!']));
        }
        
        if ($coupon['type'] != '1') {
            die(json_encode(['status' => 'error', 'msg' => 'Mã giảm giá không hợp lệ hoặc không đủ điều kiện để sử dụng!']));
        }
        if ($row = $db->get_row("SELECT * FROM `pack_service` WHERE `id` = '".$id."'' ")) {
            $amount = 1;
            $price = $amount * $row['price'];
            $price = $row['price'];
            if (isset($_POST['coupon'])) {
                $discount = checkCoupon($row["group_id"], anti_xss($_POST['coupon']), $price);
            }
            if (isset($discount)) {
                $price = $price - $price * $discount / 100;
            }
            $feePercent = (float) $db->site('fees');
            $fees       = $price * ($feePercent / 100);
            $price = $price + $fees;
            die(json_encode(['status' => 'success', 'msg' => format_number($price)]));
        }
        die(json_encode(['status' => 'error', 'msg' => 'Mã giảm giá không hợp lệ hoặc không đủ điều kiện để sử dụng!']));
    }
} else {
    die(json_encode(array('message' => "The requested resource does not support http method 'GET'.")));
}