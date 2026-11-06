<?php
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    if (!($row = $db->get_row("SELECT * FROM `flash_sales` WHERE `id` = '{$id}' "))) {
        msg_redirect('admin/modules/flash-sale-list', 'error', 'Flash Sale không tồn tại!');
    }

    // ACCOUNT
    $accountProducts = $db->get_list("
        SELECT a.*
        FROM accounts AS a
        WHERE a.status = 1
        AND a.type = 'tuchon'
        AND NOT EXISTS (
            SELECT 1
            FROM flash_sale_products fsp
            WHERE fsp.product_id = a.id
                AND fsp.flash_sale_id = '{$id}'
                AND fsp.product_type = 'account'
        )
        ORDER BY a.id DESC
    ");
    $accountOptions = [];
    foreach ($accountProducts as $p) {
        $accountOptions[] = [
            'id' => $p['id'],
            'text' => 'ID: '.$p['id'].' - '.decrypt_data($p['taikhoan']).' - Giá: '.format_number($p['price'] - ($p['price'] * $p['sale'] / 100))
        ];
    }

    // GROUP
    $groupProducts = $db->get_list("
        SELECT g.*
        FROM groups_account AS g
        WHERE 1=1
        AND NOT EXISTS (
            SELECT 1
            FROM flash_sale_products fsp
            WHERE fsp.product_id = g.id
                AND fsp.flash_sale_id = '{$id}'
                AND fsp.product_type = 'groups_account'
        )
        ORDER BY g.id DESC
    ");
    $groupOptions = [];
    foreach ($groupProducts as $g) {
        $groupOptions[] = [
            'id' => $g['id'],
            'text' => 'ID: '.$g['id'].' - '.$g['name']
        ];
    }
    
    $groupdichvuProducts = $db->get_list("
        SELECT g.*
        FROM groups_service AS g
        WHERE 1=1
        AND NOT EXISTS (
            SELECT 1
            FROM flash_sale_products fsp
            WHERE fsp.product_id = g.id
                AND fsp.flash_sale_id = '{$id}'
                AND fsp.product_type = 'groups_service'
        )
        ORDER BY g.id DESC
    ");
    $groupdichvuOptions = [];
    foreach ($groupdichvuProducts as $g) {
        $groupdichvuOptions[] = [
            'id' => $g['id'],
            'text' => 'ID: '.$g['id'].' - '.$g['name']
        ];
    }

    $flash_products = $db->get_list("SELECT * FROM `flash_sale_products` WHERE `flash_sale_id`='{$id}' ORDER BY id DESC");
} else {
    msg_redirect('admin/modules/flash-sale-list', 'error', 'Dữ liệu không hợp lệ!');
}
