<?php
$where = " `id` > 0";
    
    if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
        $where .= " AND `id` = '{$id}'";
    }
    if (!empty($_GET['name'])) {
        $name = anti_xss($_GET['name']);
        $where .= " AND `name` LIKE '%{$name}%'";
    }
    if (isset($_GET['status']) && $_GET['status'] !== '' && is_numeric($_GET['status'])) {
        $status = intval($_GET['status']);
        $where .= " AND `status` = '{$status}'";
    }

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `category_account` WHERE $where");
    $products = $db->get_list("SELECT * FROM `category_account` WHERE $where ORDER BY `sort_order` ASC LIMIT $limit OFFSET $start");
