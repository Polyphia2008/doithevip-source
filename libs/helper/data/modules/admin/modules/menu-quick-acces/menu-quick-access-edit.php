<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $name       = anti_xss($_POST['name'] ?? '');
    $url        = anti_xss($_POST['url'] ?? '#');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status     = (int)($_POST['status'] ?? 1);

    if (empty($name)) {
        msg_redirect('reload', 'error', 'Tên menu không được để trống.');
    }
    // Xử lý ảnh
    $oldImage = $menu['image'];
    $image = update_file('image', $oldImage, 'menu');

    if ($image && $oldImage && $image !== $oldImage) {
        $oldPath = realpath($_SERVER["DOCUMENT_ROOT"] . $oldImage);
        if (is_file($oldPath)) @unlink($oldPath);
    }
    $update = $db->update('menu_quick_acces', [
        'name'          => $name,
        'url'           => $url,
        'sort_order'    => $sort_order,
        'status'        => $status,
        'image'         => $image
    ], "id = '{$id}'");

    if ($update) {
        msg_redirect('reload', 'success', 'Cập nhật menu thành công.');
    } else {
        msg_redirect('reload', 'error', 'Cập nhật menu thất bại.');
    }
}
