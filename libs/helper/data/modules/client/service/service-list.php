<?php

$slug = anti_xss($_GET['slug'] ?? null);



if (!$slug) {

    msg_redirect('/', 'error', 'Sản phẩm không hợp lệ.');

}



$row = $db->get_row("SELECT * FROM `groups_service` WHERE `slug` = '{$slug}' AND `status` = 1");

if (!$row) {

    msg_redirect('/', 'error', 'Sản phẩm không tồn tại.');

}



$db->cong('groups_service', 'view', 1, " `id` = '" . $row['id'] . "' ");



$details = json_decode($row['detail'], true);

$filter_fields = isset($details['data']) ? $details['data'] : [];



$fee_percent = floatval($db->site('fees'));

$pack_services = $db->get_list("SELECT * FROM `pack_service` WHERE `group_id` = '" . $row['id'] . "'' AND `status` = 1 ORDER BY `sort_order` ASC ");



$firstItem = true;

foreach ($pack_services as $key => $pack) {

    $fees = floatval($pack['price']) * ($fee_percent / 100);

    $pack_services[$key]['final_price'] = floatval($pack['price']) + $fees;

    $pack_services[$key]['selected_class'] = $firstItem ? 'selected-item' : '';

    $firstItem = false;

}

