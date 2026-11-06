<?php
/* =======================
 * GET PACK
 * ======================= */
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/service/product-list', 'error', 'Thiếu ID gói.');
}

$pack = $db->get_row("SELECT * FROM `pack_service` WHERE `id` = '$id'");
if (!$pack) {
    msg_redirect('admin/service/product-list', 'error', 'Gói dịch vụ không tồn tại!');
}

/* =======================
 * GET GROUP
 * ======================= */
$group = $db->get_row("SELECT * FROM `groups_service` WHERE `id` = '{$pack['group_id']}'");
if (!$group) {
    msg_redirect('admin/service/product-list', 'error', 'Nhóm sản phẩm không tồn tại!');
}

/* =======================
 * HANDLE POST
 * ======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $sort_order = (int)($_POST['sort_order'] ?? 1);
    $name       = anti_xss($_POST['name'] ?? '');
    $price      = (int)($_POST['price'] ?? 0);
    $status     = (int)($_POST['status'] ?? 1);

    if (empty($name)) {
        msg_redirect('reload', 'error', 'Tên gói không được để trống.');
    }

    /* =======================
     * IMAGE LOGIC
     * ======================= */
    $image = $pack['image'];

    if ($group['type'] === 'image') {
        if (!empty($_FILES['image']['name'])) {
            $image = upload_file('image', 'product');

            if (empty($image)) {
                msg_redirect('reload', 'error', 'Upload hình ảnh thất bại.');
            }
        }
    } else {
        $image = null;
    }

    /* =======================
     * UPDATE PACK
     * ======================= */
    $update = $db->update('pack_service', [
        'sort_order' => $sort_order,
        'name'       => $name,
        'image'      => $image,
        'price'      => $price,
        'status'     => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ], "id = '$id'");

    if ($update) {
        msg_redirect('admin/service/pack-list/' . $group['id'], 'success', 'Cập nhật gói dịch vụ thành công.');
    } else {
        msg_redirect('reload', 'error', 'Cập nhật thất bại.');
    }
}
