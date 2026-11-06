<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm mã giảm giá";
load_admin_data('modules/coupon/coupon-add');
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
                        <div class="row">
                            <!-- Mã giảm giá -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Mã giảm giá</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="code" name="code"
                                        placeholder="Nhập mã giảm giá cần tạo" required>
                                    <button type="button" onclick="randomCode()" class="btn btn-danger">
                                        <i class="ti ti-refresh"></i> Tạo ngẫu nhiên
                                    </button>
                                </div>
                            </div>

                            <!-- Số lượng -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Số lượng sử dụng</label>
                                <input type="number" class="form-control" name="amount"
                                    placeholder="Nhập số lượt sử dụng" required>
                            </div>

                            <!-- Loại dịch vụ -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Loại dịch vụ</label>
                                <select class="form-select" name="type" id="serviceType" required>
                                    <option value="">-- Chọn loại dịch vụ --</option>
                                    <option value="0">Bán tài khoản</option>
                                     <option value="1">Dịch vụ game</option> 
                                </select>
                            </div>
                            <!-- Đơn hàng tối thiểu -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label fw-semibold">Đơn hàng tối thiểu</label>
                                <input type="number" class="form-control" name="min" value="1000" required>
                                <small class="text-muted">
                                    Giá trị tối thiểu để áp dụng mã
                                </small>
                            </div>

                            <!-- Đơn hàng tối đa -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label fw-semibold">Đơn hàng tối đa</label>
                                <input type="number" class="form-control" name="max" value="10000000" required>
                                <small class="text-muted">
                                    Giá trị tối đa được phép sử dụng
                                </small>
                            </div>

                            <!-- Giảm giá -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label fw-semibold">Giảm giá (%)</label>
                                <input type="number" class="form-control" name="discount"
                                    placeholder="VD: 10 = giảm 10%" required>
                            </div>
                            <!-- Sản phẩm áp dụng -->
                            <div class="mb-4 col-md-12">
                                <label class="form-label">Sản phẩm áp dụng</label>
                                <select class="form-select" name="product_id[]" id="listProduct" multiple>
                                    <?php
                                    if (isset($_GET['serviceType'])):
                                        echo "<option value=''>Mặc định sẽ áp dụng cho toàn bộ sản phẩm nếu không chọn</option>";

                                        if ($_GET['serviceType'] == 0):
                                            foreach ($category_groups_account as $groupItem):
                                                ?>
                                                <optgroup label="<?= $groupItem['category']['name'] ?>">
                                                    <?php foreach ($groupItem['groups'] as $group):?>
                                                        <option value="<?= $group['id'] ?>">
                                                            <?= $group['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach;

                                        elseif ($_GET['serviceType'] == 1):
                                            foreach ($category_groups_service as $groupItem):
                                                ?>
                                                <optgroup label="<?= $groupItem['category']['name'] ?>">
                                                    <?php foreach ($groupItem['groups'] as $product):?>
                                                        <option value="<?= $product['id'] ?>">
                                                            <?= $product['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach;
                                        endif;
                                    else:
                                        echo "<option value=''>Vui lòng chọn loại dịch vụ</option>";
                                    endif;
                                    ?>
                                </select>
                            </div>
                            <!-- Submit -->
                            <div class="col-12">
                                <button name="AddCoupon" type="submit" class="btn btn-info">
                                    <i class="ti ti-plus me-1"></i> Thêm mã giảm giá
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
    var serviceType = <?php echo isset($_GET['serviceType']) ? $_GET['serviceType'] : '""'; ?>;
    var selectElement = document.getElementById('serviceType');
    selectElement.value = serviceType;
    document.getElementById('serviceType').addEventListener('change', function () {
        var selectedValue = this.value;
        var url = '/admin/modules/coupon-add';

        // Chỉ thêm tham số ?serviceType nếu có giá trị
        if (selectedValue) {
            url += '?serviceType=' + selectedValue;
        }

        window.location.href = url; // Chuyển hướng tới URL mới
    });
</script>
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