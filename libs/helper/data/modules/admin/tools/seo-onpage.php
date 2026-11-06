<?php
if (isset($_POST['saveSeo'])) {
        check_demo_mode();

        $id   = (int)($_POST['id'] ?? 0);
        $link = anti_xss($_POST['link'] ?? '');
        $lang = anti_xss($_POST['lang'] ?? 'vi');

        $meta = [
            'title'       => anti_xss($_POST['title'] ?? ''),
            'description' => anti_xss($_POST['description'] ?? ''),
            'keywords'    => anti_xss($_POST['keywords'] ?? '')
        ];

        // xử lý upload ảnh
        if ($id > 0) {
            $rowOld = $db->get_row("SELECT * FROM seo WHERE id = '{$id}'");
            $image  = update_file('image', $rowOld['image'] ?? '', 'shared');
        } else {
            $image = upload_file('image', 'shared');
        }

        $data = [
            'link'       => $link,
            'meta'       => json_encode($meta, JSON_UNESCAPED_UNICODE),
            'image'      => $image,
            'lang'       => $lang,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($id > 0) {
            $db->update('seo', $data, "id = '{$id}'");
            msg_redirect('reload', 'success', 'Cập nhật SEO thành công!');
        } else {
            // check link tồn tại
            $check = $db->get_row("SELECT id FROM seo WHERE link = '{$link}' LIMIT 1");
            if ($check) {
                $db->update('seo', $data, "id = '{$check['id']}'");
                msg_redirect('reload', 'success', 'Đã cập nhật SEO trùng link!');
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $db->insert('seo', $data);
                msg_redirect('reload', 'success', 'Thêm SEO thành công!');
            }
        }
    }

$seo_rows = $db->get_list("SELECT * FROM seo");

foreach ($seo_rows as $key => $row) {
    $meta = json_decode($row['meta'] ?? '', true) ?: [];
    $seo_rows[$key]['meta_title'] = $meta['title'] ?? '';
    $seo_rows[$key]['meta_data'] = $meta;
}
