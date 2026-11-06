<?php
$where = " `id` > 0 ";

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
    if (!empty($_GET['action'])) {
        $action = anti_xss($_GET['action']);
        $where .= " AND `action` LIKE '%{$action}%'";
    }
    if (!empty($_GET['ip'])) {
        $ip = anti_xss($_GET['ip']);
        $where .= " AND `ip` LIKE '%{$ip}%'";
    }
    if (!empty($_GET['device'])) {
        $device = anti_xss($_GET['device']);
        $where .= " AND `device` LIKE '%{$device}%'";
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

    $total = $db->num_rows("SELECT * FROM `logs` WHERE $where");
    $logs = $db->get_list("SELECT * FROM `logs` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

foreach ($logs as $key => $log) {
    $logs[$key]['username'] = anti_xss(getRowRealtime('users', $log['user_id'], 'username') ?? 'Không rõ');
}
