<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý tài khoản";
load_admin_data('service/account/nick-list');
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
                    <h4 class="card-title mb-0">Danh Sách Tài Khoản</h4>
                    <a href="<?= BASE_URL('admin/account/nick-add/' . $id); ?>" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Thêm Tài Khoản
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="GET" class="mb-3 row g-2 align-items-center">
                        <div class="col-md-2">
                            <select name="limit" class="form-select">
                                <option value="">-- Số dòng / trang --</option>
                                <?php foreach ([5, 10, 20, 50, 100, 500, 1000, 5000, 10000, 15000, 20000, 30000, 40000, 50000] as $opt): ?>
                                    <option value="<?=$opt?>" <?= ($limit == $opt ? 'selected' : '') ?>>
                                        <?=format_number($opt)?> dòng
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="id_acc" class="form-control" placeholder="ID tài khoản" value="<?= anti_xss($_GET['id_acc'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="seller_id" class="form-control" placeholder="ID người bán" value="<?= anti_xss($_GET['seller_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="buyer_id" class="form-control" placeholder="ID người mua" value="<?= anti_xss($_GET['buyer_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="taikhoan" class="form-control" placeholder="Tài khoản" value="<?= anti_xss($_GET['taikhoan'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="matkhau" class="form-control" placeholder="Mật khẩu" value="<?= anti_xss($_GET['matkhau'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="price_sort" class="form-select">
                                <option value="">-- Giá bán --</option>
                                <option value="price_asc" <?= ($_GET['price_sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Tăng dần</option>
                                <option value="price_desc" <?= ($_GET['price_sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Giảm dần</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Đang Bán</option>
                                <option value="0" <?= ($_GET['status'] ?? '') == '0' ? 'selected' : '' ?>>Đã Bán</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/account/product-list');?>" class="btn btn-danger w-100">
                                <i class="ti ti-trash"></i> Xóa lọc
                            </a>
                        </div>
                    </form>
                    <div class="d-flex mb-3 align-items-center flex-wrap gap-2">
                        <div class="btn-list" id="bulk-action-buttons" style="display: none;">
                            <button type="button" id="btn_delete_selected"
                                class="btn btn-outline-danger btn-sm btn-rounded shadow-danger btn-wave">
                                <i class="ti ti-trash"></i> Xóa nhanh
                            </button>
                            <span id="selected-counter" class="ms-2 align-self-center text-primary fw-semibold"></span>
                        </div>
                        <div class="ms-auto d-flex gap-2">
                            <button id="select-all-btn" type="button"
                                class="btn btn-outline-primary btn-sm btn-rounded shadow btn-wave">
                                <i class="ti ti-checks"></i> Chọn tất cả
                            </button>
                            <button id="deselect-all-btn" type="button"
                                class="btn btn-outline-primary btn-sm btn-rounded shadow btn-wave">
                                <i class="ti ti-x"></i> Bỏ chọn tất cả
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Nick</th>
                                    <th>Tên Danh Mục, Sản Phẩm</th>
                                    <th>Người Bán, Mua</th>
                                    <th>Giá Bán, Khuyến Mại</th>
                                    <th>Thumbnail</th>
                                    <th>Kiểu Bán</th>
                                    <th>Trạng Thái</th>
                                    <th>Thời Gian</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $row): ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="accounts" data-column-remove="id">
                                            <td style="display: none;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <?= anti_xss($row['id']) ?>
                                            </td>
                                            <td>
                                                <?= $row['category_name'] ?><br>
                                                Sản Phẩm:   <?= $row['group_name'] ?>
                                            </td>
                                            <td>
                                                Người Bán: 
                                                <a class="text-primary" href="<?=BASE_URL('admin/users/'.$row['seller_id'].'/edit');?>">
                                                    <?= $row['seller_username'] ?> [ID <?=$row['seller_id'];?>]
                                                </a><br>
                                                Người Mua:   
                                                <a class="text-primary" href="<?=BASE_URL('admin/users/'.$row['buyer_id'].'/edit');?>">
                                                    <?= $row['buyer_username'] ?> [ID <?=$row['buyer_id'];?>]
                                                </a>
                                            </td>
                                            <td>
                                                Giá Bán: 
                                                <?= format_number($row['price']) ?> <sup>đ</sup><br>
                                                Khuyến mại:   
                                                <?= format_number($row['sale']) ?> <sup>%</sup>
                                            </td>
                                            <td>
                                                <a data-fancybox="gallery" href="<?= BASE_URL($row['thumbnail']); ?>" data-caption="<strong> ID Nick <?= anti_xss($row['id']) ?> </strong>">
                                                    <img src="<?= BASE_URL($row['thumbnail']);?>" class="img-fluid" alt="ID Nick <?= anti_xss($row['id']) ?>" style="width: 80px; border-radius: 3px;">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="<?= $row['type_info']['class'] ?> text-decoration-none">
                                                    <?= $row['type_info']['text'] ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div>
                                                    <input type="checkbox"
                                                        class="status-toggle"
                                                        id="switch<?=$row['id'];?>"
                                                        data-id="<?=$row['id'];?>"
                                                        data-table="accounts"
                                                        data-column="status"
                                                        data-switch="success"
                                                        <?= $row['status'] == '1' ? 'checked' : '' ?>>
                                                    <label for="switch<?=$row['id'];?>"
                                                        data-on-label="Stock"
                                                        data-off-label="Sell"
                                                        class="mb-0 d-block">
                                                    </label>
                                                </div>
                                            </td>
                                            <td> Ngày Tạo: <?= format_datetime($row['created_at']) ?> <br> Ngày Cập Nhật: <?= format_datetime($row['updated_at']) ?> </td>
                                            <td>
                                                <a href="/admin/account/nick-edit/<?= $row['id'] ?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="ti ti-edit"></i> Sửa
                                                </a>
                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="accounts" 
                                                    data-column-remove="id">
                                                    <i class="ti ti-trash"></i> Xóa
                                                </button>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    if ($total > $limit) {

                        // base url dạng path param
                        $base_url = BASE_URL("admin/account/nick-list/{$id}");

                        // giữ lại filter, bỏ page + id
                        $query = $_GET;
                        unset($query['page'], $query['id']);

                        if (!empty($query)) {
                            $base_url .= '?' . http_build_query($query);
                        }

                        echo '<div class="text-center">' . phan_trang_admin(
                            $base_url,
                            $start,
                            $total,
                            $limit
                        ) . '</div>';
                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
</div>
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>