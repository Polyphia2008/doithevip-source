<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm menu";
load_admin_data('modules/menu/menu-add');
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
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Tên menu</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Icon (class)</label>
                                <input type="text" name="icon" class="form-control" placeholder="VD: fas fa-home">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">URL</label>
                                <input type="text" name="url" class="form-control" value="#">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Loại menu</label>
                                <select name="menu_type" id="menu_type" class="form-select">
                                    <option value="header">Header</option>
                                    <option value="footer">Footer</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Menu cha</label>
                                <select name="parent_id" id="parent_id" class="form-select">
                                    <option value="0">-- Không có --</option>
                                    <?php 
                                    // load mặc định header
                                    foreach ($parent_menus as $p): ?>
                                        <option value="<?=$p['id']?>"><?=$p['name']?></option>
                                    <?php endforeach; ?>
                                </select>
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
                            <a href="<?= BASE_URL('admin/modules/menu-list'); ?>" class="btn btn-secondary">
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
document.addEventListener("DOMContentLoaded", function() {
    let menuType = document.getElementById("menu_type");
    let parentSelect = document.getElementById("parent_id");

    menuType.addEventListener("change", function() {
        let type = this.value;

        fetch(window.location.pathname + '?ajax=get_parent&menu_type=' + type)
            .then(res => res.json())
            .then(data => {
                parentSelect.innerHTML = '<option value="0">-- Không có --</option>';
                data.forEach(item => {
                    parentSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            });
    });
});
</script>

<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
