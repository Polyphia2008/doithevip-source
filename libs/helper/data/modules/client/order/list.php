<?php
$where = " `user_id` = '{$data_user['id']}'";

if (!empty($_GET['order_code'])) {
    $order_code = anti_xss($_GET['order_code']);
    $where .= " AND `order_code` = '$order_code' ";
}

if (!empty($_GET['service_type'])) {
    $service_type = anti_xss($_GET['service_type']);
    $where .= " AND `service_type` = '$service_type' ";
}

if (!empty($_GET['from_date'])) {
    $from = anti_xss($_GET['from_date']) . ' 00:00:00';
    $where .= " AND `time` >= '$from' ";
}

if (!empty($_GET['to_date'])) {
    $to = anti_xss($_GET['to_date']) . ' 23:59:59';
    $where .= " AND `time` <= '$to' ";
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 20;
$start = ($page - 1) * $limit;

$total = $db->num_rows("SELECT * FROM `orders` WHERE $where");
$result = $db->get_list("SELECT * FROM `orders` WHERE $where ORDER BY `id` DESC LIMIT $limit OFFSET $start");

$grouped = [];
foreach ($result as $row) {
    $grouped[$row['order_code']][] = $row;
}

$grouped_display = [];
foreach ($grouped as $order_code => $items) {
    foreach ($items as $row) {
        $serviceData = json_decode($row['service_json'] ?? '[]', true) ?: [];
        $serviceAdditional = $serviceData['service_additional'] ?? null;
        $serviceType = $row['service_type'] ?? '';
        $productName = 'Không xác định';

        if ($serviceAdditional) {
            switch ($serviceType) {
                case 'account':
                    $productName = getRowRealtime('groups_account', $serviceAdditional['group_id'], 'name');
                    break;
                case 'service':
                    $productName = getRowRealtime('groups_service', $serviceAdditional['group_id'], 'name');
                    break;
            }
        }

        $grouped_display[$order_code][] = [
            'row' => $row,
            'productName' => $productName,
            'statusInfo' => status_service($row['id']),
            'serviceType2' => service_type($row['service_type'] ?? ''),
        ];
    }
}
