<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm menu";
load_admin_data('modules/menu-quick-acces/menu-quick-access-add');
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
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tên menu</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">URL</label>
                                <input type="text" name="url" class="form-control" value="#">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Thứ tự</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm menu
                            </button>
                            <a href="<?= BASE_URL('admin/modules/menu-quick-access-list'); ?>" class="btn btn-secondary">
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
