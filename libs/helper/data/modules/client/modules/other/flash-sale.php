<?php
$title_flash_sale = anti_xss($db->site('title_flash_sale'));
$status_flash_sale = intval($db->site('status_flash_sale')) === 1;
$flash_sales_list = $db->get_list("SELECT * FROM `flash_sales` ORDER BY `start_time` ASC");
$flash_sales_tabs = $db->get_list("SELECT * FROM `flash_sales` ORDER BY `start_time` ASC");
$flash_sale_products = [];
$has_ongoing_flash_sale = false;

foreach ($flash_sales_list as $key => $flash) {
    $flashStatus = getFlashSaleStatus($flash['start_time'], $flash['end_time']);
    $flash_sales_list[$key]['flash_status'] = $flashStatus;
    $flash_sales_list[$key]['tab_active'] = $flashStatus['status'] == 2;
    $flash_sales_list[$key]['tab_bg_class'] = $flashStatus['status'] == 2 ? 'ws-bg-white' : '';
    $flash_sales_list[$key]['tab_time_class'] = $flashStatus['status'] == 2 ? 'ws-text-red-600' : 'ws-text-white ws-line-through';
    $flash_sales_list[$key]['tab_msg_class'] = $flashStatus['status'] == 2 ? 'ws-text-zinc-800' : 'ws-text-white';
    $flash_sales_list[$key]['start_label'] = getHourAndMinute($flash['start_time']);
    $flash_sales_list[$key]['end_label'] = getHourAndMinute($flash['end_time']);
    if ($flashStatus['status'] == 2) {
        $has_ongoing_flash_sale = true;
    }
}

$show_flash_sale = $status_flash_sale && $has_ongoing_flash_sale;

foreach ($flash_sales_tabs as $key => $flash) {
    $flashStatus = getFlashSaleStatus($flash['start_time'], $flash['end_time']);
    $flash_sale_products[$flash['id']] = [];

    foreach ($db->get_list("SELECT * FROM `flash_sale_products` WHERE `flash_sale_id` = '{$flash['id']}'") as $product) {
        $discountData = getProductFlashSale($flash['id'], $product['product_id'], $product['product_type']);
        $item = [
            'link' => 'javascript:',
            'img' => BASE_URL('assets/img/lazyload.gif'),
            'title' => '',
            'price' => '',
            'extraInfo' => '',
            'flashStatus' => $flashStatus,
        ];

        if ($product['product_type'] === 'account') {
            $account = $db->get_row("
                SELECT * FROM `accounts`
                WHERE `id` = '{$product['product_id']}'
                AND `status` = 1
            ");
            if (!$account) {
                continue;
            }

            $image_acc = '';
            if ($account['type'] === 'tuchon') {
                if (!empty($account['image'])) {
                    $tmp = json_decode($account['image'], true);
                    $image_acc = (is_array($tmp) && isset($tmp[0])) ? $tmp[0] : $account['image'];
                }
            } else {
                $image_acc = getRowRealtime('groups_account', $account['id'], 'image');
            }

            $detail = json_decode($account['detail'], true);
            if (!is_array($detail)) {
                $detail = [];
            }

            $item['link'] = ($flashStatus['status'] == 0) ? 'javascript:' : '/service/account/view-detail/' . $account['id'];
            $item['img'] = $image_acc;
            $item['price'] = format_number(
                (floatval($account['price']) - (floatval($account['price']) * floatval($account['sale']) / 100))
                - floatval($discountData['discountPrice'] ?? 0)
            );

            if (isset($detail[0]['value'])) {
                $item['title'] = decrypt_data($detail[0]['value']);
            }

            $extraInfo = '';
            foreach ($detail as $d) {
                if (($d['show'] ?? '') === 'on') {
                    $value = $d[$d['name']] ?? $d['value'] ?? '';
                    $extraInfo .= '
                    <div class="ws-my-1">
                        <span class="ws-truncate ws-w-full ws-block">
                            <span class="ws-mr-1 ws-text-zinc-700 ws-cap">
                                <i class="ws-relative ws-top-[1px] fa-solid fa-caret-right"></i>
                                ' . htmlspecialchars($d['label']) . ':
                            </span>
                            <span class="ws-font-medium">
                                ' . htmlspecialchars($value) . '
                            </span>
                        </span>
                    </div>';
                }
            }
            $item['extraInfo'] = $extraInfo;
        } elseif ($product['product_type'] === 'groups_account') {
            $group = $db->get_row("SELECT * FROM `groups_account` WHERE id = '{$product['product_id']}'");
            if (!$group) {
                continue;
            }
            $item['link'] = "/service/account/view/{$group['slug']}";
            $item['img'] = BASE_URL($group['image']);
            $item['title'] = $group['name'] ?? 'Danh mục';
            $item['extraInfo'] = "<div class='ws-text-sm ws-text-zinc-700'>" . ($discountData['description'] ?? '') . "</div>";
        } elseif ($product['product_type'] === 'groups_service') {
            $group = $db->get_row("SELECT * FROM `groups_service` WHERE id = '{$product['product_id']}'");
            if (!$group) {
                continue;
            }
            $item['link'] = "/service/service/view/{$group['slug']}";
            $item['img'] = BASE_URL($group['image']);
            $item['title'] = $group['name'] ?? 'Danh mục';
            $item['extraInfo'] = "<div class='ws-text-sm ws-text-zinc-700'>" . ($discountData['description'] ?? '') . "</div>";
        }

        $flash_sale_products[$flash['id']][] = $item;
    }

    $flash_sales_tabs[$key]['flash_status'] = $flashStatus;
    $flash_sales_tabs[$key]['content_active'] = $flashStatus['status'] == 2;
}
