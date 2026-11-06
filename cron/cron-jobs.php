<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

require_cron_pin();

function call_cron_job($url)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
    curl_close($ch);

    if ($error) {
        return [
            'url' => $url,
            'success' => false,
            'http_code' => $httpCode,
            'error' => $error,
        ];
    }

    return [
        'url' => $url,
        'success' => true,
        'http_code' => $httpCode,
        'response' => $result,
    ];
}

$pin = strval($db->site('pin_cron') ?? '');
$results = [];
$jobs = [
    'cron/invoice',
    'cron/delivery',
    'cron/expires',
    'cron/suspended',
];

foreach ($jobs as $job) {
    $url = BASE_URL(rtrim($job, '/') . '/');
    if ($pin !== '') {
        $url .= '?pin=' . rawurlencode($pin);
    }
    $results[] = call_cron_job($url);
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
