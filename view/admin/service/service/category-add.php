<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm danh mục";
load_admin_data('service/service/category-add');
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
                                <label class="form-label">Stt</label>
                                <input type="number" name="sort_order" value="0" class="form-control">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tên danh mục</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Tự tạo nếu bỏ trống">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm danh mục
                            </button>
                            <a href="<?= BASE_URL('admin/service/category-list'); ?>" class="btn btn-secondary">
                                Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('name').addEventListener('input', function () {
    let text = this.value.toLowerCase();

    // Bỏ dấu tiếng Việt
    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Thay ký tự không phải chữ/số thành dấu gạch ngang
    text = text.replace(/[^a-z0-9]+/g, '-');

    // Bỏ dấu gạch ngang ở đầu/cuối
    text = text.replace(/^-+|-+$/g, '');

    document.getElementById('slug').value = text;
});
</script>


<?php 
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
