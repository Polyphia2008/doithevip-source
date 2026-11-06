<?php
if (empty($_GET['id'])) {
    msg_redirect('/', 'error', 'Thiếu dữ liệu!');
}

$row = $db->get_row(" SELECT * FROM `accounts` WHERE `id` = '" . intval($_GET['id']) . "' ");
if (!$row) {
    msg_redirect('/', 'error', 'Tài khoản không tồn tại trong hệ thống!');
}

if ($row['status'] != 1) {
    msg_redirect('/', 'error', 'Tài khoản này đã bán!');
}

$detail = json_decode($row['detail'], true);
$check = $db->get_row("SELECT * FROM `groups_account` WHERE `id` = '{$row['group_id']}'");
$discountPrice = getActiveProductFlashSale($_GET['id'], 'account');
$final_price = (floatval($row['price']) - (floatval($row['price']) * floatval($row['sale']) / 100)) - floatval($discountPrice['discountPrice'] ?? 0);

$old_images = [];
if (!empty($row['image'])) {
    $tmp = json_decode($row['image'], true);
    if (is_array($tmp)) {
        $old_images = $tmp;
    } else {
        $old_images = [$row['image']];
    }
}

if (empty($old_images)) {
    $old_images = ['assets/images/no-image.png'];
}

$price_after_sale = floatval($row['price']) - (floatval($row['price']) * floatval($row['sale']) / 100);
$price_after_sale_formatted = format_number($price_after_sale);
$price_original_formatted = format_number($row['price']);
$final_price_formatted = format_number($final_price);
$discount_amount_formatted = format_number($discountPrice['discountPrice'] ?? 0);
$is_flash_sale = ($discountPrice['flashSaleStatus'] ?? '') === 'ongoing';
$discount_label = $is_flash_sale ? $discount_amount_formatted : ($row['sale'] . '%');

$detail_rows = [];
$count = 0;
if (!is_array($detail)) {
    $detail = [];
}
foreach ($detail as $item) {
    if (($item['show'] ?? '') === 'on') {
        $detail_rows[] = [
            'label' => htmlspecialchars($item['label'] ?? ''),
            'value' => htmlspecialchars($item[$item['name']] ?? $item['value'] ?? ''),
            'row_class' => $count % 2 == 0 ? '' : 'ws-bg-zinc-50',
            'modal_row_class' => 'ws-bg-zinc-50 ws-col-span-8 md:ws-col-span-9 ws-py-2 ws-px-2 ws-text-red-600',
        ];
        $count++;
    }
}
