<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý tin tức";
load_admin_data('news/new-list');
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
                    <h4 class="card-title mb-0">Danh Sách Tin Tức</h4>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newsModal" onclick="openAddNews()">
                        <i class="ti ti-plus"></i> Thêm Mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Danh mục</th>
                                    <th>Lượt xem</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $isd = 0;
                                foreach ($news_rows as $row) {
                                    $isd++;
                                ?>
                                <tr>
                                    <td><?= $isd; ?></td>
                                    <td>
                                        <a data-fancybox="gallery" href="<?= BASE_URL($row['image']) ?>" data-caption="<strong> <?= anti_xss($row['title']) ?> </strong>">
                                            <img src="<?= BASE_URL($row['image']) ?>" class="img-fluid" alt="<?= anti_xss($row['title']) ?>" style="width: 80px; border-radius: 3px;">
                                        </a>
                                    </td>
                                    <td><?= $row['title']; ?></td>
                                    <td><?= $row['cat_name'] ?? '-'; ?></td>
                                    <td><?= format_number($row['view_count']); ?></td>
                                    <td>
                                        <input type="checkbox"
                                            class="status-toggle"
                                            id="switch<?=$row['id'];?>"
                                            data-id="<?=$row['id'];?>"
                                            data-table="news"
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
                                        data-bs-toggle="modal" data-bs-target="#newsModal"
                                        onclick='openEditNews(<?= json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                            <i class="ti ti-edit"></i> Sửa
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete-single"
                                                data-id="<?= $row['id'] ?>" data-table="news" data-column-remove="id">
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
<div class="modal fade" id="newsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="newsModalLabel">Thêm tin tức</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="news_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" id="trangthai">
                                <option value="1">Hiện</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select" name="cats" id="cats">
                                <option value="0">-- Chọn danh mục --</option>
                                <?php foreach ($news_cats as $cat): ?>
                                    <option value="<?=$cat['id'];?>"><?=$cat['name'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <div id="preview_img" class="mt-2"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Nội dung</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="saveNews" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddNews(){
    $('#newsModalLabel').text('Thêm tin tức');
    $('#news_id').val('');
    $('#newsModal form')[0].reset();
    if (CKEDITOR.instances['description']) CKEDITOR.instances['description'].setData('');
    $('#preview_img').html('');
}
function openEditNews(data){
    $('#newsModalLabel').text('Chỉnh sửa tin tức');
    $('#news_id').val(data.id);
    $('#title').val(data.title);
    $('#trangthai').val(data.status);
    $('#cats').val(data.cats);
    if (CKEDITOR.instances['description']) {
        CKEDITOR.instances['description'].setData(data.description || '');
    } else {
        $('#description').val(data.description || '');
    }
    if (data.image) {
        $('#preview_img').html('<img src="'+data.image+'" style="width:100px;object-fit:cover;border-radius:4px;">');
    }
}
// Xem trước ảnh khi chọn file
$('#image').on('change', function() {
    const file = this.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $('#preview_img').html('<img src="'+e.target.result+'" style="width:100px;object-fit:cover;border-radius:4px;">');
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>
