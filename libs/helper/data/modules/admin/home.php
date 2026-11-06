<?php
// Hôm nay
$todayStart = date('Y-m-d 00:00:00');
$todayEnd   = date('Y-m-d 23:59:59');

// Tuần hiện tại
$monday     = date('Y-m-d 00:00:00', strtotime('monday this week'));
$sunday     = date('Y-m-d 23:59:59', strtotime('sunday this week'));

// Tháng hiện tại
$monthStart = date('Y-m-01 00:00:00');
$monthEnd   = date('Y-m-t 23:59:59');

// Năm hiện tại
$yearStart  = date('Y-01-01 00:00:00');
$yearEnd    = date('Y-12-31 23:59:59');

$allTime    = ['start' => '2000-01-01 00:00:00', 'end' => date('Y-m-d H:i:s')];

function sumPayAmount($where) {
    global $db;
    $row = $db->get_row("SELECT SUM(`pay_amount`) AS total FROM `invoices` WHERE $where");
    return !empty($row['total']) ? $row['total'] : 0;
}

function sumNetProfit($where) {
    global $db;
    $rows = $db->get_list("SELECT `net_amount`, `fees` FROM `invoices` WHERE $where");
    $totalProfit = 0;
    foreach ($rows as $row) {
        $feeAmount = $row['net_amount'] * $row['fees'] / 100;
        $profit = $row['net_amount'] - $feeAmount;
        $totalProfit += $profit;
    }
    return $totalProfit;
}

function sumFees($where) {
    global $db;
    $rows = $db->get_list("SELECT `net_amount`, `fees` FROM `invoices` WHERE $where");
    $totalTax = 0;
    foreach ($rows as $row) {
        $tax = $row['net_amount'] * $row['fees'] / 100;
        $totalTax += $tax;
    }
    return $totalTax;
}

function countPendingOrders() {
    global $db;
    $row = $db->get_row("SELECT COUNT(*) AS total FROM `orders` WHERE `status` = '99'");
    return (int)$row['total'];
}
function countRows($table, $where = '1=1') {
    global $db;
    $row = $db->get_row("SELECT COUNT(*) AS total FROM `$table` WHERE $where");
    return (int)($row['total'] ?? 0);
}

// Lấy số liệu
$totals = [
    'account'     => sumPayAmount("`module` = 'account' AND `status` = 'paid' AND `created_at` BETWEEN '$todayStart' AND '$todayEnd'"),
    'account_sell'      => countRows('orders', "`service_type` = 'account' AND `status` = '1' AND `created_at` BETWEEN '$todayStart' AND '$todayEnd'"),
    'service'     => sumPayAmount("`module` = 'service' AND `status` = 'paid' AND `created_at` BETWEEN '$todayStart' AND '$todayEnd'"),
    'service_pending_orders'      => countRows('orders', "`service_type` = 'service' AND `status` = '99' AND `created_at` BETWEEN '$todayStart' AND '$todayEnd'"),
    'deposit'     => sumPayAmount("`order_type` = 'deposit' AND `status` = 'paid' AND `created_at` BETWEEN '$todayStart' AND '$todayEnd'"),
    'profit'      => sumNetProfit("`status` = 'paid' AND `created_at` BETWEEN '$monthStart' AND '$monthEnd'"),
    'member'      => countRows('users', "`admin` != 1 AND `banned` = 0 AND `create_date` BETWEEN '$todayStart' AND '$todayEnd'"),
    'total_pending_orders' => countPendingOrders(),
];

$todayProfit = sumNetProfit("`status` = 'paid' AND `created_at` BETWEEN '$todayStart' AND '$todayEnd'");
$newUserCount = $db->get_row("SELECT COUNT(*) AS total FROM `users` WHERE `create_date` BETWEEN '$todayStart' AND '$todayEnd'")['total'] ?? 0;

$stats = [
    ['label' => 'Mua tài khoản', 'value' => $totals['account'], 'icon' => 'solar:cloud-bold-duotone', 'bg' => 'text-bg-primary'],
    ['label' => 'Mua dịch vụ', 'value' => $totals['service'], 'icon' => 'solar:server-square-cloud-bold', 'bg' => 'text-bg-dark'],
    ['label' => 'Nạp quỹ', 'value' => $totals['deposit'], 'icon' => 'solar:download-minimalistic-linear', 'bg' => 'text-bg-amber'],
    ['label' => 'Lãi tháng', 'value' => $totals['profit'], 'icon' => 'solar:dollar-bold-duotone', 'bg' => 'text-bg-success'],
    ['label' => 'Thành viên', 'value' => $totals['member'], 'icon' => 'solar:user-circle-bold', 'bg' => 'text-bg-danger'],
    ['label' => 'Đơn mua tài khoản', 'value' => $totals['account_sell'], 'icon' => 'solar:cart-check-broken', 'bg' => 'text-bg-primary'],
    ['label' => 'Đơn mua dịch vụ', 'value' => $totals['service_pending_orders'], 'icon' => 'solar:cart-check-bold-duotone', 'bg' => 'text-bg-dark'],
];
