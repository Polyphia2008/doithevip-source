<?php
$where = " `id` > 0 AND `admin` != 1 ";

    if (isset($_GET['user_id']) && $_GET['user_id'] !== '' && is_numeric($_GET['user_id'])) {
        $filterUserId = (int)$_GET['user_id'];
        $where .= " AND `id` = '{$filterUserId}'";
    }

    if (isset($_GET['banned']) && $_GET['banned'] !== '' && is_numeric($_GET['banned'])) {
        $banned = (int)$_GET['banned'];
        $where .= " AND `banned` = '{$banned}'";
    }

    if (!empty($_GET['username'])) {
        $username = anti_xss($_GET['username']);
        $where .= " AND `username` = '{$username}'";
    }

    if (!empty($_GET['name'])) {
        $name = anti_xss($_GET['name']);
        $where .= " AND `name` LIKE '%{$name}%'";
    }

    if (!empty($_GET['email'])) {
        $email = anti_xss($_GET['email']);
        $where .= " AND `email` = '{$email}'";
    }

    if (!empty($_GET['phone'])) {
        $phone = anti_xss($_GET['phone']);
        $where .= " AND `phone` = '{$phone}'";
    }

    /** lọc ngày tạo */
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

    $total = $db->num_rows("SELECT * FROM `users` WHERE $where");
    $users = $db->get_list("SELECT * FROM `users` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");
