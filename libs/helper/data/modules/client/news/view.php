<?php
$slug = anti_xss($_GET['slug'] ?? '');
if (empty($slug)) {
    msg_redirect(BASE_URL('news'), 'error', 'Bài viết không tồn tại!');
}

$news = $db->get_row("
    SELECT n.*, c.name AS cat_name
    FROM news n
    LEFT JOIN news_cats c ON n.cats = c.id
    WHERE n.news_slug = '{$slug}'
      AND n.status = 1
    LIMIT 1
");
if (!$news) {
    msg_redirect(BASE_URL('news'), 'error', 'Bài viết không tồn tại!');
}

$db->update("news", ['view_count' => intval($news['view_count']) + 1], "id = '{$news['id']}'");

$title = $news['title'];
