<?php
if (isset($_POST['saveInfo'])) {
        check_demo_mode();

        $id    = (int)($_POST['id'] ?? 0);
        $code  = anti_xss($_POST['code'] ?? '');
        $title = anti_xss($_POST['title'] ?? '');
        $desc  = $_POST['description'] ?? ''; // giữ nguyên HTML CKEditor
        $status = (int)($_POST['status'] ?? 1);

        $data = [
            'code'       => $code,
            'title'      => $title,
            'description'=> $desc,
            'status'     => $status,
            'updated_at' => gettime()
        ];

        if ($id > 0) {
            $db->update('informations', $data, "id = '{$id}'");
            msg_redirect('reload', 'success', 'Cập nhật thông tin thành công!');
        } else {
            $data['created_at'] = gettime();
            $db->insert('informations', $data);
            msg_redirect('reload', 'success', 'Thêm thông tin thành công!');
        }
    }

$info_rows = $db->get_list("SELECT * FROM `informations`");
