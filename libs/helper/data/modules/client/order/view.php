<?php

check_not_login_redirect();



$order_code = anti_xss($_GET['order_code'] ?? null);

if (!$order_code) {

    msg_redirect('', 'error', 'Mã đơn hàng không hợp lệ.');

}



$rows = $db->get_list("SELECT * FROM `orders` WHERE `order_code` = '{$order_code}' AND `user_id` = '{$data_user['id']}'");

if (!$rows) {

    msg_redirect('', 'error', 'Không tìm thấy đơn hàng.');

}



foreach ($rows as $row) {

    if ($row['status'] == '3' || $row['status'] == '4' || $row['status'] == '5' || $row['status'] == '6') {

        msg_redirect('order/list', 'error', 'Dịch vụ đã hoàn tiền hoặc không thể truy cập.');

    }

}



$order_items = [];

foreach ($rows as $orderRow) {

    $statusInfo = status_service($orderRow['id']);

    $serviceData = json_decode($orderRow['service_json'] ?? '[]', true) ?: [];



    $item = [

        'service_type' => $orderRow['service_type'],

        'order_code' => $orderRow['order_code'],

        'amount_formatted' => format_number($orderRow['amount'] ?? 0),

        'created_at' => anti_xss($orderRow['created_at'] ?? $orderRow['time']),

        'status_class' => $statusInfo['class'],

        'status_text' => $statusInfo['text'],

        'service_id' => $serviceData['service_id'] ?? 0,

        'dynamic_fields' => [],

        'images' => [],

        'taikhoan' => '',

        'matkhau' => '',

    ];



    if ($orderRow['service_type'] === 'service') {

        $dynamicData = $serviceData['service_additional']['data'] ?? [];

        foreach ($dynamicData as $field) {

            $item['dynamic_fields'][] = [

                'label' => $field['label'] ?? 'Thông tin',

                'value' => anti_xss(decrypt_data($field['value'])),

            ];

        }



        $image_raw = getRowRealtime('pack_service', $serviceData['service_id'] ?? 0, 'image');

        if (!empty($image_raw)) {

            $tmp = json_decode($image_raw, true);

            $item['images'] = is_array($tmp) ? $tmp : [$image_raw];

        }

    } elseif ($orderRow['service_type'] === 'account') {

        $item['taikhoan'] = anti_xss(decrypt_data(getRowRealtime('accounts', $serviceData['service_id'], 'taikhoan')));

        $item['matkhau'] = anti_xss(decrypt_data(getRowRealtime('accounts', $serviceData['service_id'], 'matkhau')));



        if (($serviceData['service_additional']['type'] ?? '') === 'tuchon') {

            $image_raw = getRowRealtime('accounts', $serviceData['service_id'], 'image');

        } else {

            $image_raw = getRowRealtime('groups_account', $serviceData['service_additional']['group_id'] ?? 0, 'image');

        }



        if (!empty($image_raw)) {

            $tmp = json_decode($image_raw, true);

            $item['images'] = is_array($tmp) ? $tmp : [$image_raw];

        }

    }



    $order_items[] = $item;

}

