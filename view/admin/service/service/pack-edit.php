<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Chỉnh sửa gói dịch vụ";
load_admin_data('service/service/pack-edit');
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
                                <input type="number" name="sort_order" value="<?= $pack['sort_order'] ?>" class="form-control">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Tên gói</label>
                                <input type="text" name="name" class="form-control" value="<?= $pack['name'] ?>" required>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Giá tiền</label>
                                <input type="number" name="price" class="form-control" value="<?= $pack['price'] ?>">
                            </div>

                            <?php if ($group['type'] === 'image'): ?>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="image" class="form-control" accept="image/*">

                                <?php if (!empty($pack['image'])): ?>
                                    <div class="mt-2">
                                        <img src="<?= BASE_URL($pack['image']) ?>" style="max-width:80px;border-radius:6px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= $pack['status'] == 1 ? 'selected' : '' ?>>Hiển thị</option>
                                    <option value="0" <?= $pack['status'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu thay đổi
                            </button>
                            <a href="<?= BASE_URL('admin/service/pack-list/'.$group['id']) ?>" class="btn btn-secondary">
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