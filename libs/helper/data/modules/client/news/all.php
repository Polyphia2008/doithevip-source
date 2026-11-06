<?php
$limit = 12;
$page = intval($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
}
$start = ($page - 1) * $limit;

$total = $db->get_row("SELECT COUNT(*) as total FROM news WHERE status = 1")['total'] ?? 0;
$news = $db->get_list("SELECT * FROM news WHERE status = 1 ORDER BY id DESC LIMIT {$start}, {$limit}");
