<?php
$order_code = anti_xss($_GET['order_code'] ?? '');
if (empty($order_code)) {
    msg_redirect('admin/service/orders', 'error', 'Thiếu mã đơn hàng.');
}

$row = $db->get_row("
    SELECT * FROM `orders`
    WHERE `order_code` = '{$order_code}'
");

if (!$row) {
    msg_redirect('admin/service/orders', 'error', 'Đơn hàng không tồn tại.');
} else {
    // Giải mã JSON
    $serviceData = json_decode($row['service_json'] ?? '[]', true) ?: [];
    $serviceId   = $serviceData['service_id'] ?? '';
    
    // Lấy mảng dữ liệu động khách nhập (đã lưu ở bước mua)
    // Cấu trúc: [['label' => '...', 'name' => '...', 'value' => '...'], ...]
    $user_inputs = $serviceData['service_additional']['data'] ?? [];
}

$display_inputs = [];
foreach ($user_inputs as $input) {
    $display_inputs[] = [
        'label' => $input['label'] ?? 'Thông tin',
        'name' => $input['name'] ?? ('field_' . count($display_inputs)),
        'value' => anti_xss(decrypt_data($input['value'] ?? '')),
    ];
}

$category_name = anti_xss(getRowRealtime('category_service', $row['category_id'], 'name') ?? 'Không rõ');
$group_name = anti_xss(getRowRealtime('groups_service', $row['group_id'], 'name') ?? 'Không rõ');
$pack_name = anti_xss(getRowRealtime('pack_service', $serviceId, 'name') ?? 'Không rõ');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $status = (int)($_POST['status'] ?? 0);
    $note   = anti_xss($_POST['note'] ?? '');
    
    // Lấy tên gói và giá từ đơn hàng hiện tại (không lấy realtime vì giá có thể đổi)
    $pack  = $serviceData['service_additional']['name'] ?? 'Dịch vụ';
    $price = $row['amount']; // Lấy số tiền từ đơn hàng

    // Cập nhật bảng orders
    $orderUpdate = $db->update("orders", [
        'status'     => $status,
        'note'       => $note,
        'updated_at' => gettime()
    ], "`id` = '{$row['id']}'");

    // Xử lý hoàn tiền / cộng tiền và cập nhật invoice (Logic giữ nguyên theo ý bạn, nhưng cần check kỹ biến $id của invoice)
    // Lưu ý: Code gốc của bạn đang dùng $id và $invoiceData chưa được định nghĩa. 
    // Mình giả định bạn muốn update trạng thái invoice tương ứng với order code.
    
    $invoiceStatus = 'paid'; // Mặc định
    if ($status == 4) { // Hủy hoàn tiền
        CongTien($row['user_id'], $price, "Hoàn tiền đơn hàng mua $pack #{$order_code}");
        $invoiceStatus = 'cancelled';
    } elseif ($status == 3) { // Hủy không hoàn tiền
        $invoiceStatus = 'cancelled';
    }

    // Cập nhật Invoice theo order_code
    $db->update("invoices", ['status' => $invoiceStatus], "`order_code` = '{$order_code}'");


    if ($orderUpdate) {
        msg_redirect('admin/service/orders', 'success', 'Cập nhật thành công.');
    } else {
        msg_redirect('reload', 'error', 'Cập nhật thất bại.');
    }
}
