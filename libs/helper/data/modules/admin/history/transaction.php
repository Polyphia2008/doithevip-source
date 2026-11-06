<?php
$where = " `id` > 0 ";

    if (!empty($_GET['trans_id'])) {
        $trans_id = anti_xss($_GET['trans_id']);
        $where .= " AND `trans_id` = '{$trans_id}'";
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
    if (!empty($_GET['content'])) {
        $content = anti_xss($_GET['content']);
        $where .= " AND `content` LIKE '%{$content}%'";
    }
    if (!empty($_GET['order_type'])) {
        $order_type = anti_xss($_GET['order_type']);
        if ($order_type === 'Deposit') {
            $where .= " AND `type` = '+' ";
        } elseif ($order_type === 'Withdraw') {
            $where .= " AND `type` = '-' ";
        }
    }

    if (!empty($_GET['from_date'])) {
        $from = anti_xss($_GET['from_date']) . " 00:00:00";
        $where .= " AND `create_date` >= '{$from}'";
    }

    if (!empty($_GET['to_date'])) {
        $to = anti_xss($_GET['to_date']) . " 23:59:59";
        $where .= " AND `create_date` <= '{$to}'";
    }
    
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;
    $start = ($page - 1) * $limit;

    $total = $db->num_rows("SELECT * FROM `logs_balance` WHERE $where");
    $logs = $db->get_list("SELECT * FROM `logs_balance` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

foreach ($logs as $key => $log) {
    $logs[$key]['username'] = anti_xss(getRowRealtime('users', $log['user_id'], 'username') ?? 'Không rõ');
}
