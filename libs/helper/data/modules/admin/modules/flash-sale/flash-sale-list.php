<?php
/* =======================
 * SAVE FLASH SALE CONFIG
 * ======================= */
if (isset($_POST['save_flash_sale_config'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ!');
    }
    check_demo_mode();

    $status_flash_sale = intval($_POST['status_flash_sale'] ?? 0) === 1 ? 1 : 0;
    $title_flash_sale = anti_xss($_POST['title_flash_sale'] ?? '');

    $db->set_site('status_flash_sale', strval($status_flash_sale));
    $db->set_site('title_flash_sale', $title_flash_sale);

    msg_redirect('reload', 'success', 'Lưu cấu hình Flash Sale thành công');
}

/* =======================
 * ADD FLASH SALE
 * ======================= */
if (isset($_POST['add_flash_sale'])) {
    $name       = anti_xss($_POST['name']);
    $start_time = anti_xss($_POST['start_time']);
    $end_time   = anti_xss($_POST['end_time']);

    if (empty($name) || empty($start_time) || empty($end_time)) {
        msg_redirect('reload', 'error', 'Vui lòng nhập đầy đủ thông tin!');
    }

    $db->insert("flash_sales", [
        'name'       => $name,
        'start_time' => $start_time,
        'end_time'   => $end_time,
        'created_at'=> date('Y-m-d H:i:s')
    ]);
    msg_redirect('reload', 'success', 'Thêm Flash Sale thành công');
}

/* =======================
 * UPDATE FLASH SALE
 * ======================= */
if (isset($_POST['update_flash_sale'])) {
    $id         = intval($_POST['id']);
    $name       = anti_xss($_POST['name']);
    $start_time = anti_xss($_POST['start_time']);
    $end_time   = anti_xss($_POST['end_time']);

    $db->update("flash_sales", [
        'name'       => $name,
        'start_time' => $start_time,
        'end_time'   => $end_time
    ], " `id` = '$id' ");

    msg_redirect('reload', 'success', 'Cập nhật Flash Sale thành công');
}

$status_flash_sale = intval($db->site('status_flash_sale'));
$title_flash_sale = anti_xss($db->site('title_flash_sale'));

/* =======================
 * WHERE + FILTER
 * ======================= */
$where = " `id` > 0 ";

if (!empty($_GET['name'])) {
    $name = anti_xss($_GET['name']);
    $where .= " AND `name` LIKE '%{$name}%'";
}

if (!empty($_GET['time'])) {
    if ($_GET['time'] == '1') {
        $orderBy = "`start_time` ASC";
    } elseif ($_GET['time'] == '2') {
        $orderBy = "`start_time` DESC";
    }
} else {
    $orderBy = "`id` DESC";
}

/* =======================
 * PAGINATION
 * ======================= */
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 20;
$start = ($page - 1) * $limit;

/* =======================
 * QUERY
 * ======================= */
$total = $db->num_rows("SELECT * FROM `flash_sales` WHERE $where");
$flashSales = $db->get_list("
    SELECT * FROM `flash_sales`
    WHERE $where
    ORDER BY $orderBy
    LIMIT $limit OFFSET $start
");
