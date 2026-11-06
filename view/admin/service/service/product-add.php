<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Thêm sản phẩm";
load_admin_data('service/service/product-add');
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
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Stt</label>
                                    <input type="number" name="sort_order" value="0" class="form-control" required>
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
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Tự tạo nếu bỏ trống">
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Kiểu hiện sản phẩm</label>
                                    <select class="form-select" name="type">
                                        <option value="list">Danh sách</option>
                                        <option value="image">Hình ảnh</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Nhãn ( Chữ ở dưới tên sản phẩm )</label>
                                    <input class="form-control" name="nhan" type="text">
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required="">
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Lượt mua ảo</label>
                                    <input type="number" name="buy_fake" class="form-control" value="0" required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Lượt xem</label>
                                    <input type="number" name="view" class="form-control" value="0" required>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status" required>
                                    <option value="1">Hiển thị</option>
                                    <option value="0">Ẩn</option>
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
                                            <!-- Trường 1: Tài Khoản -->
                                            <tr>
                                                <td data-label="Kiểu dữ liệu">
                                                    <select class="form-control" name="data_type[]">
                                                        <option value="input" selected>Nhập số & chữ</option>
                                                        <option value="select">Chọn dữ liệu</option>
                                                        <option value="number">Nhập số</option>
                                                        <option value="password">Mật khẩu</option>
                                                    </select>
                                                </td>
                                                <td data-label="Tên hiển thị">
                                                    <input class="form-control" name="data_name[]" type="text" value="Tài Khoản">
                                                </td>
                                                <td data-label="Giá trị">
                                                    <input class="form-control" name="data_value[]" type="text" placeholder="Phân cách bằng |">
                                                </td>
                                                <td data-label="Hiển thị">
                                                    <select class="form-control" name="data_show[]">
                                                        <option value="on" selected>Hiển thị</option>
                                                        <option value="off">Ẩn</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" onclick="add_()">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Trường 2: Mật Khẩu -->
                                            <tr class="removeclass2">
                                                <td data-label="Kiểu dữ liệu">
                                                    <select class="form-control" name="data_type[]">
                                                        <option value="input" selected>Nhập số & chữ</option>
                                                        <option value="select">Chọn dữ liệu</option>
                                                        <option value="number">Nhập số</option>
                                                        <option value="password">Mật khẩu</option>
                                                    </select>
                                                </td>
                                                <td data-label="Tên hiển thị">
                                                    <input class="form-control" name="data_name[]" type="text" value="Mật Khẩu">
                                                </td>
                                                <td data-label="Giá trị">
                                                    <input class="form-control" name="data_value[]" type="text" placeholder="Phân cách bằng |">
                                                </td>
                                                <td data-label="Hiển thị">
                                                    <select class="form-control" name="data_show[]">
                                                        <option value="on" selected>Hiển thị</option>
                                                        <option value="off">Ẩn</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="removeField(2)"
                                                            title="Xóa trường">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="col-form-label">Chi tiết nhóm</label>
                                <div class="form-group">
                                    <textarea name="description" id="description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm sản phẩm
                            </button>
                            <a href="<?= BASE_URL('admin/service/product-list'); ?>" class="btn btn-secondary">
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
let room = 2; // vì bạn đã có sẵn 2 dòng mặc định

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
                <option value="on" selected>Hiển thị</option>
                <option value="off">Ẩn</option>
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
