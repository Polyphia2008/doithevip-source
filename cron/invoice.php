<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';

require_cron_pin();

/** ================= LOCK FILE (GLOBAL) ================= */
$lockFile = __DIR__ . '/lock/invoice.lock';
if (!is_dir(dirname($lockFile))) {
    mkdir(dirname($lockFile), 0777, true);
}
$fp = fopen($lockFile, 'w+');
if (!flock($fp, LOCK_EX | LOCK_NB, $wouldBlock)) {
    fclose($fp);
    if ($wouldBlock)
        die('Có phiên khác đang chạy');
}

try {
    touch_cron_time('check_time_cron_invoice');

    define('PINO_API_URL', 'https://pinocloud.vn/api/app');

    /** ================= STC ENDPOINT ================= */
    $STC_BANKS = [
        'TPBANK' => ['url' => 'https://api.sieuthicode.net/historyapitpbv3/%s/%s/%s'],
        'VIETCOMBANK' => ['url' => 'https://api.sieuthicode.net/historyapivcbv3/%s/%s/%s'],
        'ACB' => ['url' => 'https://api.sieuthicode.net/historyapiacbv3/%s/%s/%s'],
        'MBBANK' => ['url' => 'https://api.sieuthicode.net/historyapimbv3/%s/%s/%s'],
        'BIDV' => ['url' => 'https://api.sieuthicode.net/historyapibidvv3/%s/%s/%s'],
        'VIETINBANK' => ['url' => 'https://api.sieuthicode.net/historyapiviettinv3/%s/%s/%s'],
        'SEABANK' => ['url' => 'https://api.sieuthicode.net/historyapiseabankv3/%s/%s/%s'],
        'MSB' => ['url' => 'https://api.sieuthicode.net/historyapimsbv3/%s/%s/%s'],
        'TIMO' => ['url' => 'https://api.sieuthicode.net/historyapitimov3/%s/%s/%s'],
    ];

    /** ================= RPAY ENDPOINT (V3) ================= */
    $RPAY_BANKS = [
        'TPBANK'       => 'https://api.rpay.vn/historyapitpbv3',
        'VIETCOMBANK'  => 'https://api.rpay.vn/historyapivcbv3',
        'ACB'          => 'https://api.rpay.vn/historyapiacbv3',
        'MBBANK'       => 'https://api.rpay.vn/historyapimbbankv3',
        'BIDV'         => 'https://api.rpay.vn/historyapibidvv3',
        'VIETINBANK'   => 'https://api.rpay.vn/historyapivietinbankv3',
        'SEABANK'      => 'https://api.rpay.vn/historyapiseabankv3',
        'MSB'          => 'https://api.rpay.vn/historyapimsbv3',
        'AGRIBANK'     => 'https://api.rpay.vn/historyapiagribankv3',
        'BVBANK'       => 'https://api.rpay.vn/historyapibvbankv3',
        'TECHCOMBANK'  => 'https://api.rpay.vn/historyapitechcombankv3',
        'VPBANK'       => 'https://api.rpay.vn/historyapivpbankv3',
        'LPBANK'       => 'https://api.rpay.vn/historyapilpbankv3',
        'OCB'          => 'https://api.rpay.vn/historyapiocbv3',
        'TIMO'         => 'https://api.rpay.vn/historyapitimov3',
    ];
    $RPAY_VIETTELPAY_API = 'https://api.rpay.vn/historyapiviettelpayv3';
    /** ================= FIND INVOICE ================= */
    function find_unpaid_invoice($description, $amount, $bankName, $limit = 10)
    {
        global $db;

        $where = "
            status = 'unpaid'
            AND pay_amount = '" . check_string($amount) . "'
            AND paygate = '" . check_string($bankName) . "'
        ";

        $sql = "
            SELECT id,user_id,order_code,order_type,net_amount
            FROM invoices
            WHERE {$where}
            ORDER BY id DESC
            LIMIT {$limit}
        ";

        $list = $db->get_list($sql);
        if (!$list)
            return null;

        foreach ($list as $row) {
            if ($row['order_code'] && str_contains($description, $row['order_code'])) {
                return $row;
            }
        }
        return null;
    }

    /** ================= PROCESS TRANSACTION ================= */
    function process_transaction_item($item, $idField, $amountField, $descField, $bankLabel, $bankName)
    {
        global $db;

        $tid = trim($item[$idField] ?? '');
        $amount = (float) ($item[$amountField] ?? 0);
        $desc = trim($item[$descField] ?? '');

        if (!$tid || $amount <= 0 || !$desc)
            return;

        $invoice = find_unpaid_invoice($desc, $amount, $bankName);
        if (!$invoice)
            return;

        $user = $db->get_row("
            SELECT * FROM users
            WHERE id = '{$invoice['user_id']}'
        ");
        if (!$user)
            return;

        $memo = "Thanh toán {$invoice['order_code']} qua {$bankLabel} (#{$tid})";

        if ($invoice['order_type'] === 'deposit') {
            if (CongTien($user['id'], $invoice['net_amount'], $memo, $invoice['order_code'])) {
                $db->cong(
                    'users',
                    'total_money',
                    $invoice['net_amount'],
                    "id='{$user['id']}'"
                );
            }
        }

        $db->update('invoices', [
            'status' => 'paid',
            'status_service' => 1
        ], "id='{$invoice['id']}'");

        echo "✔ Paid {$invoice['order_code']}\n";
    }

    /** ================= RUN BANK STC ================= */
    function run_bank_stc($bankRow, $STC_BANKS)
    {
        $bankKey = strtoupper($bankRow['bankName']);
        if (!isset($STC_BANKS[$bankKey]))
            return;

        $endpoint = sprintf(
            $STC_BANKS[$bankKey]['url'],
            urlencode($bankRow['bankPassword']),
            urlencode($bankRow['bankAccount']),
            urlencode($bankRow['apiToken'])
        );

        $json = curl_get($endpoint);
        $data = json_decode($json, true);
        if (($data['status'] ?? '') !== 'success')
            return;

        foreach ($data['transactions'] as $tx) {
            if (($tx['type'] ?? '') !== 'IN')
                continue;
            process_transaction_item(
                $tx,
                'transactionID',
                'amount',
                'description',
                $bankRow['textNameBank'] ?: $bankRow['bankName'],
                $bankRow['bankName']
            );
        }
    }

    /** ================= RUN BANK RPAY ================= */
    function run_bank_rpay($bankRow, $RPAY_BANKS, $RPAY_VIETTELPAY_API)
    {
        $bankKey = strtoupper($bankRow['bankName']);

        if ($bankKey === 'VIETTELPAY') {
            $endpoint = sprintf(
                '%s/%s/%s/%s',
                rtrim($RPAY_VIETTELPAY_API, '/'),
                urlencode($bankRow['bankUsername']),
                urlencode($bankRow['bankPassword']),
                urlencode($bankRow['apiToken'])
            );
        } else {
            if (!isset($RPAY_BANKS[$bankKey])) {
                return;
            }
            $endpoint = sprintf(
                '%s/%s/%s/%s',
                rtrim($RPAY_BANKS[$bankKey], '/'),
                urlencode($bankRow['bankPassword']),
                urlencode($bankRow['bankAccount']),
                urlencode($bankRow['apiToken'])
            );
        }

        $json = curl_get($endpoint);
        $data = json_decode($json, true);
        if (($data['status'] ?? '') !== 'success') {
            return;
        }

        foreach ($data['transactions'] as $tx) {
            if (($tx['type'] ?? '') !== 'IN') {
                continue;
            }
            process_transaction_item(
                $tx,
                'transactionID',
                'amount',
                'description',
                $bankRow['textNameBank'] ?: $bankRow['bankName'],
                $bankRow['bankName']
            );
        }
    }

    /** ================= RUN PINO ACB ================= */
    function run_bank_pino($bankRow)
    {
        if (strtoupper($bankRow['bankName']) !== 'ACB')
            return;

        $url = PINO_API_URL . "?" . http_build_query([
            'api_key' => $bankRow['apiToken'],
            'type' => 'wallet',
            'action' => 'acb_bank',
            'username' => $bankRow['bankUsername'],
            'password' => $bankRow['bankPassword'],
            'stk' => $bankRow['bankAccount'],
            'limit' => 20
        ]);

        $data = json_decode(curl_get($url), true);
        if (($data['status'] ?? '') !== 'success')
            return;

        foreach ($data['transactions'] as $tx) {
            process_transaction_item(
                $tx,
                'transactionNumber',
                'amount',
                'description',
                $bankRow['textNameBank'] ?: $bankRow['bankName'],
                $bankRow['bankName']
            );
        }
    }

    /** ================= RUN WEBTHE ================= */
    function run_wallet_webthe($bankRow)
    {
        $url = PINO_API_URL . "?" . http_build_query([
            'api_key' => $bankRow['apiToken'],
            'type' => 'wallet',
            'action' => 'webthe',
            'webcard' => $bankRow['bankName'],
            'cookie' => $bankRow['walletCookie']
        ]);

        $data = json_decode(curl_get($url), true);
        if (!is_array($data))
            return;

        foreach ($data as $tx) {
            process_transaction_item(
                $tx,
                'TranId',
                'Amount',
                'Description',
                $bankRow['textNameBank'] ?: $bankRow['bankName'],
                $bankRow['bankName']
            );
        }
    }

    /** ================= MAIN ================= */
    $expire = intval($db->site('invoice_expire_time') ?? 0);
    if ($expire <= 0) {
        $expire = 900;
    }
    $time = date('Y-m-d H:i:s', time() - $expire);

    $db->update('invoices', [
        'status' => 'cancel',
        'status_service' => 3
    ], "status='unpaid' AND created_at <'{$time}'");

    $banks = $db->get_list("SELECT * FROM bankings WHERE invoice_pay = 1");

    foreach ($banks as $b) {
        if ($b['typeApi'] === 'STC') {
            run_bank_stc($b, $STC_BANKS);
        } elseif ($b['typeApi'] === 'RPAY') {
            run_bank_rpay($b, $RPAY_BANKS, $RPAY_VIETTELPAY_API);
        } elseif ($b['typeApi'] === 'PINO_API' && empty($b['walletCookie'])) {
            run_bank_pino($b);
        } elseif ($b['typeApi'] === 'PINO_API') {
            run_wallet_webthe($b);
        }
    }

} finally {
    flock($fp, LOCK_UN);
    fclose($fp);
}
