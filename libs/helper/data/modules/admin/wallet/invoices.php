<?php
$where = " `id` > 0";

    $searchOrderCode = trim((string)($_GET['order_code'] ?? $_GET['trans_id'] ?? ''));
    if ($searchOrderCode !== '') {
        $order_code = anti_xss($searchOrderCode);
        $where .= " AND (`order_code` = '{$order_code}' OR `order_code_service` = '{$order_code}')";
    }
    if (isset($_GET['user_id']) && $_GET['user_id'] !== '' && is_numeric($_GET['user_id'])) {
        $filterUserId = (int)$_GET['user_id'];
        $where .= " AND `user_id` = '{$filterUserId}'";
    }
    if (!empty($_GET['username'])) {
        $filterUsername = anti_xss($_GET['username']);
        $userRow  = $db->get_row("SELECT `id` FROM `users` WHERE `username` = '{$filterUsername}'");
        if ($userRow) {
            $where .= " AND `user_id` = '{$userRow['id']}'";
        } else {
            $where .= " AND `user_id` = 0";
        }
    }
    $searchContent = trim((string)($_GET['content'] ?? $_GET['description'] ?? ''));
    if ($searchContent !== '') {
        $description = anti_xss($searchContent);
        $where .= " AND `description` LIKE '%{$description}%'";
    }
    if (!empty($_GET['order_type'])) {
        $order_type = anti_xss($_GET['order_type']);
        $where .= " AND `order_type` = '{$order_type}'";
    }
    if (!empty($_GET['paygate'])) {
        $paygate = anti_xss($_GET['paygate']);
        $where .= " AND `paygate` = '$paygate' ";
    }
    if (!empty($_GET['from_date'])) {
        $from = anti_xss($_GET['from_date']) . " 00:00:00";
        $where .= " AND `created_at` >= '{$from}'";
    }

    if (!empty($_GET['to_date'])) {
        $to = anti_xss($_GET['to_date']) . " 23:59:59";
        $where .= " AND `created_at` <= '{$to}'";
    }
    
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `invoices` WHERE $where");
    $invoices = $db->get_list("SELECT * FROM `invoices` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

foreach ($invoices as $key => $invoice) {
    $invoices[$key]['username'] = anti_xss(getRowRealtime('users', $invoice['user_id'], 'username') ?? 'Không rõ');
    $invoices[$key]['status_info'] = status_invoice_admin($invoice['id']);
}

$deposit_banks = $db->get_list("SELECT * FROM `bankings` WHERE `invoice_pay` = 1");
