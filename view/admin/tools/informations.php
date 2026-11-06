<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý thông tin";
load_admin_data('tools/informations');
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
                    <a href="javascript: void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
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
                    <h4 class="card-title mb-0">Danh Sách Thông Tin</h4>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="openAddInfo()">
                        <i class="ti ti-plus"></i> Thêm Mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã thông tin</th>
                                    <th>Tiêu đề</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $isd = 0;
                                foreach ($info_rows as $row) {
                                    $isd++;
                                ?>
                                <tr>
                                    <th scope="row"> <?= $isd; ?></th>
                                    <th scope="row"><?= $row['code']; ?></th>
                                    <th scope="row"><?= $row['title'] ?? ''; ?></th>
                                    <th scope="row">
                                        <div>
                                            <input type="checkbox"
                                                class="status-toggle"
                                                id="switch<?=$row['id'];?>"
                                                data-id="<?=$row['id'];?>"
                                                data-table="informations"
                                                data-column="status"
                                                data-switch="success"
                                                <?= $row['status'] == '1' ? 'checked' : '' ?>>
                                            <label for="switch<?=$row['id'];?>"
                                                data-on-label="Hiện"
                                                data-off-label="Ẩn"
                                                class="mb-0 d-block">
                                            </label>
                                        </div>
                                    </td>
                                    <th scope="row"><?= format_datetime($row['created_at']); ?></th>
                                    <th scope="row">
                                        <a href="#" class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#infoModal"
                                        onclick='openEditInfo(<?= json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                            <i class="ti ti-edit"></i> Sửa
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete-single"
                                                data-id="<?= $row['id'] ?>" data-table="informations" data-column-remove="id">
                                            <i class="ti ti-trash"></i> Xóa
                                        </button>
                                    </th>
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
<div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="infoModalLabel">Thêm thông tin</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="info_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Mã thông tin</label>
                            <input type="text" class="form-control" name="code" id="code" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" id="trangthai">
                                <option value="1">Hiện</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="saveInfo" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddInfo(){
    $('#infoModalLabel').text('Thêm thông tin');
    $('#info_id').val('');
    $('#infoModal form')[0].reset();

    if (CKEDITOR.instances['description']) {
        CKEDITOR.instances['description'].setData('');
    }
}

function openEditInfo(data){
    $('#infoModalLabel').text('Chỉnh sửa thông tin');
    $('#info_id').val(data.id);
    $('#code').val(data.code);
    $('#trangthai').val(data.status);
    $('#title').val(data.title);

    if (CKEDITOR.instances['description']) {
        CKEDITOR.instances['description'].setData(data.description || '');
    } else {
        $('#description').val(data.description || '');
    }
}

</script>

<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>