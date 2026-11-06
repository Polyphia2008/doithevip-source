<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý Seo Onpage";
load_admin_data('tools/seo-onpage');
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
                    <h4 class="card-title mb-0">Danh Sách Seo Onpage</h4>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#seoModal" onclick="openAddSeo()">
                        <i class="ti ti-plus"></i> Thêm Mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Link</th>
                                    <th>Tiêu đề</th>
                                    <th>Image</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $isd = 0;
                                foreach ($seo_rows as $row) {
                                    $isd++;
                                    $meta = $row['meta_data'] ?? [];
                                ?>
                                <tr>
                                    <th scope="row"> <?= $isd; ?></th>
                                    <th scope="row"><?= $row['link']; ?></th>
                                    <th scope="row"><?= $row['meta_title'] ?? ($meta['title'] ?? ''); ?></th>
                                    <th scope="row">
                                        <?php if(!empty($row['image'])): ?>
                                            <a data-fancybox="gallery" href="<?= $row['image']; ?>" data-caption="<strong> <?= $meta['title'] ?? ''; ?> </strong>">
                                                <img src="<?= $row['image']; ?>" class="img-fluid" alt="<?= $meta['title'] ?? ''; ?>" style="width: 80px; border-radius: 3px;">
                                            </a>
                                        <?php endif; ?>
                                    </th>
                                    <th scope="row"><?= $row['lang']; ?></th>
                                    <th scope="row">
                                        <a href="#" class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" data-bs-target="#seoModal"
                                        onclick='openEditSeo(<?= json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                            <i class="ti ti-edit"></i> Sửa
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete-single"
                                                data-id="<?= $row['id'] ?>" data-table="seo" data-column-remove="id">
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
<div class="modal fade" id="seoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="seoModalLabel">Thêm SEO</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="seo_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" id="link" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngôn ngữ</label>
                            <input type="text" class="form-control" name="lang" id="lang" value="vi">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Tiêu đề (title)</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Ảnh (image)</label>
                            <input type="file" class="form-control" name="image" id="image">
                            <div id="previewImage" class="mt-2"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Mô tả (description)</label>
                            <textarea class="form-control" name="description" id="description1"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Từ khóa (keywords)</label>
                            <input type="text" class="form-control" name="keywords" id="keywords">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="saveSeo" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddSeo(){
    $('#seoModalLabel').text('Thêm SEO');
    $('#seo_id').val('');
    $('#seoModal form')[0].reset();
    $('#previewImage').html('');
}

function openEditSeo(data){
    $('#seoModalLabel').text('Chỉnh Sửa SEO');
    $('#seo_id').val(data.id);
    $('#link').val(data.link);
    $('#lang').val(data.lang);

    let meta = {};
    if (typeof data.meta === 'string') {
        try { meta = JSON.parse(data.meta); } catch(e){ meta = {}; }
    }

    $('#title').val(meta.title || '');
    $('#keywords').val(meta.keywords || '');
    $('#description1').val(meta.description || '');


    if(data.image){
        $('#previewImage').html('<img src="'+data.image+'" class="img-thumbnail" style="max-height:120px;">');
    } else {
        $('#previewImage').html('');
    }
}
</script>

<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>