<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý đơn hàng";
load_admin_data('service/account/order-list');
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
                <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $_SERVER['HTTP_HOST']; ?></a></li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="card-title mb-0">Danh Sách Đơn Hàng</h4>
                </div>
                <div class="card-body">

                    <!-- form lọc -->
                    <form method="GET" action="<?= BASE_URL('admin/account/orders'); ?>" class="mb-3 row g-2 align-items-center">
                        <div class="col-md-2">
                            <input type="text" name="order_code" class="form-control" placeholder="Mã đơn" value="<?= anti_xss($_GET['order_code'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="user_id" class="form-control" placeholder="ID người mua" value="<?= anti_xss($_GET['user_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="username" class="form-control" placeholder="Tên khách hàng" value="<?= anti_xss($_GET['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" class="form-select">
                                <option value="">-- Danh Mục --</option>
                                <?php foreach ($filter_categories as $category) {?>
                                    <option value="<?= $category['id']; ?>"<?= ($_GET['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                        <?= anti_xss($category['name']); ?>
                                    </option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="group_id" class="form-select">
                                <option value="">-- Sản Phẩm --</option>
                                <?php foreach ($filter_groups as $group) {?>
                                    <option value="<?= $group['id']; ?>"<?= ($_GET['group_id'] ?? '') == $group['id'] ? 'selected' : ''; ?>>
                                        <?= anti_xss($group['name']); ?>
                                    </option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="99" <?= ($_GET['status'] ?? '') == '99' ? 'selected' : '' ?>>Chờ xử lý</option>
                                <option value="1" <?= ($_GET['status'] ?? '') == '1' ? 'selected' : '' ?>>Thành công</option>
                                <option value="2" <?= ($_GET['status'] ?? '') == '2' ? 'selected' : '' ?>>Đang xử lý</option>
                                <option value="3" <?= ($_GET['status'] ?? '') == '3' ? 'selected' : '' ?>>Hủy và không hoàn tiền</option>
                                <option value="4" <?= ($_GET['status'] ?? '') == '4' ? 'selected' : '' ?>>Hủy và hoàn tiền</option>
                                <option value="5" <?= ($_GET['status'] ?? '') == '5' ? 'selected' : '' ?>>Bị khóa</option>
                                <option value="6" <?= ($_GET['status'] ?? '') == '6' ? 'selected' : '' ?>>Không thanh toán</option>
                                <option value="7" <?= ($_GET['status'] ?? '') == '7' ? 'selected' : '' ?>>Không xác định</option>
                            </select>
                        </div>
                        <div class="col-md-2"><input type="date" name="from_date" class="form-control"
                                value="<?= anti_xss($_GET['from_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-2"><input type="date" name="to_date" class="form-control"
                                value="<?= anti_xss($_GET['to_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i> Tìm kiếm</button>
                            <a href="<?= BASE_URL('admin/account/orders'); ?>" class="btn btn-danger w-100">
                                <i class="ti ti-trash me-1"></i> Xóa lọc</a>
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
                    <!-- bảng dữ liệu -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col"> ID Đơn</th>
                                    <th scope="col"> ID Nick, Mã Đơn</th>
                                    <th scope="col"> Người Mua, Người Bán</th>
                                    <th scope="col"> Danh Mục, Sản Phẩm </th>
                                    <th scope="col"> Thành Tiền </th>
                                    <th scope="col"> Trạng Thái</th>
                                    <th scope="col"> Thời Gian Mua </th>
                                    <th scope="col"> Hành Động </th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else:
                                    foreach ($orders as $row):
                                    ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="orders" data-column="sort_order" data-column-remove="id">
                                            <th style="display: none;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                                </div>
                                            </th>
                                            <th> <?= $row['id'] ?></th>
                                            <th> ID Nick: <b class="text-primary">#<?= $row['service_id'] ?></b><br><b class="text-danger"><?= $row['order_code'] ?></b></th>
                                            <th>
                                                <a class="text-primary" href="<?= BASE_URL('admin/users/' . $row['user_id'] . '/edit') ?>"><?= $row['buyer_username'] ?>
                                                    [ID <?= $row['user_id'] ?>]</a><br>
                                                Người Bán: <a class="text-danger" href="<?= BASE_URL('admin/users/' . $row['seller_id'] . '/edit') ?>"><?= $row['seller_username'] ?>
                                                    [ID <?= $row['seller_id'] ?>]</a>
                                            </th>
                                            <th>
                                                <a class="text-primary" href="<?= BASE_URL('admin/account/category-edit/' . $row['category_id']) ?>"><?= $row['category_name'] ?></a><br>
                                                Sản Phẩm: <a class="text-danger" href="<?= BASE_URL('admin/account/product-edit/' . $row['group_id']) ?>"><?= $row['group_name'] ?></a>
                                            </th>
                                            <th>
                                                <?= format_number($row['invoice_total']); ?><sup>đ</sup>  
                                            </th>
                                            <th><span class="<?= $row['status_info']['class'] ?>"><?= $row['status_info']['text'] ?></span></th>
                                            <th>
                                                <?= format_datetime($row['created_at']) ?>
                                            </th>
                                            <th>
                                                <a href="/admin/account/nick-edit/<?= $row['service_id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="ti ti-eye me-1"></i> Chi tiết nick
                                                </a>
                                                <?php if ($row['status'] == 4): ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="ti ti-circle-check me-1"></i> Đã hoàn tiền
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-warning btn-refund"
                                                            data-id="<?= $row['id'] ?>" 
                                                            data-table="orders" 
                                                            data-column="id">
                                                        <i class="ti ti-credit-card-refund me-1"></i> Hoàn tiền
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="orders" 
                                                    data-column-remove="id">
                                                    <i class="ti ti-trash"></i> Xóa
                                                </button>
                                            </th>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- phân trang -->
                    <?php
                    if ($total > $limit) {
                        echo '<div class="text-center">' . phan_trang_admin(
                            BASE_URL('admin/account/orders?' . http_build_query(array_merge($_GET, ['page' => null]))),
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