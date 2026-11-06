<?php
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/modules/menu-list', 'error', 'Thiếu ID menu.');
}

$menu = $db->get_row("SELECT * FROM `menu` WHERE `id` = '{$id}'");
if (!$menu) {
    msg_redirect('admin/modules/menu-list', 'error', 'Menu không tồn tại.');
}

$parent_menus = $db->get_list("SELECT id,name FROM menu WHERE parent_id = 0 AND menu_type = '" . $menu['menu_type'] . "' ORDER BY sort_order ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $name       = anti_xss($_POST['name'] ?? '');
    $icon       = anti_xss($_POST['icon'] ?? '');
    $url        = anti_xss($_POST['url'] ?? '#');
    $menu_type  = anti_xss($_POST['menu_type'] ?? 'header');
    $parent_id  = (int)($_POST['parent_id'] ?? 0);
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status     = (int)($_POST['status'] ?? 1);

    if (empty($name)) {
        msg_redirect('reload', 'error', 'Tên menu không được để trống.');
    }

    $parentLevel = 0;
    if ($parent_id > 0) {
        $parent = $db->get_row("SELECT level FROM `menu` WHERE id = '{$parent_id}'");
        $parentLevel = $parent ? (int)$parent['level'] : 1;
    }

    $update = $db->update('menu', [
        'icon'          => $icon,
        'name'          => $name,
        'url'           => $url,
        'menu_type'     => $menu_type,
        'parent_id'     => $parent_id,
        'level'         => $parent_id > 0 ? $parentLevel + 1 : 1,
        'sort_order'    => $sort_order,
        'status'        => $status,
        'updated_at'    => gettime(),
    ], "id = '{$id}'");

    if ($update) {
        msg_redirect('reload', 'success', 'Cập nhật menu thành công.');
    } else {
        msg_redirect('reload', 'error', 'Cập nhật menu thất bại.');
    }
}
