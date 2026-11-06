<?php
if (isset($_POST['saveNews'])) {
    check_demo_mode();

    $id     = (int)($_POST['id'] ?? 0);
    $titleN = anti_xss($_POST['title'] ?? '');
    $slug   = create_slug($titleN);
    $desc   = $_POST['description'] ?? '';
    $status = (int)($_POST['status'] ?? 1);
    $cats   = (int)($_POST['cats'] ?? 0);

    if ($id > 0) {
        $rowOld = $db->get_row("SELECT * FROM `news` WHERE `id` = '{$id}'");
        $image  = update_file('image', $rowOld['image'] ?? '', 'news');
    } else {
        $image = upload_file('image', 'news');
    }

    $data = [
        'title'       => $titleN,
        'news_slug'   => $slug,
        'description' => $desc,
        'status'      => $status,
        'cats'        => $cats,
        'image'       => $image,
        'updated_at'  => gettime()
    ];

    if ($id > 0) {
        $db->update('news', $data, "id = '{$id}'");
        msg_redirect('reload', 'success', 'Cập nhật tin tức thành công!');
    } else {
        $data['created_at'] = gettime();
        $db->insert('news', $data);
        msg_redirect('reload', 'success', 'Thêm tin tức thành công!');
    }
}

$news_rows = $db->get_list("SELECT n.*, c.name as cat_name FROM news n LEFT JOIN news_cats c ON n.cats = c.id ORDER BY n.id DESC");
$news_cats = $db->get_list("SELECT * FROM news_cats WHERE status = 1 ORDER BY name ASC");
