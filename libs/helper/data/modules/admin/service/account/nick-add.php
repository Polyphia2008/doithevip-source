<?php
$id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        msg_redirect('admin/account/product-list', 'error', 'Thiếu ID nhóm sản phẩm.');
    }

    /* =======================
    * GET GROUP
    * ======================= */
    $group = $db->get_row("SELECT * FROM `groups_account` WHERE `id` = '$id'");
    if (!$group) {
        msg_redirect('admin/account/product-list', 'error', 'Nhóm sản phẩm không tồn tại!');
    }

    /* =======================
    * PARSE DETAIL
    * ======================= */
    $detail = json_decode($group['detail'], true);
    if (empty($detail) || !isset($detail['data']) || !is_array($detail['data'])) {
        msg_redirect('admin/account/product-list', 'error', 'Nhóm sản phẩm chưa cấu hình dữ liệu!');
    }

    /* =======================
    * GROUP INFO
    * ======================= */
    $group_id    = (int)$group['id'];
    $category_id = (int)$group['category_id'];
    $type        = anti_xss($group['type']); // random | tuchon

    /* =======================
    * HANDLE POST
    * ======================= */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        /* ==== CSRF ==== */
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            msg_redirect('reload', 'error', 'CSRF token không hợp lệ!');
        }

        $seller_id = (int)$data_user['id'];
        $buyer_id  = 0;
        $status    = '1';
        $sale = (int)($_POST['sale'] ?? 0);

        /* =======================
        * PRICE + IMAGE
        * ======================= */
        if ($type === 'random') {
            $price = (int)$group['price'];
            $image = $group['image'];
        } else {
            $price = (int)($_POST['price'] ?? 0);

            $images = [];
            if (empty($_FILES['image']['name'][0])) {
                msg_redirect('reload', 'error', 'Vui lòng chọn ít nhất 1 hình ảnh!');
            }

            $total = count($_FILES['image']['name']);

            for ($i = 0; $i < $total; $i++) {
                $img = upload_multiple_file('image', 'accounts', $i);
                if ($img) {
                    $images[] = $img;
                }
            }

            if (empty($images)) {
                msg_redirect('reload', 'error', 'Upload ảnh thất bại!');
            }
            $image = json_encode($images, JSON_UNESCAPED_UNICODE);

        }

        /* =======================
        * RANDOM = MULTI LINE
        * ======================= */
        if ($type === 'random') {

            if (empty($_POST['data'])) {
                msg_redirect('reload', 'error', 'Dữ liệu tài khoản trống!');
            }

            $lines = array_filter(array_map('trim', explode("\n", $_POST['data'])));
            if (empty($lines)) {
                msg_redirect('reload', 'error', 'Dữ liệu tài khoản không hợp lệ!');
            }

            $success = 0;

            foreach ($lines as $line) {

                $account = preg_split("/[|:\/]/", $line);
                if (count($account) < 2) {
                    continue;
                }

                $taikhoan = encrypt_data(anti_xss($account[0]));
                $matkhau  = encrypt_data(anti_xss($account[1]));

                // build detail
                $arr_data = [];
                for ($i = 0; $i < count($detail['data']); $i++) {
                    $arr_data[] = [
                        'id'    => $i,
                        'label' => $detail['data'][$i]['label'],
                        'type'  => $detail['data'][$i]['type'],
                        'name'  => $detail['data'][$i]['name'],
                        'value' => anti_xss($account[$i] ?? ''),
                        'show'  => $detail['data'][$i]['show']
                    ];
                }

                $detail_json = json_encode([
                    'author' => 'PINOCLOUD.VN',
                    'data'   => $arr_data
                ], JSON_UNESCAPED_UNICODE);

                $insert = $db->insert('accounts', [
                    'category_id' => $category_id,
                    'group_id'    => $group_id,
                    'type'        => $type,
                    'seller_id'   => $seller_id,
                    'buyer_id'    => $buyer_id,
                    'taikhoan'    => $taikhoan,
                    'matkhau'     => $matkhau,
                    'detail'      => $detail_json,
                    'image'       => $image,
                    'price'       => $price,
                    'sale'        => $sale,
                    'status'      => $status,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s')
                ]);

                if ($insert) {
                    $success++;
                }
            }

            if ($success === 0) {
                msg_redirect('reload', 'error', 'Không có tài khoản hợp lệ!');
            }

            msg_redirect('reload', 'success', "Đã thêm thành công {$success} tài khoản!");
        }

        /* =======================
        * TỰ CHỌN (1 ACCOUNT)
        * ======================= */
        else {

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
                }
                elseif ($name === 'matkhau') {
                    $matkhau = encrypt_data($post);
                    $value = $matkhau;
                }
                else {
                    $value = $post;
                }

                $arr_data[] = [
                    'id'    => $i,
                    'label' => $field['label'],
                    'type'  => $field['type'],
                    'name'  => $name,
                    'value' => encrypt_data($post),
                    'show'  => $field['show'],
                    $name   => $value
                ];
            }

            $detail_json = json_encode($arr_data, JSON_UNESCAPED_UNICODE);

            $insert = $db->insert('accounts', [
                'category_id' => $category_id,
                'group_id'    => $group_id,
                'type'        => $type,
                'seller_id'   => $seller_id,
                'buyer_id'    => $buyer_id,
                'taikhoan'    => $taikhoan,
                'matkhau'     => $matkhau,
                'detail'      => $detail_json,
                'image'       => $image,
                'price'       => $price,
                'sale'        => $sale,
                'status'      => $status,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]);

            if (!$insert) {
                msg_redirect('reload', 'error', 'Có lỗi xảy ra!');
            }

            msg_redirect('reload', 'success', 'Đăng tài khoản thành công!');
        }
    }
