<?php
$id = (int)($_GET['id'] ?? 0);
$slider = $db->get_row("SELECT * FROM `sliders` WHERE id = '{$id}'");

if (!$slider) {
    msg_redirect('admin/modules/slider-list', 'error', 'Slider không tồn tại.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        msg_redirect('reload', 'error', 'CSRF token không hợp lệ.');
    }

    check_demo_mode();

    $sort_order     = (int)($_POST['sort_order'] ?? 0);
    $slider_name    = anti_xss($_POST['slider_name'] ?? '');
    $slider_text    = $_POST['slider_text'] ?? '';
    $slider_url     = anti_xss($_POST['slider_url'] ?? '');
    $slider_btn_text= anti_xss($_POST['slider_btn_text'] ?? '');
    $slider_btn_url = anti_xss($_POST['slider_btn_url'] ?? '');
    $status         = (int)($_POST['status'] ?? 1);

    // update file mới nếu có
    $slider_image   = update_file('slider_image', $slider['slider_image'], 'slider');

    if (empty($slider_name)) {
        msg_redirect('reload', 'error', 'Tên slider không được để trống.');
    }

    $update = $db->update('sliders', [
        'slider_name'    => $slider_name,
        'slider_image'   => $slider_image,
        'slider_text'    => $slider_text,
        'slider_url'     => $slider_url,
        'slider_btn_text'=> $slider_btn_text,
        'slider_btn_url' => $slider_btn_url,
        'sort_order'     => $sort_order,
        'status'         => $status,
        'updated_at'     => gettime()
    ], "id = '{$id}'");

    if ($update) {
        msg_redirect('reload', 'success', 'Cập nhật slider thành công.');
    } else {
        msg_redirect('reload', 'error', 'Cập nhật slider thất bại.');
    }
}
