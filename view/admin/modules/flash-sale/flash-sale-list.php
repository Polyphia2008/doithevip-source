<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý Flash Sale";
load_admin_data('modules/flash-sale/flash-sale-list');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?=$title;?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
                </li>
                <li class="breadcrumb-item active"><?=$title;?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>

            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh Sách Flash Sale</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddFlashSale">
                            <i class="ti ti-plus"></i> Thêm Flash Sale
                        </button>
                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalFlashSaleConfig">
                            <i class="ti ti-settings"></i> Cấu hình
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <!-- FILTER -->
                    <form method="GET" action="<?= BASE_URL('admin/modules/flash-sale-list'); ?>" class="mb-3 row g-2 align-items-center">
                        <div class="col-md-2">
                            <select name="limit" class="form-select">
                                <option value="">-- Số dòng / trang --</option>
                                <?php foreach ([5,10,20,50,100] as $opt): ?>
                                    <option value="<?=$opt?>" <?=($limit==$opt?'selected':'')?>>
                                        <?=$opt?> dòng
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control"
                                   placeholder="Tên Flash Sale"
                                   value="<?=anti_xss($_GET['name'] ?? '')?>">
                        </div>

                        <div class="col-md-3">
                            <select name="time" class="form-select">
                                <option value="">-- Sắp xếp thời gian --</option>
                                <option value="1" <?=($_GET['time']??'')=='1'?'selected':''?>>Giờ bắt đầu ↑</option>
                                <option value="2" <?=($_GET['time']??'')=='2'?'selected':''?>>Giờ bắt đầu ↓</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/modules/flash-sale-list');?>"
                               class="btn btn-danger w-100">
                                <i class="ti ti-trash"></i> Xóa lọc
                            </a>
                        </div>
                    </form>
                    <div class="d-flex mb-3 align-items-center flex-wrap gap-2">
                        <div class="btn-list" id="bulk-action-buttons" style="display: none;">
                            <button type="button" id="btn_delete_selected"
                                class="btn btn-outline-danger btn-sm btn-rounded shadow-danger btn-wave">
                                <i class="ti ti-trash"></i> Xóa nhanh
                            </button>
                            <span id="selected-counter" class="ms-2 align-self-center text-primary fw-semibold"></span>
                        </div>
                        <div class="ms-auto d-flex gap-2">
                            <button id="select-all-btn" type="button"
                                class="btn btn-outline-primary btn-sm btn-rounded shadow btn-wave">
                                <i class="ti ti-checks"></i> Chọn tất cả
                            </button>
                            <button id="deselect-all-btn" type="button"
                                class="btn btn-outline-primary btn-sm btn-rounded shadow btn-wave">
                                <i class="ti ti-x"></i> Bỏ chọn tất cả
                            </button>
                        </div>
                    </div>                    
                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Flash Sale</th>
                                    <th>Bắt đầu</th>
                                    <th>Kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                            <?php if (empty($flashSales)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($flashSales as $row): ?>
                                    <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="flash_sales" data-column-remove="id">
                                        <td style="display: none;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                            </div>
                                        </td>
                                        <td><?=$row['id'];?></td>
                                        <td><b><?=$row['name'];?></b></td>
                                        <td>
                                            <span class="badge badge-outline-info">
                                                <?=$row['start_time'];?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline-danger">
                                                <?=$row['end_time'];?>
                                            </span>
                                        </td>
                                        <td>
                                            <?=getFlashSaleStatus($row['start_time'],$row['end_time'])['msg'];?>
                                        </td>
                                        <td><?=format_datetime($row['created_at']);?></td>
                                        <td>
                                            <a href="<?=BASE_URL('admin/modules/flash-sale-add/'.$row['id'].'/product');?>"
                                               class="btn btn-sm btn-dark">
                                                <i class="ti ti-list"></i> Sản phẩm
                                            </a>

                                            <button class="btn btn-sm btn-primary btn-edit"
                                                data-id="<?=$row['id'];?>"
                                                data-name="<?=$row['name'];?>"
                                                data-start="<?=$row['start_time'];?>"
                                                data-end="<?=$row['end_time'];?>">
                                                <i class="ti ti-edit"></i> Sửa
                                            </button>

                                            <button class="btn btn-sm btn-danger btn-delete-single"
                                                    data-id="<?=$row['id'];?>"
                                                    data-table="flash_sales"
                                                    data-column-remove="id">
                                                <i class="ti ti-trash"></i> Xóa
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <?php
                    if ($total > $limit) {
                        echo '<div class="text-center mt-3">' .
                            phan_trang_admin(
                                BASE_URL('admin/modules/flash-sale-list?' . http_build_query(array_merge($_GET, ['page' => null]))),
                                $start,
                                $total,
                                $limit
                            ) .
                        '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFlashSaleConfig" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
            <div class="modal-header">
                <h5 class="modal-title">Cấu hình Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Bật/tắt flash sale</label>
                    <select name="status_flash_sale" class="form-select">
                        <option value="1" <?= $status_flash_sale === 1 ? 'selected' : '' ?>>Bật</option>
                        <option value="0" <?= $status_flash_sale === 0 ? 'selected' : '' ?>>Tắt</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tiêu đề flash sale</label>
                    <input type="text" name="title_flash_sale" value="<?= $title_flash_sale; ?>" class="form-control" placeholder="Flash Sale">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary" name="save_flash_sale_config">
                    <i class="ti ti-device-floppy"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalAddFlashSale" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên Flash Sale</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giờ bắt đầu</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giờ kết thúc</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button class="btn btn-primary" name="add_flash_sale">
                    <i class="ti ti-check"></i> Thêm
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalEditFlashSale" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit-id">

            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tên Flash Sale</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giờ bắt đầu</label>
                    <input type="time" name="start_time" id="edit-start" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giờ kết thúc</label>
                    <input type="time" name="end_time" id="edit-end" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button class="btn btn-primary" name="update_flash_sale">
                    <i class="ti ti-device-floppy"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('edit-id').value    = this.dataset.id;
        document.getElementById('edit-name').value  = this.dataset.name;
        document.getElementById('edit-start')._flatpickr.setDate(this.dataset.start, false);
        document.getElementById('edit-end')._flatpickr.setDate(this.dataset.end, false);


        new bootstrap.Modal(document.getElementById('modalEditFlashSale')).show();
    });
});
flatpickr("input[type=time]", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});

</script>

<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
