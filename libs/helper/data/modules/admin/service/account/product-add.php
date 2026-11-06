<?php
$categories = $db->get_list("SELECT * FROM `category_account` WHERE `id` != '0' AND `status` != 0 ");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('admin/account/product-add', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();
    
    $sort_order       = (int)($_POST['sort_order'] ?? 0);
    $name        = anti_xss($_POST['name'] ?? '');
    $category_id       = (int)($_POST['category_id'] ?? 0);
    $slug        = !empty($_POST['slug']) ? anti_xss($_POST['slug']) : create_slug($name);
    $type        = anti_xss($_POST['type'] ?? '');
    $description = $_POST['description'] ?? '';
    $image       = upload_file('image', 'product');
    $price       = (int)($_POST['price'] ?? 0);
    $buy_fake    = (int)($_POST['buy_fake'] ?? 0);
    $view        = (int)($_POST['view'] ?? 0);
    $nhan        = $_POST['nhan'] ?? '';
    $status      = (int)($_POST['status'] ?? 1);


    $check_name = $db->get_row("SELECT `id` FROM `groups_account` WHERE `slug` = '" . $slug . "' ");
    if ($check_name) {
        msg_redirect('admin/account/product-add', 'error', 'Tên sản phẩm đã tồn tại, vui lòng chọn tên khác.');
    }
    // JSON detail
	$label  = anti_xss($_POST['label'] ?? '');
    // Xử lý mảng data field
    for ($i = 0; $i < count($_POST['data_name']); $i++) {
        $arr_data[] = [
            "id"	=> $i,
            "label"	=> $_POST['data_name'][$i],
            "type"	=> $_POST['data_type'][$i],
            "name"	=> toslug($_POST['data_name'][$i]),
            "value" => $_POST['data_value'][$i],
            "show"	=> $_POST['data_show'][$i]
        ];
    }
    $json_detail = [
        'label'			=> $label,
        'data'			=> $arr_data
    ];

    $full_json_detail = json_encode($json_detail, JSON_UNESCAPED_UNICODE);
    $insert = $db->insert('groups_account', [
        'sort_order' => $sort_order,
        'category_id'=> $category_id,
        'type'       => $type,
        'name'       => $name,
        'slug'       => $slug,
        'price'      => $price,
        'nhan'       => $nhan,
        'image'      => $image,
        'description'=> $description,
        'detail'     => $full_json_detail,
        'buy_fake'   => $buy_fake,
        'view'       => $view,
        'status'     => $status,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    if ($insert) {
        msg_redirect('admin/account/product-list', 'success', 'Thêm thành công.');
    } else {
        msg_redirect('admin/account/product-add', 'error', 'Thêm thất bại.');
    }
}
?>
<style>
@media (max-width: 768px) {
    .mobile-table thead {
        display: none;
    }

    .mobile-table tr {
        display: block;
        margin-bottom: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 8px;
    }

    .mobile-table td {
        display: block;
        width: 100%;
        margin-bottom: 6px;
        text-align: left;
    }

    .mobile-table td::before {
        content: attr(data-label);
        font-weight: 600;
        display: block;
        margin-bottom: 2px;
        color: #555;
    }
    
}
</style>
