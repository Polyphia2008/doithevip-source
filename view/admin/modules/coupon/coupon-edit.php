<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Sửa mã giảm giá";
load_admin_data('modules/coupon/coupon-edit');
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
                    <a href="javascript:void(0);"><?= $_SERVER['HTTP_HOST']; ?></a>
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
                    <form action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                        <!-- Giữ type cũ, không cho sửa -->
                        <input type="hidden" name="type" value="<?= (int)$coupon['type']; ?>">

                        <div class="row">

                            <!-- Mã giảm giá (KHÔNG CHO SỬA) -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Mã giảm giá</label>
                                <input type="text" class="form-control" value="<?= anti_xss($coupon['code']); ?>" readonly>
                                <small class="text-muted">
                                    Không thể thay đổi mã giảm giá
                                </small>
                            </div>

                            <!-- Số lượng -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Số lượng sử dụng</label>
                                <input type="number" class="form-control" name="amount" value="<?= (int)$coupon['amount']; ?>" min="<?= (int)$coupon['used']; ?>" required>
                                <small class="text-muted">
                                    Đã sử dụng: <?= format_number($coupon['used']); ?> lượt
                                </small>
                            </div>

                            <!-- Giảm giá -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label fw-semibold">Giảm giá (%)</label>
                                <input type="number" class="form-control" name="discount" value="<?= (int)$coupon['discount']; ?>" min="1" max="100" required>
                            </div>

                            <!-- Đơn hàng tối thiểu -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label fw-semibold">Đơn hàng tối thiểu</label>
                                <input type="number" class="form-control" name="min" value="<?= (int)$coupon['min']; ?>" required>
                            </div>

                            <!-- Đơn hàng tối đa -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label fw-semibold">Đơn hàng tối đa</label>
                                <input type="number" class="form-control" name="max" value="<?= (int)$coupon['max']; ?>" required>
                            </div>

                            <!-- Sản phẩm áp dụng -->
                            <div class="mb-4 col-md-12">
                                <label class="form-label">Sản phẩm áp dụng</label>

                                <select class="form-select" name="product_id[]" id="listProduct" multiple>
                                    <option value="">
                                        Áp dụng cho toàn bộ sản phẩm (nếu không chọn)
                                    </option>

                                    <?php if ($coupon['type'] == 0): ?>
                                        <?php foreach ($category_groups_account as $groupItem): ?>
                                            <optgroup label="<?= $groupItem['category']['name']; ?>">
                                                <?php foreach ($groupItem['groups'] as $group): ?>
                                                    <option value="<?= $group['id']; ?>"
                                                        <?= in_array($group['id'], $selectedProducts) ? 'selected' : ''; ?>>
                                                        <?= $group['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php foreach ($category_groups_service as $groupItem): ?>
                                            <optgroup label="<?= $groupItem['category']['name']; ?>">
                                                <?php foreach ($groupItem['groups'] as $product): ?>
                                                    <option value="<?= $product['id']; ?>"
                                                        <?= in_array($product['id'], $selectedProducts) ? 'selected' : ''; ?>>
                                                        <?= $product['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Submit -->
                            <div class="col-12">
                                <a href="<?= BASE_URL('admin/modules/coupon-list'); ?>" class="btn btn-danger">
                                    <i class="ti ti-arrow-left"></i> Quay lại
                                </a>
                                <button name="UpdateCoupon" type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i> Cập nhật
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const multipleCancelButton = new Choices(
        '#listProduct', {
            allowHTML: true,
            removeItemButton: true,
        }
    );
    function random(length) {
        var result = '';
        var characters = 'QWERTYUPASDFGHJKZXCVBNM123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }
    function randomCode(){
        document.getElementById('code').value = random(8);
    }
</script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>