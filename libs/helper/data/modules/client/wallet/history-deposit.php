<?php

$where = " `user_id` = '{$data_user['id']}' AND `order_type` = 'deposit' ";

if (!empty($_GET['paygate'])) {

    $paygate = anti_xss($_GET['paygate']);

    $where .= " AND `paygate` = '$paygate' ";

}



$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

$limit = 20;

$start = ($page - 1) * $limit;



$total = $db->num_rows("SELECT * FROM `invoices` WHERE $where");

$result = $db->get_list("SELECT * FROM `invoices` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

$deposit_banks = $db->get_list("SELECT * FROM `bankings` WHERE `invoice_pay` = 1 ");



$deposit_invoices = [];

foreach ($result as $invoice) {

    $status = status_invoice($invoice['id']);

    $bankInfo = json_decode($invoice['bank_info'] ?? '', true) ?: [];



    if (empty($invoice['card_info'])) {

        $payment_info = 'Số tài khoản: <b>' . htmlspecialchars($bankInfo['bankAccount'] ?? '') . '</b><br>';

        $payment_info .= 'Chủ tài khoản: ' . htmlspecialchars($bankInfo['bankOwner'] ?? '');

    } else {

        [$card_code, $card_seri] = explode('|', $invoice['card_info'] . '|');

        $payment_info = 'Seri: <b>' . htmlspecialchars($card_seri) . '</b><br>';

        $payment_info .= 'Mã thẻ: ' . htmlspecialchars($card_code);

    }



    $deposit_invoices[] = [

        'order_code' => $invoice['order_code'],

        'net_amount_formatted' => format_number($invoice['net_amount']),

        'paygate_display' => $bankInfo['textNameBank'] ?? $invoice['paygate'],

        'payment_info' => $payment_info,

        'created_at_formatted' => format_datetime($invoice['created_at']),

        'status_class' => $status['class'],

        'status_text' => $status['text'],

        'client_note' => $invoice['client_note'],

    ];

}

