<?php

$show_noti_sell = intval($db->site('status_noti_sell')) === 1;

$noti_orders = $db->get_list("SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 50");



foreach ($noti_orders as $key => $order) {

    $serviceJson = json_decode($order['service_json'], true) ?: [];

    $noti_orders[$key]['service_id'] = $serviceJson['service_id'] ?? 0;

    $noti_orders[$key]['service_type_text'] = service_type($order['service_type'])['text'];

    $noti_orders[$key]['amount_formatted'] = format_number($order['amount']);

    $noti_orders[$key]['time_ago'] = timeAgo2($order['created_at']);

}

