<?php
if (isset($_POST['saveCat'])) {
    check_demo_mode();

    $id     = (int)($_POST['id'] ?? 0);
    $name   = anti_xss($_POST['name'] ?? '');
    $slug   = create_slug($name);
    $status = (int)($_POST['status'] ?? 1);

    $data = [
        'name'       => $name,
        'url_key'    => $slug,
        'status'     => $status,
        'updated_at' => gettime()
    ];

    if ($id > 0) {
        $db->update('news_cats', $data, "id = '{$id}'");
        msg_redirect('reload', 'success', 'Cập nhật danh mục thành công!');
    } else {
        $data['created_at'] = gettime();
        $db->insert('news_cats', $data);
        msg_redirect('reload', 'success', 'Thêm danh mục thành công!');
    }
}

$cat_rows = $db->get_list("SELECT * FROM news_cats ORDER BY id DESC");
