<?php
$where = " `id` > 0 ";

    // lọc loại menu (header/footer)
    if (!empty($_GET['menu_type'])) {
        $menu_type = anti_xss($_GET['menu_type']);
        $where .= " AND `menu_type` = '{$menu_type}'";
    }

    // lọc theo trạng thái
    if (isset($_GET['status']) && $_GET['status'] !== '' && is_numeric($_GET['status'])) {
        $status = intval($_GET['status']);
        $where .= " AND `status` = '{$status}'";
    }

    // lọc theo tên
    if (!empty($_GET['name'])) {
        $name = anti_xss($_GET['name']);
        $where .= " AND `name` LIKE '%{$name}%'";
    }

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 20;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `menu` WHERE $where");
    $menus = $db->get_list("SELECT * FROM `menu` WHERE $where ORDER BY `sort_order` ASC LIMIT $limit OFFSET $start");

foreach ($menus as $key => $menu) {
    $menus[$key]['parent_name'] = getRowRealtime('menu', $menu['parent_id'], 'name');
}
