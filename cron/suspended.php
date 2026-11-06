<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

require_cron_pin();

$lockFile = __DIR__ . '/lock/suspended.lock';
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
    touch_cron_time('check_time_cron_suspended');
    $id = 0;
    $timesspend = time() + (86400 * 90);

    if ($id < 1) {
        die(json_encode([
            'status' => 'error',
            'msg'    => 'Không có dịch vụ nào cần kích hoạt!'
        ]));
    }

    echo json_encode([
        'status' => 'success',
        'msg'    => 'Xử lý thành công!'
    ]);

} finally {
    flock($fp, LOCK_UN);
    fclose($fp);
}
