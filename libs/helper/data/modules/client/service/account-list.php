<?php

$slug = anti_xss($_GET['slug'] ?? null);



if (!$slug) {

    msg_redirect('/', 'error', 'Sản phẩm không hợp lệ.');

}



$row = $db->get_row("SELECT * FROM `groups_account` WHERE `slug` = '{$slug}' AND `status` = 1");

if (!$row) {

    msg_redirect('/', 'error', 'Sản phẩm không tồn tại.');

}



$detail_query = json_decode($row['detail'], true);

$filter_fields = $detail_query['data'] ?? [];

$countShow = 0;



foreach ($filter_fields as $item) {

    if (($item['show'] ?? '') === 'on') {

        $countShow++;

    }

}



$db->cong('groups_account', 'view', 1, " `id` = '" . $row['id'] . "' ");



$show_noti_sell = intval($db->site('status_noti_sell')) === 1;

$group_orders = $db->get_list("SELECT * FROM `orders` WHERE `service_type` = 'account' AND `group_id` = {$row['id']} ORDER BY `id` DESC LIMIT 50");

$has_group_orders = count($group_orders) > 0;



foreach ($group_orders as $key => $order) {

    $serviceJson = json_decode($order['service_json'], true) ?: [];

    $group_orders[$key]['service_id'] = $serviceJson['service_id'] ?? 0;

    $group_orders[$key]['service_type_text'] = service_type($order['service_type'])['text'];

    $group_orders[$key]['amount_formatted'] = format_number($order['amount']);

    $group_orders[$key]['time_ago'] = timeAgo2($order['created_at']);

}

