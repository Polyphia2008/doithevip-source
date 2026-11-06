<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    
    if (isset($_POST['type']) && $_POST['type'] === 'deposit') {

        // CSRF
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ!'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if (!check_login()) {
            die(JsonMsg('error', 'Vui lòng đăng nhập !'));
        }

        $net_amount = anti_xss($_POST['net_amount']);
        $payment_method = anti_xss($_POST['payment_method'] ?? '');
        if (empty($payment_method)) {
            die(JsonMsg('error', 'Vui lòng chọn cổng thanh toán.'));
        }

        $min_amount   = $db->site('deposit_min_amount');
        $max_amount   = $db->site('deposit_max_amount');
        $daily_limit  = $db->site('deposit_daily_limit');

        if ($net_amount < $min_amount) {
            die(JsonMsg('error', 'Số tiền nạp tối thiểu là ' . format_number($min_amount) . 'đ.'));
        }
        if ($net_amount > $max_amount) {
            die(JsonMsg('error', 'Số tiền nạp tối đa là ' . format_number($max_amount) . 'đ.'));
        }

        // Tính tổng nạp trong ngày của user
        $todayTotal = $db->get_row("
            SELECT SUM(`net_amount`) as total 
            FROM `invoices` 
            WHERE `user_id` = '{$data_user['id']}' 
            AND `order_type` = 'deposit'
            AND `status` = 'paid'
            AND DATE(`created_at`) = CURDATE()
        ")['total'] ?? 0;

        if (($todayTotal + $net_amount) > $daily_limit) {
            die(JsonMsg('error', 'Bạn đã vượt quá hạn mức nạp trong ngày (' . format_number($daily_limit) . 'đ).'));
        }

        $bank_info = $db->get_row("SELECT * FROM `bankings` WHERE `bankName` = '{$payment_method}' AND `invoice_pay` = 1");
        if (!$bank_info) {
            die(JsonMsg('error', 'Cổng thanh toán không hợp lệ.'));
        }

        // Tính giá & phí
        $feePercent = $bank_info['fees_percent'];        // % phí
        $fixed_fee  = $bank_info['fixed_fee'];           // phí cố định
        $fees       = ($net_amount * ($feePercent / 100)) + $fixed_fee;
        $pay_amount = $net_amount + $fees;               // Tổng tiền khách phải trả

        $order_code = generate_code_with_prefix('D', 14);

        // Data chung cho invoice
        $invoiceData = [
            'order_code'         => $order_code,
            'order_type'         => 'deposit',
            'user_id'            => $data_user['id'],
            'net_amount'         => $net_amount,
            'fees'               => $feePercent,
            'pay_amount'         => $pay_amount,
            'paygate'            => $payment_method,
            'description'        => "Nạp " . format_number($net_amount) . "đ vào tài khoản",
            'status'             => 'unpaid',
            'status_service'     => '100',
            'created_at'         => gettime(),
            'updated_at'         => gettime(),
        ];
        if ($bank_info) {
            $invoiceData['bank_info'] = json_encode($bank_info, JSON_UNESCAPED_UNICODE);
        }

        // Insert
        $INSERT = $db->insert("invoices", $invoiceData);
        if ($INSERT) {
            insert_log($data_user['id'], "Thực hiện tạo hóa nạp " . format_number($net_amount) . "đ vào tài khoản");
            die(json_encode(['status' => 'success', 'msg'    => 'Tạo hóa đơn nạp tiền thành công.', 'url'    => "invoice/{$order_code}"]));
        } else {
            die(JsonMsg('error', 'Tạo hóa đơn nạp tiền thất bại.'));
        }
    }
    if (isset($_POST['type']) && $_POST['type'] === 'deposit_card') {

        // CSRF
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ!'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if (!check_login()) {
            die(JsonMsg('error', 'Vui lòng đăng nhập !'));
        }

        $payment_method = anti_xss($_POST['loaithe'] ?? '');
        $pay_amount = anti_xss($_POST['menhgia']);
        $seri = anti_xss($_POST['seri']);
        $pin = anti_xss($_POST['pin']);
        
        if (empty($payment_method)) {
            die(JsonMsg('error', 'Vui lòng chọn loại thẻ !'));
        }
        if (empty($pay_amount)) {
            die(JsonMsg('error', 'Vui lòng chọn mệnh giá !'));
        }
        if (empty($seri)) {
            die(JsonMsg('error', 'Vui lòng nhập seri thẻ !'));
        }
        if (empty($pin)) {
            die(JsonMsg('error', 'Vui lòng nhập mã thẻ !'));
        }
        if (strlen($seri) < 5 || strlen($pin) < 5) {
            die(JsonMsg('error', 'Mã thẻ hoặc seri không đúng định dạng!'));
        }
        if (check_format_card($payment_method, $seri, $pin)['status'] != true) {
            die(JsonMsg('error', check_format_card($payment_method, $seri, $pin)['msg']));
        }
        $card_info = $pin . '|' . $seri;
        if ($db->get_row("SELECT * FROM `invoices` WHERE `card_info` = '{$card_info}' ")) {
            die(JsonMsg('error', 'Mã thẻ và seri đã tồn tại trong hệ thống!'));
        }
        if($db->num_rows("SELECT * FROM `invoices` WHERE `user_id`='".$data_user['id']."' AND `status` = 'xuly''") > 5){
            die(JsonMsg('error', 'Bạn đã có quá nhiều yêu cầu nạp thẻ chưa được xử lý!'));
        }
        if(
            $db->num_rows("SELECT * FROM `invoices` WHERE `status` = 'cancel' AND `ip` = '".$data_user['ip']."' AND `created_at` >= DATE(NOW()) AND `created_at` < DATE(NOW()) + INTERVAL 1 DAY  ") - 
            $db->num_rows("SELECT * FROM `invoices` WHERE `status` = 'paid' AND `ip` = '".$data_user['ip']."' AND `created_at` >= DATE(NOW()) AND `created_at` < DATE(NOW()) + INTERVAL 1 DAY  ") >= 10)
        {
            die(JsonMsg('error', 'Bạn đã bị chặn sử dụng chức năng nạp thẻ trong 1 ngày'));
        }
        $request_id = generate_code_with_prefix('D', 14);
        
        $data_napthe = napthe($payment_method, $pay_amount, $seri, $pin, $request_id);
        // Dữ liệu insert
        
        $feePercent = (float) $db->site('fees_napthecao') ?? 0;
        $fees       = $pay_amount * ($feePercent / 100);
        $net_amount = $pay_amount - $fees;
        $invoiceData = [
            'request_id'         => $request_id,
            'order_code'         => $request_id,
            'order_type'         => 'deposit',
            'user_id'            => $data_user['id'],
            'ip'                 => myip(),
            'net_amount'         => $net_amount,
            'fees'               => $feePercent,
            'pay_amount'         => $pay_amount,
            'paygate'            => $payment_method,
            'description'        => "Nạp " . format_number($net_amount) . "đ vào tài khoản",
            'card_info'          => $card_info,
            'status'             => 'unpaid',
            'status_service'     => '100',
            'created_at'         => gettime(),
            'updated_at'         => gettime(),
        ];
        if (isset($data_napthe['status'])) {
            if ($data_napthe['status'] == '99') {                
                // Insert
                $INSERT = $db->insert("invoices", $invoiceData);
                if ($INSERT) {
                    insert_log($data_user['id'], 'Thực hiện nạp thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ vào tài khoản');
                    $LAST_INSERT_ID = $db->get_row("SELECT * FROM `invoices` WHERE `request_id` = '$request_id' ");
                    $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    insert_cards_log($LAST_INSERT_ID['id'], $log);
                    die(json_encode(['status' => 'success', 'msg'    => 'Đã gửi thẻ lên hệ thống, vui lòng chờ xử lý.', 'url'    => "/wallet/history/deposit?paygate={$payment_method}"]));
                } else {
                    die(JsonMsg('error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin'));
                }
            } elseif ($data_napthe['status'] == 2 || $data_napthe['status'] == 3) {
                die(JsonMsg('error', 'Thẻ lỗi'));
            } elseif ($data_napthe['status'] == 4) {
                die(JsonMsg('error', 'Hệ thống đang bảo trì, vui lòng thử lại sau'));
            } elseif ($data_napthe['status'] == 100) {
                die(JsonMsg('error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin'));
            } else{
                $INSERT = $db->insert("invoices", $invoiceData);
                if ($INSERT) {
                    insert_log($data_user['id'], 'Thực hiện nạp thẻ cào ' . $payment_method . ' - ' . format_number($net_amount) . ' đ vào tài khoản');
                    $LAST_INSERT_ID = $db->get_row("SELECT * FROM `invoices` WHERE `request_id` = '$request_id' ");
                    $log = json_encode($data_napthe, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    insert_cards_log($LAST_INSERT_ID['id'], $log);
                    die(JsonMsg('error', $data_napthe['message']));
                } else {
                    die(JsonMsg('error', 'Nạp thẻ thất bại, vui lòng liên hệ Admin'));
                }
            }
        }else{
            die(JsonMsg('error', 'Đã xảy ra lỗi khi gửi dữ liệu!'));
        }
            
    }
    if (isset($_POST['type']) && $_POST['type'] === 'mua-tai-khoan') {
        $id =  intval($_POST['id'] ?? 0);
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ!'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if (!check_login()) {
            die(JsonMsg('error', 'Vui lòng đăng nhập !'));
        }
        if (!$db->get_row(" SELECT * FROM `users` WHERE `id` = '".$data_user['id']."' AND `banned` = '0'' ")) {
            die(JsonMsg('error', 'Dữ liệu người dùng không hợp lệ!'));
        }
        if ($id <= 0) {
            die(JsonMsg('error', 'Thiếu ID!'));
        }

        $row = $db->get_row(" SELECT * FROM `accounts` WHERE `id` = '$id''");
        if (!$row) {
            die(JsonMsg('error', 'Không tìm thấy tài khoản này trong hệ thống!'));
        }
        if ($row['status'] == 0) {
            die(JsonMsg('error', 'Tài khoản này đã bán, vui lòng mua tài khoản khác !'));
        }
        // Data Insert
        // Start Tính Tiền
        $price = $row['price'] - (($row['price'] * $row['sale']) / 100);
        $price = $price - getActiveProductFlashSale($id, 'account')['discountPrice'];
        if (!empty($_POST['coupon'])) {
            $discount = checkCoupon($row["group_id"], anti_xss($_POST['coupon']), $price);
        }
        // Tính tiền coupon
        if (isset($discount)) {
            $price = $price - (($price * $discount) / 100);
        }
        $feePercent = (float) $db->site('fees');
        $fees       = $price * ($feePercent / 100);
        $net_amount = $price + $fees;
        // End Tính Tiền
        $order_code = generate_code_with_prefix('B', 14);
        $order_type = "buy";
        $module = "account";
        $description = "Mua tài khoản ID #{$id}";
        $description_hoantien = "Hoàn tiền mua tài khoản ID #{$id}";
        $description_congtien_seller = "Cộng tiền bán tài khoản #{$id}";
        $status = "paid";
        $status_service = 1;
        $payment_method = "balance";
        if ($net_amount > $data_user['money']) {
            die(JsonMsg('error', 'Số dư không đủ để thanh toán.'));
        }
        $TRUTIEN = TruTien($data_user['id'], $net_amount, $description, $order_code);
        if ($TRUTIEN) {
            if (getRowRealtime("users", $data_user['id'], "money") < 0) {
                Banned($data_user['id'], "Tài khoản đã bị khoá vì số dư không hợp lệ.");
                die(JsonMsg('error', 'Tài khoản đã bị khoá vì số dư không hợp lệ.'));
            }
            // Service JSON
            $service_json = json_encode([
                'service_id'            => $id,
                'service_additional'    => [
                    'category_id'       => $row['category_id'],
                    'group_id'          => $row['group_id'],
                    'type'              => $row['type'],
                    'seller_id'         => $row['seller_id'],
                    'buyer_id'          => $data_user['id'],
                    'image'             => $row['image'],
                    'price'             => $row['price'],
                    'sale'              => $row['sale'],
                ],
            ], JSON_UNESCAPED_UNICODE);
            // Data chung cho invoice
            $invoiceData = [
                'order_code'         => $order_code,
                'order_code_service' => $order_code,
                'order_type'         => $order_type,
                'module'             => $module,
                'service_json'       => $service_json,
                'user_id'            => $data_user['id'],
                'net_amount'         => $net_amount,
                'fees'               => $feePercent,
                'pay_amount'         => $net_amount,
                'paygate'            => $payment_method,
                'description'        => $description,
                'status'             => $status,
                'status_service'     => $status_service,
                'created_at'         => gettime(),
                'updated_at'         => gettime(),
            ];
        
            // Insert Hóa Đơn
            $INSERT_INVOICE = $db->insert("invoices", $invoiceData);
            if ($INSERT_INVOICE) {
                insert_log($data_user['id'], "Thực hiện tạo hóa đơn mua tài khoản ID #{$id}");
                // Cập Nhật Tài Khoản (Trạng Thái, Người Mua, Thời Gian)
                $db->update("accounts", [
                    'status' => 0,
                    'buyer_id'   => $data_user['id'],
                    'updated_at' => gettime(),
                ], " `id` = '" . $row['id'] . "''");
                // Insert Order
                $orderData = [
                    'user_id'      => $data_user['id'],
                    'order_code'   => $order_code,
                    'category_id'  => $row['category_id'],
                    'group_id'     => $row['group_id'],
                    'service_type' => $module,
                    'service_json' => $service_json,
                    'status'       => '1',
                    'amount'       => $net_amount,
                    'created_at'   => gettime(),
                    'updated_at'   => gettime()
                ];
                $INSERT_ORDER = $db->insert("orders", $orderData);
                if($INSERT_ORDER){
                    // Cộng Tiền Seller
                    CongTien($row['seller_id'], $net_amount, $description_congtien_seller, $order_code);
                    // XÓA ACCOUNT ĐÃ BÁN KHỎI FLASH SALE
                    $db->remove('flash_sale_products', "product_id = '{$row['id']}' AND `product_type` = 'account'");
                    die(JsonMsg('success', 'Thanh toán thành công, chuyển hướng đến lịch sử mua !'));
                }else {
                    // Hoàn Tiền INSERT_ORDER Failed
                    CongTien($data_user['id'], $net_amount, $description_hoantien, $order_code);
                    die(JsonMsg('error', 'Có lỗi xảy ra trong quá trình thanh toán, vui lòng liên hệ Admin !'));
                }
            } else {
                // Hoàn Tiền INSERT_INVOICE Failed
                CongTien($data_user['id'], $net_amount, $description_hoantien, $order_code);
                die(JsonMsg('error', 'Có lỗi xảy ra trong quá trình thanh toán, vui lòng liên hệ Admin !'));
            }
        }else{
            die(JsonMsg('error', 'Thanh toán thất bại, vui lòng thử lại.'));
        }
    }
    if (isset($_POST['type']) && $_POST['type'] === 'mua-dich-vu') {
        // 1. KIỂM TRA BẢO MẬT & ĐĂNG NHẬP
        if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
            die(JsonMsg('error', 'CSRF token không hợp lệ!'));
        }
        if($db->site("status_demo") == 1){
            die(JsonMsg('error', 'Đây là trang web demo, không thể thực hiện chức năng này!'));
        }
        if (!check_login()) {
            die(JsonMsg('error', 'Vui lòng đăng nhập !'));
        }
        if (!$db->get_row(" SELECT * FROM `users` WHERE `id` = '".$data_user['id']."' AND `banned` = '0'' ")) {
            die(JsonMsg('error', 'Dữ liệu người dùng không hợp lệ!'));
        }
    
        // 2. LẤY DỮ LIỆU CƠ BẢN
        $id   = intval($_POST['dichvu'] ?? 0);
        $note = anti_xss($_POST['note'] ?? '');
        
        if (empty($id)) {
            die(JsonMsg('error', 'Vui lòng chọn gói dịch vụ cần mua !'));
        }
    
        // 3. LẤY THÔNG TIN GÓI DỊCH VỤ (PACK)
        $row = $db->get_row(" SELECT * FROM `pack_service` WHERE `id` = '$id' AND `status` = 1'");
        if (!$row) {
            die(JsonMsg('error', 'Không tìm thấy gói này trong hệ thống hoặc đang tắt!'));
        }
    
        // 4. LẤY THÔNG TIN NHÓM (GROUP) ĐỂ LẤY CẤU TRÚC FORM
        $group = $db->get_row("SELECT * FROM `groups_service` WHERE `id` = '".$row['group_id']."'");
        if (!$group) {
            die(JsonMsg('error', 'Nhóm dịch vụ không tồn tại!'));
        }
    
        // 5. XỬ LÝ DỮ LIỆU ĐỘNG (DYNAMIC FIELDS)
        // Giải mã JSON từ cột detail trong database
        $details = json_decode($group['detail'], true);
        $form_fields = isset($details['data']) ? $details['data'] : [];
        
        $user_inputs_data = []; // Mảng chứa dữ liệu khách nhập để lưu vào 'data'
    
        foreach ($form_fields as $item) {
            // Nếu field này cấu hình ẩn (show != on) thì bỏ qua
            if (isset($item['show']) && $item['show'] !== 'on') continue;
    
            $fieldName  = $item['name'];  // Ví dụ: taikhoan, matkhau, server...
            $fieldLabel = $item['label']; // Ví dụ: Tài khoản, Mật khẩu...
            
            // Lấy dữ liệu từ $_POST dựa trên name
            $val = anti_xss($_POST[$fieldName] ?? '');
    
            // Kiểm tra rỗng (Validation)
            if (empty($val)) {
                die(JsonMsg('error', 'Vui lòng nhập ' . $fieldLabel . ' !'));
            }
    
            // Đưa vào mảng data
            // Lưu cả Label, Name và Value (đã mã hóa) để sau này hiển thị lịch sử cho dễ
            $user_inputs_data[] = [
                'label' => $fieldLabel,
                'name'  => $fieldName,
                'value' => encrypt_data($val) // Mã hoá dữ liệu nhạy cảm
            ];
        }
    
        // 6. TÍNH TOÁN GIÁ TIỀN
        $price = $row['price'];
        
        // Check mã giảm giá
        if (!empty($_POST['coupon'])) {
            $discount = checkCoupon($row["group_id"], anti_xss($_POST['coupon']), $price);
        }
        // Áp dụng giảm giá
        if (isset($discount)) {
            $price = $price - (($price * $discount) / 100);
        }
        
        // Tính phí giao dịch (nếu có)
        $feePercent = (float) $db->site('fees');
        $fees       = $price * ($feePercent / 100);
        $net_amount = $price + $fees;
    
    
        // 7. CHUẨN BỊ THÔNG TIN ĐƠN HÀNG
        $order_code     = generate_code_with_prefix('B', 14);
        $order_type     = "buy";
        $module         = "service";
        $description    = "Mua gói dịch vụ ID #{$id}";
        $description_refund = "Hoàn tiền mua gói dịch vụ ID #{$id}";
        $status         = "paid";
        $status_service = 99; // Trạng thái chờ xử lý
        $payment_method = "balance";
    
        // Kiểm tra số dư
        if ($net_amount > $data_user['money']) {
            die(JsonMsg('error', 'Số dư không đủ để thanh toán.'));
        }
    
        // 8. THỰC HIỆN TRỪ TIỀN
        $TRUTIEN = TruTien($data_user['id'], $net_amount, $description, $order_code);
        
        if ($TRUTIEN) {
            // Kiểm tra hack âm tiền
            if (getRowRealtime("users", $data_user['id'], "money") < 0) {
                Banned($data_user['id'], "Tài khoản đã bị khoá vì số dư không hợp lệ.");
                die(JsonMsg('error', 'Tài khoản đã bị khoá vì số dư không hợp lệ.'));
            }
    
            // 9. TẠO JSON DỮ LIỆU ĐỂ LƯU TRỮ
            // Cấu trúc: service_additional chứa mảng 'data' (các trường khách nhập) và thông tin gói
            $service_json = json_encode([
                'service_id'            => $id,
                'service_additional'    => [
                    'data'        => $user_inputs_data, // <--- Dữ liệu động nằm ở đây
                    'category_id' => $row['category_id'],
                    'group_id'    => $row['group_id'],
                    'name'        => $row['name'],
                    'image'       => $row['image'],
                    'price'       => $row['price']
                ],
            ], JSON_UNESCAPED_UNICODE);
    
            // Data chung cho Invoice
            $invoiceData = [
                'order_code'         => $order_code,
                'order_code_service' => $order_code,
                'order_type'         => $order_type,
                'module'             => $module,
                'service_json'       => $service_json,
                'user_id'            => $data_user['id'],
                'net_amount'         => $net_amount,
                'fees'               => $feePercent,
                'pay_amount'         => $net_amount,
                'paygate'            => $payment_method,
                'description'        => $description,
                'status'             => $status,
                'status_service'     => $status_service,
                'created_at'         => gettime(),
                'updated_at'         => gettime(),
            ];
            
            // 10. LƯU DATABASE (INVOICE & ORDER)
            $INSERT_INVOICE = $db->insert("invoices", $invoiceData);
            
            if ($INSERT_INVOICE) {
                insert_log($data_user['id'], "Thực hiện tạo hóa đơn mua gói dịch vụ ID #{$id}");
                
                // Insert Order
                $orderData = [
                    'user_id'      => $data_user['id'],
                    'order_code'   => $order_code,
                    'category_id'  => $row['category_id'],
                    'group_id'     => $row['group_id'],
                    'service_type' => $module,
                    'service_json' => $service_json,
                    'status'       => $status_service,
                    'amount'       => $net_amount,
                    'note'         => $note,
                    'created_at'   => gettime(),
                    'updated_at'   => gettime()
                ];
                
                $INSERT_ORDER = $db->insert("orders", $orderData);
                
                if($INSERT_ORDER){
                    die(JsonMsg('success', 'Thanh toán thành công, chuyển hướng đến lịch sử mua !'));
                } else {
                    // Nếu Insert Order lỗi -> Hoàn tiền
                    CongTien($data_user['id'], $net_amount, $description_refund, $order_code);
                    die(JsonMsg('error', 'Có lỗi xảy ra trong quá trình tạo đơn hàng, vui lòng liên hệ Admin !'));
                }
            } else {
                // Nếu Insert Invoice lỗi -> Hoàn tiền
                CongTien($data_user['id'], $net_amount, $description_refund, $order_code);
                die(JsonMsg('error', 'Có lỗi xảy ra trong quá trình tạo hóa đơn, vui lòng liên hệ Admin !'));
            }
        } else {
            die(JsonMsg('error', 'Thanh toán thất bại, vui lòng thử lại.'));
        }
    }
    if (isset($_POST['type']) && $_POST['type'] === 'check_invoice') {

    // 1. Kiểm tra đăng nhập
    if (!check_login()) {
        die(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện chức năng này!'));
    }

    // 2. Lấy mã đơn hàng từ request
    $order_code = anti_xss($_POST['order_code'] ?? '');
    if (empty($order_code)) {
        die(JsonMsg('error', 'Mã đơn hàng không hợp lệ.'));
    }

    // 3. Tìm đơn hàng trong database (Phải khớp cả User ID để bảo mật)
    $invoice = $db->get_row("SELECT * FROM `invoices` WHERE `order_code` = '{$order_code}' AND `user_id` = '{$data_user['id']}' ");

    if (!$invoice) {
        die(JsonMsg('error', 'Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập.'));
    }

    if ($invoice['status'] === 'paid') {
        die(json_encode([
            'status' => 'success', 
            'return' => 1, 
            'msg'    => 'Thanh toán thành công!',
            'url'    => '/wallet/history'
        ]));
    } else if ($invoice['status'] === 'cancel') {
        die(json_encode([
            'status' => 'error', 
            'return' => 0, 
            'msg'    => 'Đơn nạp đã bị huỷ.'
        ]));
    } else {
        die(json_encode([
            'status' => 'pending', 
            'return' => 0, 
            'msg'    => 'Đang chờ thanh toán...'
        ]));
    }
}
?>