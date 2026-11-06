<?php
$category_services = $db->get_list("SELECT * FROM `category_service` WHERE `status` = '1' ORDER BY `sort_order` ASC ");
$service_groups_by_category = [];

foreach ($category_services as $category) {
    $groups = $db->get_list("SELECT * FROM `groups_service` WHERE `status` = '1' AND `category_id` = '" . anti_xss($category['id']) . "' ORDER BY `sort_order` ASC");
    foreach ($groups as $key => $group) {
        $sell = $db->get_row("SELECT COUNT(*) AS total FROM `orders` WHERE `group_id` = '" . $group['id'] . "' AND `service_type` = 'service' ");
        $groups[$key]['count_sell'] = intval($sell['total'] ?? 0) + intval($group['buy_fake'] ?? 0);
    }
    $service_groups_by_category[$category['id']] = $groups;
}
