<?php

$fee_percent = floatval($db->site('fees'));

$category_accounts = $db->get_list("SELECT * FROM `category_account` WHERE `status` = '1' ORDER BY `sort_order` ASC ");

$account_groups_by_category = [];



foreach ($category_accounts as $category) {

    $groups = $db->get_list("SELECT * FROM `groups_account` WHERE `status` = '1' AND `category_id` = '" . anti_xss($category['id']) . "' ORDER BY `sort_order` ASC");

    foreach ($groups as $key => $group) {

        $acc = $db->get_row("SELECT COUNT(*) AS total FROM `accounts` WHERE `group_id` = '" . $group['id'] . "' AND `status`= 1");

        $groups[$key]['count_account'] = intval($acc['total'] ?? 0);

        if ($group['type'] === 'random') {

            $fees = floatval($group['price']) * ($fee_percent / 100);

            $groups[$key]['price_with_fee'] = floatval($group['price']) + $fees;

            $groups[$key]['price_original'] = floatval($groups[$key]['price_with_fee']) * 2;

        }

    }

    $account_groups_by_category[$category['id']] = $groups;

}

