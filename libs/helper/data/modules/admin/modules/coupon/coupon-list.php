<?php
$where = " `id` > 0 ";

    if (!empty($_GET['code'])) {
        $code = anti_xss($_GET['code']);
        $where .= " AND `code` LIKE '%{$code}%'";
    }
    if (!empty($_GET['amount'])) {
        if ($_GET['amount'] == '1') {
            $orderBy = "`amount` ASC";
        } elseif ($_GET['amount'] == '2') {
            $orderBy = "`amount` DESC";
        }
    }
    if (!empty($_GET['discount'])) {
        if ($_GET['discount'] == '1') {
            $orderBy = "`discount` ASC";
        } elseif ($_GET['discount'] == '2') {
            $orderBy = "`discount` DESC";
        }
    }

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 20;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `coupons` WHERE $where");
    $coupons = $db->get_list("SELECT * FROM `coupons` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

foreach ($coupons as $key => $row) {
    $remaining = intval($row['amount']) - intval($row['used']);
    if (intval($row['used']) >= intval($row['amount'])) {
        $coupons[$key]['usage_html'] = '<span style="color:red">Đã sử dụng hết</span>';
    } else {
        $coupons[$key]['usage_html'] = '<span style="color:green">Còn ' . format_number($remaining) . ' lượt sử dụng</span>';
    }

    $coupons[$key]['product_badges'] = [];
    if (!empty($row['product_id'])) {
        $productIds = json_decode($row['product_id'], true) ?: [];
        foreach ($productIds as $productId) {
            if (intval($row['type']) === 0) {
                $coupons[$key]['product_badges'][] = [
                    'class' => 'badge-outline-danger',
                    'name' => getRowRealtime('groups_account', $productId, 'name'),
                ];
            } else {
                $coupons[$key]['product_badges'][] = [
                    'class' => 'badge-outline-success',
                    'name' => getRowRealtime('groups_service', $productId, 'name'),
                ];
            }
        }
    }
}
