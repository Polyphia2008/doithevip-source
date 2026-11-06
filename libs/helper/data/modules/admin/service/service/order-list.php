<?php
$where = " `id` > 0 AND `service_type` = 'service'";

    // lọc theo order_code
    if (!empty($_GET['order_code'])) {
        $order_code = anti_xss($_GET['order_code']);
        $where .= " AND `order_code` = '{$order_code}'";
    }

    // lọc theo user_id
    if (!empty($_GET['user_id']) && is_numeric($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
        $where .= " AND `user_id` = '{$user_id}'";
    }
    // lọc theo username
    if (!empty($_GET['username'])) {
        $username = anti_xss($_GET['username']);
        $userRow  = $db->get_row("SELECT `id` FROM `users` WHERE `username` = '{$username}'");
        if ($userRow) {
            $where .= " AND `user_id` = '{$userRow['id']}'";
        } else {
            $where .= " AND `user_id` = 0";
        }
    }
    // lọc theo user_id
    if (!empty($_GET['group_id']) && is_numeric($_GET['group_id'])) {
        $group_id = intval($_GET['group_id']);
        $where .= " AND `group_id` = '{$group_id}'";
    }
    // lọc theo user_id
    if (!empty($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        $category_id = intval($_GET['category_id']);
        $where .= " AND `category_id` = '{$category_id}'";
    }
    // lọc status
    if (isset($_GET['status']) && $_GET['status'] !== '' && is_numeric($_GET['status'])) {
        $status = intval($_GET['status']);
        $where .= " AND `status` = '{$status}'";
    }
    // lọc ngày
    if (!empty($_GET['from_date'])) {
        $from = anti_xss($_GET['from_date']) . " 00:00:00";
        $where .= " AND `created_at` >= '{$from}'";
    }
    if (!empty($_GET['to_date'])) {
        $to = anti_xss($_GET['to_date']) . " 23:59:59";
        $where .= " AND `created_at` <= '{$to}'";
    }

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 20;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `orders` WHERE $where");
    $orders = $db->get_list("SELECT * FROM `orders` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

$filter_categories = $db->get_list("SELECT id, name FROM `category_service` WHERE `status` = 1");
$filter_groups = $db->get_list("SELECT id, name FROM `groups_service` WHERE `status` = 1");

foreach ($orders as &$order) {
    $serviceData = json_decode($order['service_json'] ?? '[]', true) ?: [];
    $serviceId = $serviceData['service_id'] ?? '';
    $order['service_id'] = $serviceId;
    $order['status_info'] = status_service_admin($order['id']);
    $order['type_info'] = service_type_admin($order['type'] ?? '');
    $order['buyer_username'] = anti_xss(getRowRealtime('users', $order['user_id'], 'username') ?? 'Không rõ');
    $order['receiver_username'] = anti_xss(getRowRealtime('users', $order['receive_id'], 'username') ?? 'Không rõ');
    $order['category_name'] = anti_xss(getRowRealtime('category_service', $order['category_id'], 'name') ?? 'Không rõ');
    $order['group_name'] = anti_xss(getRowRealtime('groups_service', $order['group_id'], 'name') ?? 'Không rõ');
    $order['pack_name'] = anti_xss(getRowRealtime('pack_service', $serviceId, 'name') ?? 'Không rõ');
    $invoiceRow = $db->get_row("SELECT SUM(`net_amount`) AS total FROM `invoices` WHERE `order_code_service` = '" . anti_xss($order['order_code']) . "'");
    $order['invoice_total'] = $invoiceRow['total'] ?? 0;
}
unset($order);
