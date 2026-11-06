<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

require_cron_pin();

$lockFile = __DIR__ . '/lock/delivery.lock';
if (!is_dir(dirname($lockFile))) {
    mkdir(dirname($lockFile), 0777, true);
}

$fp = fopen($lockFile, 'w+');
if (!flock($fp, LOCK_EX | LOCK_NB, $wouldBlock)) {
    fclose($fp);
    if ($wouldBlock) {
        die('Có phiên khác đang chạy');
    }
}

try {
    touch_cron_time('check_time_cron_delivery');
    $id = 0;
    $list = $db->get_list("SELECT * FROM `invoices` WHERE `status_service` = '99' AND `status` = 'paid'");

    foreach ($list as $row) {
        $id++;
    }

    if ($id < 1) {
        exit(json_encode([
            'status' => 'error',
            'msg'    => 'Không có dịch vụ nào cần kích hoạt!'
        ]));
    }

    echo json_encode([
        'status' => 'success',
        'msg'    => "Đã xử lý $id dịch vụ cần kích hoạt"
    ]);

} finally {
    flock($fp, LOCK_UN);
    fclose($fp);
}
