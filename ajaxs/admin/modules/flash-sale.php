<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

if (!isset($data_user) || empty($capbac['status']) || $capbac['status'] !== true) {
    http_response_code(404);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input)) {
        exit(JsonMsg('error', "Không có dữ liệu nào được gửi."));
    }

    foreach ($input as $product) {
        $flashSaleID   = anti_xss($product['flashSaleID'] ?? '');
        $productID     = anti_xss($product['productID'] ?? '');
        $productType   = anti_xss($product['productType'] ?? '');
        $discountPrice = anti_xss($product['discountPrice'] ?? '');
        $description   = anti_xss($product['description'] ?? '');

        // Validate chung
        if (empty($flashSaleID) || empty($productID) || empty($productType)) {
            exit(JsonMsg('error', "Dữ liệu không hợp lệ cho sản phẩm ID: $productID"));
        }

        // Check flash sale tồn tại
        $flashsale = $db->get_row("SELECT * FROM `flash_sales` WHERE `id` = '{$flashSaleID}'");
        if (!$flashsale) {
            exit(JsonMsg('error', "Chiến dịch Flash Sale không tồn tại"));
        }

        // Check theo loại sản phẩm
        if ($productType === 'account') {
            if ($discountPrice === '' || !is_numeric($discountPrice) || $discountPrice < 0) {
                exit(JsonMsg('error', "Giá giảm không hợp lệ cho account ID: $productID"));
            }
            $exists = $db->get_row("SELECT * FROM `accounts` WHERE id = '{$productID}' AND `status`= 1");
            if (!$exists) {
                exit(JsonMsg('error', "Tài khoản ID: $productID không tồn tại hoặc không khả dụng."));
            }
        } elseif ($productType === 'groups_account') {
            if (empty($description)) {
                exit(JsonMsg('error', "Mô tả không được để trống cho Group ID: $productID"));
            }
            $exists = $db->get_row("SELECT * FROM `groups_account` WHERE id = '{$productID}'");
            if (!$exists) {
                exit(JsonMsg('error', "Danh mục Group ID: $productID không tồn tại."));
            }
        } elseif ($productType === 'groups_service') {
            if (empty($description)) {
                exit(JsonMsg('error', "Mô tả không được để trống cho Group ID: $productID"));
            }
            $exists = $db->get_row("SELECT * FROM `groups_service` WHERE id = '{$productID}'");
            if (!$exists) {
                exit(JsonMsg('error', "Danh mục Group ID: $productID không tồn tại."));
            }
        }  else {
            exit(JsonMsg('error', "Loại sản phẩm không hợp lệ: $productType"));
        }

        // Kiểm tra trùng
        if ($db->get_row("SELECT * FROM `flash_sale_products` WHERE flash_sale_id = '{$flashSaleID}' AND product_id = '{$productID}' AND product_type='{$productType}'")) {
            exit(JsonMsg('error', "Sản phẩm {$productType} ID: $productID đã tồn tại trong flash sale này"));
        }

        // Thêm vào flash sale
        $db->insert("flash_sale_products", [
            'flash_sale_id'  => $flashSaleID,
            'product_id'     => $productID,
            'product_type'   => $productType,
            'discount_price' => ($productType === 'account' ? $discountPrice : 0),
            'description'    => ($productType !== 'account' ? $description : null),
            'created_at'     => gettime() // nếu DB DATETIME
        ]);
    }

    echo JsonMsg('success', "Các sản phẩm đã được thêm vào flash sale thành công!");
}
