<?php
/* =======================
     * GET ACCOUNT ID
     * ======================= */
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        msg_redirect('admin/account/product-list', 'error', 'Thiếu ID tài khoản!');
    }

    /* =======================
     * GET ACCOUNT
     * ======================= */
    $account = $db->get_row("SELECT * FROM `accounts` WHERE `id` = '$id'");
    if (!$account) {
        msg_redirect('admin/account/product-list', 'error', 'Tài khoản không tồn tại!');
    }
    /* =======================
    * PARSE IMAGE
    * ======================= */
    $old_images = [];
    if (!empty($account['image'])) {
        $tmp = json_decode($account['image'], true);
        if (is_array($tmp)) {
            $old_images = $tmp;
        } else {
            // Ảnh cũ dạng string (tương thích dữ liệu cũ)
            $old_images = [$account['image']];
        }
    }
    /* =======================
     * GET GROUP
     * ======================= */
    $group = $db->get_row("SELECT * FROM `groups_account` WHERE `id` = '{$account['group_id']}'");
    if (!$group) {
        msg_redirect('admin/account/product-list', 'error', 'Nhóm sản phẩm không tồn tại!');
    }

    /* =======================
     * PARSE DETAIL
     * ======================= */
    $detail = json_decode($group['detail'], true);
    if (empty($detail['data'])) {
        msg_redirect('admin/account/product-list', 'error', 'Nhóm sản phẩm chưa cấu hình dữ liệu!');
    }

    $old_detail_raw = json_decode($account['detail'], true);

    /* CHUẨN HÓA DETAIL */
    if (isset($old_detail_raw['data']) && is_array($old_detail_raw['data'])) {
        $old_detail = $old_detail_raw['data'];
    } else {
        $old_detail = is_array($old_detail_raw) ? $old_detail_raw : [];
    }

    $form_fields = [];
    for ($i = 0; $i < count($detail['data']); $i++) {
        $field = $detail['data'][$i];
        $name = $field['name'];
        $rawValue = $old_detail[$i]['value'] ?? '';
        if (in_array($name, ['taikhoan', 'matkhau'], true)) {
            $value = $rawValue ? decrypt_data($rawValue) : '';
        } else {
            $value = $rawValue;
        }
        $form_fields[] = [
            'field' => $field,
            'name' => $name,
            'value' => $value,
        ];
    }


    /* =======================
     * GROUP INFO
     * ======================= */
    $type        = $group['type']; // random | tuchon
    $category_id = (int)$group['category_id'];

    /* =======================
     * HANDLE POST
     * ======================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /* ==== CSRF ==== */
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('reload', 'error', 'CSRF token không hợp lệ!');
        }

        /* =======================
         * PRICE - SALE
         * ======================= */
        if ($type === 'random') {
            $price = (int)$account['price'];
        } else {
            $price = (int)($_POST['price'] ?? $account['price']);
        }
        $sale = (int)($_POST['sale'] ?? $account['sale']);
        /* =======================
        * IMAGE (UPLOAD LÀ ĐÈ)
        * ======================= */
        if (!empty($_FILES['image']['name'][0])) {

            $images = [];
            $total  = count($_FILES['image']['name']);

            for ($i = 0; $i < $total; $i++) {
                $img = upload_multiple_file('image', 'accounts', $i);
                if ($img) {
                    $images[] = $img;
                }
            }

            if (empty($images)) {
                msg_redirect('reload', 'error', 'Upload ảnh thất bại!');
            }

            // upload mới = ghi đè toàn bộ ảnh cũ
            $image = json_encode($images, JSON_UNESCAPED_UNICODE);

        } else {
            // không upload = giữ ảnh cũ
            $image = $account['image'];
        }

        /* =======================
         * BUILD DETAIL
         * ======================= */
        $arr_data = [];
        $taikhoan = '';
        $matkhau  = '';

        for ($i = 0; $i < count($detail['data']); $i++) {

            $field = $detail['data'][$i];
            $name  = $field['name'];
            $post  = anti_xss($_POST[$name] ?? '');

            if ($name === 'taikhoan') {
                $taikhoan = encrypt_data($post);
                $value = $taikhoan;
            } elseif ($name === 'matkhau') {
                $matkhau = encrypt_data($post);
                $value = $matkhau;
            } else {
                $value = $post;
            }

            $arr_data[] = [
                'id'    => $i,
                'label' => $field['label'],
                'type'  => $field['type'],
                'name'  => $name,
                'value' => $value,
                'show'  => $field['show']
            ];
        }

        $detail_json = json_encode([
            'author' => 'PINOCLOUD.VN',
            'data'   => $arr_data
        ], JSON_UNESCAPED_UNICODE);

        /* =======================
         * UPDATE
         * ======================= */
        $update = $db->update('accounts', [
            'taikhoan'   => $taikhoan ?: $account['taikhoan'],
            'matkhau'    => $matkhau ?: $account['matkhau'],
            'detail'     => $detail_json,
            'image'      => $image,
            'price'      => $price,
            'sale'       => $sale,
            'updated_at' => date('Y-m-d H:i:s')
        ], "`id` = '$id'");

        if (!$update) {
            msg_redirect('reload', 'error', 'Cập nhật thất bại!');
        }

        msg_redirect('reload', 'success', 'Cập nhật tài khoản thành công!');
    }
