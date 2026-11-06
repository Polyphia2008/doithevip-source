<?php
$id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        msg_redirect('admin/account/product-list', 'error', 'Thiếu ID sản phẩm.');
    }

    $row = $db->get_row("SELECT * FROM `groups_account` WHERE `id` = '{$id}'");
    if (!$row) {
        msg_redirect('admin/account/product-list', 'error', 'Danh mục không tồn tại.');
    }

    $where = " `id` > 0 AND `group_id` = ".$id."";
    $orderBy = "`id` DESC";

    if (!empty($_GET['id_acc']) && is_numeric($_GET['id_acc'])) {
        $id_acc = intval($_GET['id_acc']);
        $where .= " AND `id` = '{$id_acc}'";
    }
    if (!empty($_GET['seller_id']) && is_numeric($_GET['seller_id'])) {
        $seller_id = intval($_GET['seller_id']);
        $where .= " AND `seller_id` = '{$seller_id}'";
    }
    if (!empty($_GET['buyer_id']) && is_numeric($_GET['buyer_id'])) {
        $buyer_id = intval($_GET['buyer_id']);
        $where .= " AND `buyer_id` = '{$buyer_id}'";
    }
    if (!empty($_GET['taikhoan'])) {
        $taikhoan = anti_xss($_GET['taikhoan']);
        $taikhoan_encrypt = encrypt_data($taikhoan);
        $where .= " AND `taikhoan` = '{$taikhoan_encrypt}'";
    }

    if (!empty($_GET['matkhau'])) {
        $matkhau = anti_xss($_GET['matkhau']);
        $matkhau_encrypt = encrypt_data($matkhau);
        $where .= " AND `matkhau` = '{$matkhau_encrypt}'";
    }
    if (!empty($_GET['name'])) {
        $name = anti_xss($_GET['name']);
        $where .= " AND `name` LIKE '%{$name}%'";
    }
    if (!empty($_GET['type'])) {
        $type = anti_xss($_GET['type']);
        $where .= " AND `type` = '{$type}'";
    }
    if (!empty($_GET['price_sort'])) {
        if ($_GET['price_sort'] === 'price_asc') {
            $orderBy = "`price` ASC";
        } elseif ($_GET['price_sort'] === 'price_desc') {
            $orderBy = "`price` DESC";
        }
    }

    if (isset($_GET['status']) && $_GET['status'] !== '' && is_numeric($_GET['status'])) {
        $status = intval($_GET['status']);
        $where .= " AND `status` = '{$status}'";
    }

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `accounts` WHERE $where");
    $products = $db->get_list("SELECT * FROM `accounts` WHERE $where ORDER BY $orderBy LIMIT $limit OFFSET $start");

foreach ($products as $key => $product) {
    $products[$key]['type_info'] = service_type_admin($product['type'] ?? '');

    if ($product['type'] === 'tuchon') {
        $imageAcc = '';
        if (!empty($product['image'])) {
            $tmp = json_decode($product['image'], true);
            if (is_array($tmp) && isset($tmp[0])) {
                $imageAcc = $tmp[0];
            } else {
                $imageAcc = $product['image'];
            }
        }
        $products[$key]['thumbnail'] = $imageAcc;
    } else {
        $products[$key]['thumbnail'] = getRowRealtime('groups_account', $product['group_id'], 'image');
    }

    $products[$key]['category_name'] = anti_xss(getRowRealtime('category_account', $product['category_id'], 'name'));
    $products[$key]['group_name'] = anti_xss(getRowRealtime('groups_account', $product['group_id'], 'name'));
    $products[$key]['seller_username'] = anti_xss(getRowRealtime('users', $product['seller_id'], 'username') ?? 'Không rõ');
    $products[$key]['buyer_username'] = anti_xss(getRowRealtime('users', $product['buyer_id'], 'username') ?? 'Không rõ');
}
