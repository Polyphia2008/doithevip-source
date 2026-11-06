<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $name        = anti_xss($_POST['name'] ?? '');
    $slug        = !empty($_POST['slug']) ? anti_xss($_POST['slug']) : create_slug($name);
    $image       = upload_file('image', 'product');
    $status      = (int)($_POST['status'] ?? 1);

    $check_name = $db->get_row("SELECT `id` FROM `category_service` WHERE `slug` = '" . $slug . "' ");
    if ($check_name) {
        msg_redirect('reload', 'error', 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.');
    }
    $insert = $db->insert('category_service', [
        'sort_order' => $sort_order,
        'name'       => $name,
        'slug'       => $slug,
        'image'      => $image,
        'status'     => $status,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    if ($insert) {
        msg_redirect('admin/service/category-list', 'success', 'Thêm thành công.');
    } else {
        msg_redirect('reload', 'error', 'Thêm thất bại.');
    }
}
