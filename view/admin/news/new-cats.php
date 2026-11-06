<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý danh mục tin tức";
load_admin_data('news/new-cats');
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
                    <h4 class="card-title mb-0">Danh Sách Danh Mục</h4>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#catModal" onclick="openAddCat()">
                        <i class="ti ti-plus"></i> Thêm Mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên danh mục</th>
                                    <th>Slug</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $isd = 0;
                                foreach ($cat_rows as $row) {
                                    $isd++;
                                ?>
                                <tr>
                                    <td><?= $isd; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['url_key']; ?></td>
                                    <td>
                                        <input type="checkbox"
                                            class="status-toggle"
                                            id="switch<?=$row['id'];?>"
                                            data-id="<?=$row['id'];?>"
                                            data-table="news_cats"
                                            data-column="status"
                                            data-switch="success"
                                            <?= $row['status'] == '1' ? 'checked' : '' ?>>
                                        <label for="switch<?=$row['id'];?>"
                                            data-on-label="Hiện"
                                            data-off-label="Ẩn"
                                            class="mb-0 d-block"></label>
                                    </td>
                                    <td><?= format_datetime($row['created_at']); ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#catModal"
                                        onclick='openEditCat(<?= json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                            <i class="ti ti-edit"></i> Sửa
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete-single"
                                                data-id="<?= $row['id'] ?>" data-table="news_cats" data-column-remove="id">
                                            <i class="ti ti-trash"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="catModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="catModalLabel">Thêm danh mục</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cat_id">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" id="cat_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status" id="cat_status">
                            <option value="1">Hiện</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="saveCat" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddCat(){
    $('#catModalLabel').text('Thêm danh mục');
    $('#cat_id').val('');
    $('#catModal form')[0].reset();
}
function openEditCat(data){
    $('#catModalLabel').text('Chỉnh sửa danh mục');
    $('#cat_id').val(data.id);
    $('#cat_name').val(data.name);
    $('#cat_status').val(data.status);
}
</script>

<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>
