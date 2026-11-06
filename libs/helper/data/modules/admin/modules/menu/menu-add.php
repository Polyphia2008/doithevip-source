<?php
$parent_menus = $db->get_list("SELECT id,name FROM menu WHERE parent_id = 0 AND menu_type = 'header' ORDER BY sort_order ASC");

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

        $insert = $db->insert('menu', [
            'icon'          => $icon,
            'name'          => $name,
            'url'           => $url,
            'menu_type'     => $menu_type,
            'parent_id'     => $parent_id,
            'level'         => $parent_id > 0 ? 2 : 1,
            'children_count'=> 0,
            'sort_order'    => $sort_order,
            'status'        => $status,
            'created_at'    => gettime(),
            'updated_at'    => gettime(),
        ]);

        if ($insert) {
            msg_redirect('reload', 'success', 'Thêm menu thành công.');
        } else {
            msg_redirect('reload', 'error', 'Thêm menu thất bại.');
        }
    }
