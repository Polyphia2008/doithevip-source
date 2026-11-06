<?php
$where = " `user_id` = '{$data_user['id']}'";

if (!empty($_GET['order_code'])) {
    $order_code = anti_xss($_GET['order_code']);
    $where .= " AND `trans_id` = '$order_code' ";
}

if (!empty($_GET['message'])) {
    $message = anti_xss($_GET['message']);
    $where .= " AND `content` LIKE '%$message%' ";
}

if (!empty($_GET['order_type'])) {
    $orderType = anti_xss($_GET['order_type']);
    if ($orderType === 'Withdraw') {
        $where .= " AND `type` = '-' ";
    } elseif ($orderType === 'Deposit') {
        $where .= " AND `type` = '+' ";
    }
}

if (!empty($_GET['from_date'])) {
    $from = anti_xss($_GET['from_date']) . ' 00:00:00';
    $where .= " AND `time` >= '$from' ";
}

if (!empty($_GET['to_date'])) {
    $to = anti_xss($_GET['to_date']) . ' 23:59:59';
    $where .= " AND `time` <= '$to' ";
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 20;
$start = ($page - 1) * $limit;

$total = $db->num_rows("SELECT * FROM `logs_balance` WHERE $where");
$result = $db->get_list("SELECT * FROM `logs_balance` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");
