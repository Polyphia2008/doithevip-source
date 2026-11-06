<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Sửa tài khoản";
load_admin_data('service/account/nick-edit');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">

    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?= $title; ?></h4>
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

                            <?php foreach ($form_fields as $item): ?>
                                <?php
                                    $field = $item['field'];
                                    $name  = $item['name'];
                                    $value = $item['value'];
                                ?>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><?= $field['label']; ?></label>

                                        <?php if (in_array($field['type'], ['input','password'])): ?>
                                            <input type="text"
                                                   class="form-control"
                                                   name="<?= $name; ?>"
                                                   value="<?= $value; ?>"
                                                   required>
                                        <?php elseif ($field['type'] === 'number'): ?>
                                            <input type="number"
                                                   class="form-control"
                                                   name="<?= $name; ?>"
                                                   value="<?= $value; ?>"
                                                   required>
                                        <?php elseif ($field['type'] === 'select'): ?>
                                            <select class="form-control" name="<?= $name; ?>" required>
                                                <?php foreach (explode('|', $field['value']) as $v): ?>
                                                    <option value="<?= $v; ?>" <?= $value == $v ? 'selected' : ''; ?>>
                                                        <?= $v; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if ($type !== 'random'): ?>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Giá bán</label>
                                        <input type="number"
                                               name="price"
                                               class="form-control"
                                               value="<?= (int)$account['price']; ?>"
                                               required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Hình ảnh <b style="color: var(--greeva-info);">(Có thể chọn nhiều)</b></label>

                                        <input type="file"
                                            name="image[]"
                                            class="form-control"
                                            accept="image/*"
                                            multiple>

                                        <?php if (!empty($old_images)): ?>
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                <?php foreach ($old_images as $img): ?>
                                                    <a data-fancybox="gallery"
                                                    href="<?= BASE_URL($img); ?>"
                                                    data-caption="<strong>ID Nick <?= anti_xss($account['id']); ?></strong>">
                                                        <img src="<?= BASE_URL($img); ?>"
                                                            class="img-fluid"
                                                            alt="ID Nick <?= anti_xss($account['id']); ?>"
                                                            style="height:70px;border-radius:6px">
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status" required>
                                    <option value="1" <?= $account['status']== 1 ?'selected':'' ?>>Đang bán</option>
                                    <option value="0" <?= $account['status']== 0 ?'selected':'' ?>>Đã bán</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Khuyến mại</label>
                                    <input type="number" name="sale" class="form-control" value="<?= (int)($account['sale'] ?? 0); ?>" min="0" placeholder="Nhập số khuyến mại"
                                    >
                                </div>
                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu thay đổi
                            </button>

                            <a href="<?= BASE_URL("admin/account/nick-list/{$group['id']}"); ?>"
                               class="btn btn-secondary">
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
