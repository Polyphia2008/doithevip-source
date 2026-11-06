<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm gói dịch vụ";
load_admin_data('service/service/pack-add');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 fw-bold mb-0"><?= $title ?></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <label class="form-label">STT</label>
                                <input type="number" name="sort_order" value="1" class="form-control">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Tên gói</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Giá tiền</label>
                                <input type="number" name="price" class="form-control" value="0">
                            </div>

                            <?php if ($group['type'] === 'image'): ?>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <?php endif; ?>


                            <div class="mb-3 col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm gói
                            </button>
                            <a href="<?= BASE_URL('admin/service/pack-list/'.$group['id'].'') ?>" class="btn btn-secondary">
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