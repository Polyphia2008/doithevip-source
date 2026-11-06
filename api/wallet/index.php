<?php
if (!empty($params['action'])) {
    switch ($params['action']) {
        case 'doithecao':
            assert_card_callback_from_doithevip();
            if(isset($params['request_id']) && isset($params['callback_sign'])){
                $status = check_string($params['status']);
                $message = check_string($params['message']);
                $request_id = check_string($params['request_id']);
                $declared_value = check_string($params['declared_value']);
                $value = check_string($params['value']);
                $amount = check_string($params['amount']);
                $code = check_string($params['code']);
                $serial = check_string($params['serial']);
                $telco = check_string($params['telco']);
                $trans_id = check_string($params['trans_id']);
                $callback_sign = check_string($params['callback_sign']);

                if($callback_sign != md5($db->site('card_partner_key').$code.$serial)){
                    die('callback_sign_error');
                }
                $row = $db->get_row("SELECT * FROM `invoices` WHERE `request_id` = '$request_id' AND `status` = 'unpaid'");
                if (!$row) {
                    die('request_id_error');
                }
                $row_user = $db->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "' ");
                $net_amount = $row['net_amount'];
                if($status == 1){
                    /* CẬP NHẬT TRẠNG THÁI THẺ CÀO */
                    $db->update("invoices", array(
                        'updated_at	'    => gettime(),
                        'status'         => 'paid',
                        'client_note'    => 'Thẻ cào hợp lệ',
                        'net_amount'     => $net_amount
                    ), " `id` = '" . $row['id'] . "' ");
                    $logData = json_encode([
                        'trans_id' => $trans_id ?? null,
                        'request_id' => $request_id ?? null,
                        'amount' => $amount ?? null,
                        'value' => $real_money ?? null,
                        'declared_value' => $declared_value ?? null,
                        'telco' => $telco ?? null,
                        'serial' => $serial ?? null,
                        'code' => $code ?? null,
                        'status' => $status ?? null,
                        'message' => $message ?? null
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $db->insert("cards_log", array(
                        'charging_id' => $row['id'],
                        'updated_at' => gettime(),
                        'created_at' => gettime(),
                        'log' => $logData
                    ));
                    /* CỘNG TIỀN USER */
                    CongTien($row_user['id'], $net_amount, $row['description'], $request_id);
                    $db->cong("users", "total_money", $net_amount, " `id` = '{$row_user['id']}' ");
                    die('payment.success');
                }else{
                    $logData = json_encode([
                        'trans_id' => $trans_id ?? null,
                        'request_id' => $request_id ?? null,
                        'amount' => $amount ?? null,
                        'value' => $real_money ?? null,
                        'declared_value' => $declared_value ?? null,
                        'telco' => $telco ?? null,
                        'serial' => $serial ?? null,
                        'code' => $code ?? null,
                        'status' => $status ?? null,
                        'message' => $message ?? null
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $db->insert("cards_log", array(
                        'charging_id' => $row['id'],
                        'updated_at' => gettime(),
                        'created_at' => gettime(),
                        'log' => $logData
                    ));
                    /* CẬP NHẬT TRẠNG THÁI THẺ CÀO */
                    $db->update("invoices", array(
                        'admin_note'     => $message,
                        'updated_at	'    => gettime(),
                        'status'         => 'cancel',
                        'client_note'    => 'Thẻ cào không hợp lệ hoặc đã được sử dụng',
                        'net_amount'     => $net_amount
                    ), " `id` = '" . $row['id'] . "' ");
                    exit('payment.error');
                }
            }
    }
}
?>
