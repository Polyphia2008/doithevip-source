<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm slider";
load_admin_data('modules/slider/slider-add');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?= $title; ?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
                </li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        <div class="row g-3">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Thứ tự (Sort Order)</label>
                                <input type="number" name="sort_order" value="0" class="form-control">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tên Slider</label>
                                <input type="text" name="slider_name" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="slider_image" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Nội dung</label>
                                <textarea name="slider_text" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Slider URL (link chính)</label>
                                <input type="text" name="slider_url" class="form-control" placeholder="http://...">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Nút (Text)</label>
                                <input type="text" name="slider_btn_text" class="form-control" placeholder="VD: Xem chi tiết">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Nút (Link URL)</label>
                                <input type="text" name="slider_btn_url" class="form-control" placeholder="http://...">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm Slider
                            </button>
                            <a href="<?= BASE_URL('admin/modules/slider-list'); ?>" class="btn btn-secondary">
                                Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
