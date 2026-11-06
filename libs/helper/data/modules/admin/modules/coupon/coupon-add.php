<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }
    check_demo_mode();
    $code      = strtoupper(trim(anti_xss($_POST['code'] ?? '')));
    $type      = (int) ($_POST['type'] ?? -1);
    $amount    = (int) ($_POST['amount'] ?? 0);
    $min       = (int) ($_POST['min'] ?? 0);
    $max       = (int) ($_POST['max'] ?? 0);
    $discount  = (int) ($_POST['discount'] ?? 0);
    $products  = $_POST['product_id'] ?? [];

    // Validate cơ bản (giữ đúng tinh thần backend cũ)
    if (empty($code)) {
        msg_redirect('reload', 'error', 'Vui lòng nhập mã giảm giá.');
    }

    if ($amount <= 0) {
        msg_redirect('reload', 'error', 'Số lượng sử dụng không hợp lệ.');
    }

    if (!in_array($type, [0, 1])) {
        msg_redirect('reload', 'error', 'Loại dịch vụ không hợp lệ.');
    }

    if ($discount <= 0 || $discount > 100) {
        msg_redirect('reload', 'error', 'Giảm giá phải từ 1% đến 100%.');
    }

    if ($min < 0 || $max <= 0 || $min >= $max) {
        msg_redirect('reload', 'error', 'Giá trị đơn hàng không hợp lệ.');
    }

    // Check trùng mã coupon
    if ($db->get_row("SELECT id FROM coupons WHERE code = '{$code}'")) {
        msg_redirect('reload', 'error', 'Mã giảm giá này đã tồn tại trong hệ thống!');
    }

    // Xử lý product_id (GIỐNG LOGIC CŨ)
    $product_id = NULL;
    if (!empty($products) && !in_array('', $products)) {
        $product_id = json_encode(array_map('intval', $products), JSON_UNESCAPED_UNICODE);
    }

    // Insert DB (mapping y hệt backend cũ)
    $isInsert = $db->insert('coupons', [
        'code'       => $code,
        'product_id' => $product_id,
        'type'       => $type,
        'amount'     => $amount,
        'min'        => $min,
        'max'        => $max,
        'discount'   => $discount,
        'used'       => 0,
        'created_at' => gettime(),
        'updated_at' => gettime()
    ]);

    if ($isInsert) {
        msg_redirect('admin/modules/coupon-list', 'success', 'Thêm mã giảm giá thành công!');
    } else {
        msg_redirect('reload', 'error', 'Thêm mã giảm giá thất bại!');
    }
}

$category_groups_account = [];
foreach ($db->get_list("SELECT * FROM `category_account`") as $category) {
    $category_groups_account[] = [
        'category' => $category,
        'groups' => $db->get_list("SELECT * FROM `groups_account` WHERE `category_id` = '" . $category['id'] . "'"),
    ];
}

$category_groups_service = [];
foreach ($db->get_list("SELECT * FROM category_service") as $category) {
    $category_groups_service[] = [
        'category' => $category,
        'groups' => $db->get_list("SELECT * FROM `groups_service` WHERE `category_id` = '" . $category['id'] . "'"),
    ];
}
