<?php
$id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        msg_redirect('admin/service/product-list', 'error', 'Thiếu ID sản phẩm.');
    }

    $group = $db->get_row("SELECT * FROM `groups_service` WHERE `id` = '{$id}'");
    if (!$group) {
        msg_redirect('admin/service/product-list', 'error', 'Danh mục không tồn tại.');
    }

    $where = " `id` > 0 AND `group_id` = ".$id."";
    $orderBy = "`id` DESC";

    if (!empty($_GET['id_pack']) && is_numeric($_GET['id_pack'])) {
        $id_pack = intval($_GET['id_pack']);
        $where .= " AND `id` = '{$id_pack}'";
    }
    if (!empty($_GET['name'])) {
        $name = anti_xss($_GET['name']);
        $where .= " AND `name` LIKE '%{$name}%'";
    }
    if (!empty($_GET['type'])) {
        $type = anti_xss($_GET['type']);
        $where .= " AND `type` = '{$type}'";
    }
    if (!empty($_GET['price_sort'])) {
        if ($_GET['price_sort'] === 'price_asc') {
            $orderBy = "`price` ASC";
        } elseif ($_GET['price_sort'] === 'price_desc') {
            $orderBy = "`price` DESC";
        }
    }

    if (isset($_GET['status']) && $_GET['status'] !== '' && is_numeric($_GET['status'])) {
        $status = intval($_GET['status']);
        $where .= " AND `status` = '{$status}'";
    }

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `pack_service` WHERE $where");
    $products = $db->get_list("SELECT * FROM `pack_service` WHERE $where ORDER BY $orderBy LIMIT $limit OFFSET $start");

foreach ($products as $key => $product) {
    $products[$key]['type_info'] = service_type_admin($product['type'] ?? '');
    $products[$key]['category_name'] = anti_xss(getRowRealtime('category_service', $product['category_id'], 'name'));
    $products[$key]['group_name'] = anti_xss(getRowRealtime('groups_service', $product['group_id'], 'name'));
}
