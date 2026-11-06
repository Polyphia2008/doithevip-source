<?php
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/account/category-list', 'error', 'Thiếu ID danh mục.');
}

$item = $db->get_row("SELECT * FROM `category_account` WHERE `id` = '{$id}'");
if (!$item) {
    msg_redirect('admin/account/category-list', 'error', 'Danh mục không tồn tại.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('admin/account/category-edit/'.$id, 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $sort_order   = (int)($_POST['sort_order'] ?? 0);
    $name         = anti_xss($_POST['name'] ?? '');
    $slug         = !empty($_POST['slug']) ? anti_xss($_POST['slug']) : create_slug($name);
    $type         = anti_xss($_POST['type'] ?? '');
    $keywords     = anti_xss($_POST['keywords'] ?? '');
    $description  = $_POST['description'] ?? '';
    $price        = (int)($_POST['price'] ?? 0);
    $status       = (int)($_POST['status'] ?? 1);

    // Xử lý ảnh
    $oldImage = $item['image'];
    $image = update_file('image', $oldImage, 'product');

    if ($image && $oldImage && $image !== $oldImage) {
        $oldPath = realpath($_SERVER["DOCUMENT_ROOT"] . $oldImage);
        if (is_file($oldPath)) @unlink($oldPath);
    }

    $update = $db->update('category_account', [
        'sort_order'    => $sort_order,
        'name'          => $name,
        'slug'          => $slug,
        'image'         => $image,
        'status'        => $status,
        'updated_at'    => date('Y-m-d H:i:s')
    ], " `id` = '{$id}' ");

    if ($update) {
        msg_redirect('admin/account/category-list', 'success', 'Cập nhật thành công.');
    } else {
        msg_redirect('admin/account/category-edit/'.$id, 'error', 'Cập nhật thất bại.');
    }
}
