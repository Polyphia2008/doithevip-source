<?php
function getRowUser($id, $row)
{
    global $db;
    return $db->get_row("SELECT * FROM `users` WHERE `id` = '$id'")[$row] ?? 'NULL';
}
function usersTotal()
{
    global $db;
    return format_number($db->get_row("SELECT COUNT(id) as total FROM users")['total'] ?? 0);
}
function usersMonthTotal()
{
    global $db;
    return format_number($db->get_row("SELECT COUNT(id) as total FROM users WHERE YEAR(create_date) = " . date('Y') . " AND MONTH(create_date) = " . date('m') . " ")['total'] ?? 0);
}
function usersDayTotal()
{
    global $db;
    return format_number($db->get_row("SELECT COUNT(id) as total FROM users WHERE DATE(create_date) = CURDATE()")['total'] ?? 0);
}
function TruTien($user_id, $amount, $reason, $transid = NULL)
{
    global $db;
    if($transid == NULL){
        $transid = uniqid().'_'.mt_rand(0, 9999999);
    }
    $db->insert("logs_balance", array(
        'trans_id' => $transid,
        'money_before' => getRowUser($user_id, 'money'),
        'money_change' => $amount,
        'money_after' => getRowUser($user_id, 'money') - $amount,
        'time' => gettime(),
        'type' => '-',
        'content' => $reason,
        'user_id' => $user_id
    ));
    $isRemove = $db->tru("users", "money", $amount, " `id` = '$user_id' ");
    if ($isRemove) {
        return true;
    } else {
        return false;
    }
}
function CongTien($user_id, $amount, $reason, $transid = NULL)
{
    global $db;
    if($transid == NULL){
        $transid = uniqid().'_'.mt_rand(0, 9999999);
    }
    $db->insert("logs_balance", array(
        'trans_id' => $transid,
        'money_before' => getRowUser($user_id, 'money'),
        'money_change' => $amount,
        'money_after' => getRowUser($user_id, 'money') + $amount,
        'time' => gettime(),
        'type' => '+',
        'content' => $reason,
        'user_id' => $user_id
    ));
    $isPlus = $db->cong("users", "money", $amount, " `id` = '$user_id' ");
    if ($isPlus) {
        return true;
    } else {
        return false;
    }
}
function HoanTien($user_id, $amount, $reason, $transid = NULL)
{
    global $db;
    if($transid == NULL){
        $transid = uniqid().'_'.mt_rand(0, 9999999);
    }
    $db->insert("logs_balance", array(
        'trans_id' => $transid,
        'money_before' => getRowUser($user_id, 'money'),
        'money_change' => $amount,
        'money_after' => getRowUser($user_id, 'money') + $amount,
        'time' => gettime(),
        'type' => '+',
        'content' => $reason,
        'user_id' => $user_id
    ));
    $isPlus = $db->cong("users", "money", $amount, " `id` = '$user_id' ");
    if ($isPlus) {
        return true;
    } else {
        return false;
    }
}
function Banned($user_id, $reason)
{
    global $db;
    insert_log($user_id, $reason);
    $db->update("users", array(
        'banned' => 1,
        'failed_reason' => $reason
    ), "id = '" . $user_id . "' ");
}
function check_capbac($user_id)
{
    global $db;
    $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '$user_id' ");
    if (!$user) {
        return [
            'status' => false,
            'label' => 'Người dùng không tồn tại'
        ];
    }

    if ($user['admin'] == 1) {
        return [
            'status' => true,
            'label' => 'Quản Trị Viên'
        ];
    } else {
        return [
            'status' => false,
            'label' => 'Thành Viên'
        ];
    }
}
function check_banned($user_id) {
    global $db;
    $user = $db->get_row("SELECT * FROM `users` WHERE `id` = '$user_id' ");

    if (!$user) {
        return [
            'status' => null,
            'class' => 'badge badge-outline-secondary text-decoration-none',
            'text' => 'Không tồn tại'
        ];
    }

    switch ($user['banned']) {
        case 0:
            return [
                'status' => 1,
                'class' => 'badge badge-outline-success text-decoration-none',
                'text' => 'Hoạt động'
            ];
        case 1:
            return [
                'status' => 'cancel',
                'class' => 'badge badge-outline-danger text-decoration-none',
                'text' => 'Bị khóa'
            ];
        default:
            return [
                'status' => $user['banned'],
                'class' => 'badge badge-outline-secondary text-decoration-none',
                'text' => $user['banned']
            ];
    }
}