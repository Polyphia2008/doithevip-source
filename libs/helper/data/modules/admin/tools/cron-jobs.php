<?php
if (isset($_POST['save_cron_pin'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ!');
    }
    check_demo_mode();

    $pin_cron = anti_xss($_POST['pin_cron'] ?? '');
    $db->set_site('pin_cron', $pin_cron);

    msg_redirect('reload', 'success', 'Lưu mã PIN cron thành công!');
}

$pin_cron = strval($db->site('pin_cron') ?? '');

$cronJobs = [
    [
        'name' => 'Cron xử lý nạp tiền / hóa đơn ngân hàng tự động',
        'path' => 'cron/invoice',
        'time_key' => 'check_time_cron_invoice',
        'recommend' => 'Mỗi 1 phút',
    ],
    [
        'name' => 'Cron xử lý giao hàng / kích hoạt dịch vụ',
        'path' => 'cron/delivery',
        'time_key' => 'check_time_cron_delivery',
        'recommend' => 'Mỗi 1–5 phút',
    ],
    [
        'name' => 'Cron xử lý dịch vụ hết hạn',
        'path' => 'cron/expires',
        'time_key' => 'check_time_cron_expires',
        'recommend' => 'Mỗi 5–15 phút',
    ],
    [
        'name' => 'Cron xử lý dịch vụ tạm ngưng',
        'path' => 'cron/suspended',
        'time_key' => 'check_time_cron_suspended',
        'recommend' => 'Mỗi ngày / mỗi giờ',
    ],
];

foreach ($cronJobs as $key => $job) {
    $cronJobs[$key]['url'] = cron_job_url($job['path']);
    $cronJobs[$key]['url_display'] = BASE_URL(rtrim($job['path'], '/') . '/');
    $cronJobs[$key]['last_run'] = format_cron_last_run($db->site($job['time_key']));
}

$cronAllUrl = cron_job_url('cron/jobs');
$cronAllUrlDisplay = BASE_URL('cron/jobs/');
