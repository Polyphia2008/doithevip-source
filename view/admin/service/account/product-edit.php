<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Chỉnh sửa sản phẩm";
load_admin_data('service/account/product-edit');
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
                <li class="breadcrumb-item"><a href="javascript:void(0);"><?=$_SERVER['HTTP_HOST'];?></a></li>
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
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Stt</label>
                                    <input type="number" name="sort_order" value="<?= $row['sort_order'] ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Danh mục</label>
                                    <select class="form-control select2" data-toggle="select2" name="category_id">
                                        <?php foreach ($categories as $category) { ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" name="name" id="name" value="<?= anti_xss($row['name']) ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" value="<?= anti_xss($row['slug']) ?>" class="form-control" placeholder="Tự tạo nếu bỏ trống">
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Loại tài khoản</label>
                                    <select class="form-select" name="type" id="accountType">
                                        <option value="tuchon" <?= $row['type']=='tuchon'?'selected':'' ?>>Tự chọn</option>
                                        <option value="random" <?= $row['type']=='random'?'selected':'' ?>>Thử vận may</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3" id="randomField" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Giá tiền</label>
                                    <input class="form-control" name="price" type="number" value="<?= $row['price'] ?>" placeholder="Nếu là tài khoản vận may thì nhập">
                                </div>
                            </div>
                            
                            <div class="mb-3 col-md-3" id="accountField" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label">Nhãn ( Chữ ở dưới tên sản phẩm )</label>
                                    <input class="form-control" name="nhan" type="text" value="<?= anti_xss($row['nhan']) ?>" placeholder="Nếu là tài khoản tự chọn thì nhập">
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Lượt mua ảo</label>
                                    <input type="number" name="buy_fake" value="<?= $row['buy_fake'] ?>" class="form-control"  required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Lượt xem</label>
                                    <input type="number" name="view" class="form-control" value="<?= $row['view'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <?php if ($row['image']): ?>
                                    <img src="<?=BASE_URL($row['image'])?>" class="mt-2" style="max-height:60px">
                                <?php endif; ?>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status" required>
                                    <option value="1" <?= $row['status']== 1 ?'selected':'' ?>>Hiển thị</option>
                                    <option value="0" <?= $row['status']== 0 ?'selected':'' ?>>Ẩn</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered mobile-table table-hover align-middle mb-0 text-nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kiểu dữ liệu</th>
                                                <th>Tên hiển thị</th>
                                                <th>Giá trị</th>
                                                <th>Hiển thị</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gift">
                                            <?php foreach ($data_fields as $i => $f): ?>
                                            <tr class="removeclass<?= $i ?>">
                                                <td data-label="Kiểu dữ liệu">
                                                    <select class="form-control" name="data_type[]">
                                                        <option <?=$detail['data'][$i]['type'] == 'input' ? 'selected="selected"' : ''?> value="input">Nhập số & chữ</option>
                                                        <option <?=$detail['data'][$i]['type'] == 'select' ? 'selected="selected"' : ''?> value="select">Chọn dữ liệu</option>
                                                        <option <?=$detail['data'][$i]['type'] == 'number' ? 'selected="selected"' : ''?> value="number">Nhập số</option>
                                                        <option <?=$detail['data'][$i]['type'] == 'password' ? 'selected="selected"' : ''?> value="password">Mật khẩu</option>
                                                    </select>
                                                </td>
                                                <td data-label="Tên hiển thị">
                                                    <input name="data_name[]" class="form-control" value="<?= anti_xss($f['label']) ?>" placeholder="Tên hiển thị">
                                                </td>
                                                <td data-label="Giá trị">
                                                    <input name="data_value[]" class="form-control" value="<?= anti_xss($f['value']) ?>" placeholder="Phân cách bằng |">
                                                </td>
                                                <td data-label="Hiển thị">
                                                    <select name="data_show[]" class="form-control">
                                                        <option value="on" <?= $f['show']=='on'?'selected':'' ?>>Hiển thị</option>
                                                        <option value="off" <?= $f['show']=='off'?'selected':'' ?>>Ẩn</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?php if ($i === 0 || $i === 1): ?>
                                                        <button type="button" class="btn btn-success btn-sm" onclick="add_()">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeField(<?=$i ?>)">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="col-form-label">Chi tiết nhóm</label>
                                <div class="form-group">
                                    <textarea name="description" id="description" class="form-control" rows="6">
                                        <?= $row['description'] ?? '' ?>
                                    </textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu sản phẩm
                            </button>
                            <a href="<?= BASE_URL('admin/account/product-list'); ?>" class="btn btn-secondary">
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
let room = <?= count($data_fields) ?>; // vì bạn đã có sẵn 2 dòng mặc định

function add_() {
    room++;

    const wrapper = document.getElementById('gift');

    const tr = document.createElement('tr');
    tr.className = 'removeclass' + room;

    tr.innerHTML = `
        <td data-label="Kiểu dữ liệu">
            <select class="form-control" name="data_type[]">
                <option value="input">Nhập số & chữ</option>
                <option value="select">Chọn dữ liệu</option>
                <option value="number">Nhập số</option>
                <option value="password">Mật khẩu</option>
            </select>
        </td>

        <td data-label="Tên hiển thị">
            <input class="form-control" name="data_name[]" type="text" placeholder="Tên hiển thị">
        </td>

        <td data-label="Giá trị">
            <input class="form-control" name="data_value[]" type="text" placeholder="Phân cách bằng |">
        </td>

        <td data-label="Hiển thị">
            <select class="form-control" name="data_show[]">
                <option value="on">Hiển thị</option>
                <option value="off" selected>Ẩn</option>
            </select>
        </td>

        <td>
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="removeField(${room})"
                    title="Xóa trường">
                <i class="fa fa-minus"></i>
            </button>
        </td>
    `;

    wrapper.appendChild(tr);
}

function removeField(id) {
    const row = document.querySelector('.removeclass' + id);
    if (row) row.remove();
}
$(document).ready(function () {

    function toggleAccountType() {
        const type = $('#accountType').val();

        if (type === 'random') {
            $('#randomField').show();
            $('#accountField').hide();
        } else if (type === 'tuchon') {
            $('#accountField').show();
            $('#randomField').hide();
        } else {
            $('#randomField, #accountField').hide();
        }
    }

    // Khi đổi select
    $('#accountType').on('change', toggleAccountType);

    // Khi load trang edit
    toggleAccountType();
});
document.getElementById('name').addEventListener('input', function () {
    let text = this.value.toLowerCase();

    // Bỏ dấu tiếng Việt
    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Thay ký tự không phải chữ/số thành dấu gạch ngang
    text = text.replace(/[^a-z0-9]+/g, '-');

    // Bỏ dấu gạch ngang ở đầu/cuối
    text = text.replace(/^-+|-+$/g, '');

    document.getElementById('slug').value = text;
});
</script>

<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
