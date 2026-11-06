<?php
/* =======================
   LẤY SẢN PHẨM
======================= */
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    msg_redirect('admin/account/product-list', 'error', 'Thiếu ID sản phẩm.');
}

$row = $db->get_row("
    SELECT * FROM groups_account 
    WHERE id = '$id'
");
if (!$row) {
    msg_redirect('admin/account/product-list', 'error', 'Sản phẩm không tồn tại.');
}

$detail = json_decode($row['detail'], true);
$data_fields = $detail['data'] ?? [];
$label_old  = $detail['label'] ?? '';
$categories = $db->get_list("SELECT * FROM `category_account` WHERE `id` != '0' AND `status` != 0 ");

/* =======================
   UPDATE
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect("reload", 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $name       = anti_xss($_POST['name'] ?? '');
    $category_id= (int)($_POST['category_id'] ?? 0);
    $slug       = !empty($_POST['slug']) ? anti_xss($_POST['slug']) : create_slug($name);
    $type       = anti_xss($_POST['type'] ?? '');
    $price      = (int)($_POST['price'] ?? 0);
    $nhan       = anti_xss($_POST['nhan'] ?? '');
    $buy_fake   = (int)($_POST['buy_fake'] ?? 0);
    $view       = (int)($_POST['view'] ?? 0);
    $status     = (int)($_POST['status'] ?? 1);
    $description= $_POST['description'] ?? '';

    // Slug trùng (trừ chính nó)
    $check_slug = $db->get_row("
        SELECT id FROM groups_account 
        WHERE slug = '$slug' 
        AND id != '$id'
    ");
    if ($check_slug) {
        msg_redirect("reload", 'error', 'Tên sản phẩm đã tồn tại. Vui lòng đặt tên khác.');
    }

    // LOGIC PRICE
    if ($type !== 'random') {
        $price = 0;
    }

    // Upload ảnh
    $image = $row['image'];
    if (!empty($_FILES['image']['name'])) {
        $newImage = upload_file('image', 'product');
        if ($newImage) {
            $oldPath = $_SERVER["DOCUMENT_ROOT"] . $row['image'];
            if (is_file($oldPath)) @unlink($oldPath);
            $image = $newImage;
        }
    }

    // DATA FIELD
    if (empty($_POST['data_name'])) {
        msg_redirect("reload", 'error', 'Phải có ít nhất 1 trường dữ liệu.');
    }

    $arr_data = [];
    for ($i = 0; $i < count($_POST['data_name']); $i++) {
        $arr_data[] = [
            'id'    => $i,
            'label' => anti_xss($_POST['data_name'][$i]),
            'type'  => $_POST['data_type'][$i],
            'name'  => toslug($_POST['data_name'][$i]),
            'value' => $_POST['data_value'][$i],
            'show'  => $_POST['data_show'][$i]
        ];
    }

    $json_detail = json_encode([
        'label' => anti_xss($_POST['label'] ?? ''),
        'data'  => $arr_data
    ], JSON_UNESCAPED_UNICODE);

    $update = $db->update('groups_account', [
        'sort_order' => $sort_order,
        'category_id'=> $category_id,
        'type'       => $type,
        'name'       => $name,
        'slug'       => $slug,
        'price'      => $price,
        'nhan'       => $nhan,
        'image'      => $image,
        'description'=> $description,
        'detail'     => $json_detail,
        'buy_fake'   => $buy_fake,
        'view'       => $view,
        'status'     => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ], "id='$id'");

    if ($update) {
        msg_redirect('reload', 'success', 'Cập nhật thành công.');
    } else {
        msg_redirect("reload", 'warning', 'Không có thay đổi.');
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
