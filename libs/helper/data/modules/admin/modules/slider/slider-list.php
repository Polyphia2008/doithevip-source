<?php
$where = " `id` > 0 ";

// lọc theo trạng thái
if (isset($_GET['status']) && $_GET['status'] !== '' && is_numeric($_GET['status'])) {
    $status = intval($_GET['status']);
    $where .= " AND `status` = '{$status}'";
}

// lọc theo tên slider
if (!empty($_GET['name'])) {
    $name = anti_xss($_GET['name']);
    $where .= " AND `slider_name` LIKE '%{$name}%'";
}

// lọc theo ngôn ngữ
if (!empty($_GET['lang'])) {
    $lang = anti_xss($_GET['lang']);
    $where .= " AND `lang` = '{$lang}'";
}

$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 20;
$start = ($page - 1) * $limit;

$total   = $db->num_rows("SELECT * FROM `sliders` WHERE $where");
$sliders = $db->get_list("SELECT * FROM `sliders` WHERE $where ORDER BY `sort_order` ASC, `id` DESC LIMIT $limit OFFSET $start");
