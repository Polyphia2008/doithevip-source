<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm tài khoản";
load_admin_data('service/account/nick-add');
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
                            <?php if ($type === 'random'): ?>

                                <?php
                                    $placeholder = '';
                                    for ($i = 0; $i < count($detail['data']); $i++) {
                                        $placeholder .= $detail['data'][$i]['label'];
                                        if ($i < count($detail['data']) - 1) $placeholder .= '|';
                                    }
                                ?>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Nhập dữ liệu tài khoản</label>
                                        <textarea class="form-control" name="data" rows="4" placeholder="<?= $placeholder ?>" required></textarea><br>
                                        <div class="alert alert-dark d-flex align-items-center mb-0" role="alert">
                                            <iconify-icon icon="solar:balloon-bold-duotone" class="fs-20 me-1"></iconify-icon>
                                            <div class="lh-1"><strong>Hỗ trợ định dạng - </strong> <b>tài khoản|mật khẩu</b>, <b>tài khoản:mật khẩu</b>, <b>tài khoản/mật khẩu</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>

                                <?php for ($i = 0; $i < count($detail['data']); $i++): ?>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label"><?= $detail['data'][$i]['label'] ?></label>

                                            <?php if (in_array($detail['data'][$i]['type'], ['input','password'])): ?>
                                                <input type="text" class="form-control" name="<?= $detail['data'][$i]['name'] ?>" placeholder="<?= $detail['data'][$i]['label'] ?>" required>
                                            <?php elseif ($detail['data'][$i]['type'] === 'number'): ?>
                                                <input type="number" class="form-control" name="<?= $detail['data'][$i]['name'] ?>" placeholder="<?= $detail['data'][$i]['label'] ?>" required>
                                            <?php elseif ($detail['data'][$i]['type'] === 'select'): ?>
                                                <select class="form-control" name="<?= $detail['data'][$i]['name'] ?>" required>
                                                    <?php foreach (explode('|', $detail['data'][$i]['value']) as $v): ?>
                                                        <option value="<?= $v ?>"><?= $v ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endfor; ?>

                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Giá bán</label>
                                        <input type="number" name="price" class="form-control" required>
                                    </div>
                                </div>
                                

                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Hình ảnh <b style="color: var(--greeva-info);">(Có thể chọn nhiều)</b></label>
                                        <input type="file" name="image[]" class="form-control" accept="image/*" multiple required>
                                    </div>
                                </div>


                            <?php endif; ?>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Khuyến mại</label>
                                    <input type="number" name="sale" class="form-control" value="0" min="0" placeholder="Nhập % khuyến mại"
                                    >
                                </div>
                            </div>

                        </div>
                                                        
                        <!-- =====================
                            ACTION
                        ====================== -->
                        <div class="md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm tài khoản
                            </button>

                            <a href="<?= BASE_URL("admin/account/nick-list/{$id}"); ?>" class="btn btn-secondary">
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
