<?php
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/modules/coupon-list', 'error', 'ID không hợp lệ.');
}

// Lấy coupon hiện tại
$coupon = $db->get_row("SELECT * FROM coupons WHERE id = '{$id}'");
if (!$coupon) {
    msg_redirect('admin/modules/coupon-list', 'error', 'Mã giảm giá không tồn tại.');
}

// ========================
// UPDATE COUPON
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UpdateCoupon'])) {

    // CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    // Demo mode
    check_demo_mode();

    // Lấy dữ liệu (KHÔNG nhận code, KHÔNG nhận type)
    $amount   = (int) ($_POST['amount'] ?? 0);
    $min      = (int) ($_POST['min'] ?? 0);
    $max      = (int) ($_POST['max'] ?? 0);
    $discount = (int) ($_POST['discount'] ?? 0);
    $products = $_POST['product_id'] ?? [];

    // Validate
    if ($amount <= 0) {
        msg_redirect('reload', 'error', 'Số lượng sử dụng không hợp lệ.');
    }

    // Không cho giảm thấp hơn số đã dùng
    if ($amount < $coupon['used']) {
        msg_redirect('reload', 'error', 'Số lượt mới không được nhỏ hơn số lượt đã sử dụng.');
    }

    if ($discount <= 0 || $discount > 100) {
        msg_redirect('reload', 'error', 'Giảm giá phải từ 1% đến 100%.');
    }

    if ($min < 0 || $max <= 0 || $min >= $max) {
        msg_redirect('reload', 'error', 'Giá trị đơn hàng không hợp lệ.');
    }

    // Xử lý product_id (GIỮ LOGIC CŨ)
    $product_id = NULL;
    if (!empty($products) && !in_array('', $products)) {
        $product_id = json_encode(array_map('intval', $products), JSON_UNESCAPED_UNICODE);
    }

    // Update DB
    $isUpdate = $db->update('coupons', [
        'amount'     => $amount,
        'product_id' => $product_id,
        'min'        => $min,
        'max'        => $max,
        'discount'   => $discount,
        'updated_at' => gettime()
    ], "id = '{$id}'");

    if ($isUpdate) {
        msg_redirect('reload', 'success', 'Cập nhật mã giảm giá thành công!');
    } else {
        msg_redirect('reload', 'error', 'Không có thay đổi hoặc cập nhật thất bại.');
    }
}

$category_groups_account = [];
foreach ($db->get_list("SELECT * FROM category_account") as $category) {
    $category_groups_account[] = [
        'category' => $category,
        'groups' => $db->get_list("SELECT * FROM groups_account WHERE category_id = '{$category['id']}'"),
    ];
}

$category_groups_service = [];
foreach ($db->get_list("SELECT * FROM category_service") as $category) {
    $category_groups_service[] = [
        'category' => $category,
        'groups' => $db->get_list("SELECT * FROM groups_service WHERE category_id = '{$category['id']}'"),
    ];
}

$selectedProducts = json_decode($coupon['product_id'] ?? '', true) ?: [];
