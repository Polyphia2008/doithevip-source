<?php

$menus = $db->get_list("

    SELECT * FROM `menu`

    WHERE `status` = 1

      AND `menu_type` = 'header'

    ORDER BY `sort_order` ASC, `id` ASC

");



$footerMenus = $db->get_list("SELECT * FROM `menu` WHERE `status` = 1 AND `menu_type` = 'footer' ORDER BY parent_id ASC, sort_order ASC");

$footerData = [];



foreach ($footerMenus as $item) {

    if ($item['parent_id'] == 0) {

        $footerData[$item['id']] = $item;

        $footerData[$item['id']]['children'] = [];

    } elseif (isset($footerData[$item['parent_id']])) {

        $footerData[$item['parent_id']]['children'][] = $item;

    }

}



$site = [

    'title' => $db->site('title'),

    'description' => $db->site('description'),

    'keywords' => $db->site('keywords'),

    'favicon' => $db->site('favicon'),

    'logo_dark' => $db->site('logo_dark'),

    'logo_light' => $db->site('logo_light'),

    'theme_color_top' => $db->site('theme_color_top'),

    'theme_color_footer' => $db->site('theme_color_footer'),

    'phone' => $db->site('phone'),

    'email' => $db->site('email'),

    'copyright' => $db->site('copyright'),

    'cf_enable' => intval($db->site('cf_enable')),

    'cf_site_key' => $db->site('cf_site_key'),

    'google_client_id' => $db->site('google_client_id'),

    'google_client_secret' => $db->site('google_client_secret'),

    'facebook_client_id' => $db->site('facebook_client_id'),

    'facebook_client_secret' => $db->site('facebook_client_secret'),

    'status_flash_sale' => intval($db->site('status_flash_sale')),

    'title_flash_sale' => anti_xss($db->site('title_flash_sale')),

    'status_noti_sell' => intval($db->site('status_noti_sell')),

    'notice_popup' => $db->site('notice_popup'),

];

