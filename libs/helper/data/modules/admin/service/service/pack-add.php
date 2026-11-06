<?php
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/service/product-list', 'error', 'Thiếu ID nhóm sản phẩm.');
}

/* =======================
* GET GROUP
* ======================= */
$group = $db->get_row("SELECT * FROM `groups_service` WHERE `id` = '$id'");
if (!$group) {
    msg_redirect('admin/service/product-list', 'error', 'Nhóm sản phẩm không tồn tại!');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $sort_order  = (int)($_POST['sort_order'] ?? 1);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $group_id    = (int)($_POST['group_id'] ?? 0);
    $name        = anti_xss($_POST['name'] ?? '');
    $price       = (int)($_POST['price'] ?? 0);
    $status      = (int)($_POST['status'] ?? 1);
    $image = null;

    if (empty($name)) {
        msg_redirect('reload', 'error', 'Tên gói không được để trống.');
    }
    

    if ($group['type'] === 'image') {
        $image = upload_file('image', 'product');

        if (empty($image)) {
            msg_redirect('reload', 'error', 'Vui lòng chọn hình ảnh cho gói.');
        }
    }

    $insert = $db->insert('pack_service', [
        'sort_order' => $sort_order,
        'category_id'=> $group['category_id'],
        'group_id'   => $group['id'],
        'name'       => $name,
        'image'      => $image,
        'price'      => $price,
        'status'     => $status,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    if ($insert) {
        msg_redirect('admin/service/pack-list/'.$group['id'].'', 'success', 'Thêm gói dịch vụ thành công.');
    } else {
        msg_redirect('reload', 'error', 'Thêm thất bại.');
    }
}
