<?php
$where = " `id` > 0";
    $orderBy = "`sort_order` ASC";

    if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
        $where .= " AND `id` = '{$id}'";
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

    $total = $db->num_rows("SELECT * FROM `groups_account` WHERE $where");
    $products = $db->get_list("SELECT * FROM `groups_account` WHERE $where ORDER BY $orderBy LIMIT $limit OFFSET $start");

foreach ($products as &$product) {
    $countRow = $db->get_row("SELECT COUNT(id) FROM `orders` WHERE `group_id` = '" . $product['id'] . "' AND `service_type` = 'account' AND `status` = 1 ");
    $product['buy_count'] = $countRow['COUNT(id)'] ?? 0;
    $product['type_info'] = service_type_admin($product['type'] ?? '');
}
unset($product);
