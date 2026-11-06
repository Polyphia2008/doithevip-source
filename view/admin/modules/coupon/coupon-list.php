<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý mã giảm giá";
load_admin_data('modules/coupon/coupon-list');
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
                    <h4 class="card-title mb-0">Danh Sách Mã Giảm Giá</h4>
                    <a href="<?=BASE_URL('admin/modules/coupon-add');?>" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Thêm mã giảm giá
                    </a>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3 row g-2 align-items-center">
                        <div class="col-md-2">
                            <select name="limit" class="form-select">
                                <option value="">-- Số dòng / trang --</option>
                                <?php foreach ([5, 10, 20, 50, 100, 500] as $opt): ?>
                                    <option value="<?=$opt?>" <?= ($limit == $opt ? 'selected' : '') ?>>
                                        <?=format_number($opt)?> dòng
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="code" class="form-control" placeholder="Mã giảm giá" value="<?= anti_xss($_GET['code'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="amount" class="form-select">
                                <option value="">-- Số lượng sử dụng --</option>
                                <option value="1" <?= ($_GET['amount'] ?? '') === '1' ? 'selected' : '' ?>>Tăng dần</option>
                                <option value="2" <?= ($_GET['amount'] ?? '') === '2' ? 'selected' : '' ?>>Giảm dần</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="discount" class="form-select">
                                <option value="">-- Phần trăm giảm giá --</option>
                                <option value="1" <?= ($_GET['discount'] ?? '') === '1' ? 'selected' : '' ?>>Tăng dần</option>
                                <option value="2" <?= ($_GET['discount'] ?? '') === '2' ? 'selected' : '' ?>>Giảm dần</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/modules/coupon-list');?>" class="btn btn-danger w-100">
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
                                    <th>ID</th>
                                    <th>Mã giảm giá</th>
                                    <th>Sản phẩm áp dụng</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Số lượng sử dụng</th>
                                    <th>Đã sử dụng</th>
                                    <th>Giảm</th>
                                    <th>Thời gian tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($coupons)): ?>
                                    <tr><td colspan="9" class="text-center">Không có dữ liệu.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($coupons as $row): ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="coupons" data-column-remove="id">
                                            <td style="display: none;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                                </div>
                                            </td>
                                            <td><?=$row['id'];?></td>
                                            <td><b><?=$row['code'];?></b> (<?= $row['usage_html']; ?>)</td>
                                            <td>
                                                <?php if (empty($row["product_id"])): ?>
                                                    <span class="badge badge-outline-info">Áp dụng cho toàn bộ sản phẩm</span>
                                                <?php else: ?>
                                                    <?php foreach ($row['product_badges'] as $badge): ?>
                                                        <span class="badge <?= $badge['class'] ?>"><?= $badge['name'] ?></span><br>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row["type"] == 0): ?>
                                                    <span class="badge badge-outline-success">Bán Tài Khoản</span>
                                                <?php else: ?>
                                                    <span class="badge badge-outline-dark">Dịch Vụ Game</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge badge-outline-primary"><?=format_number($row['amount']);?></span></td>
                                            <td><span class="badge badge-outline-danger"><?=format_number($row['used']);?></span></td>
                                            <td><span class="badge badge-outline-dark"><?=format_number($row['discount']);?>%</span></td>
                                            <td><?=format_datetime($row['created_at'])?></td>
                                            
                                            <td>
                                                <a href="/admin/modules/coupon/<?= $row['id'] ?>/log" class="btn btn-sm btn-dark me-1">
                                                    <i class="ti ti-list-check"></i> Nhật Ký Sử Dụng
                                                </a>
                                               <a href="<?=BASE_URL('admin/modules/coupon/'.$row['id'].'/edit')?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="ti ti-edit"></i> Sửa
                                                </a>
                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="coupons" 
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
                            echo '<div class="text-center">' . phan_trang_admin(
                                BASE_URL('admin/modules/coupon-list?' . http_build_query(array_merge($_GET, ['page' => null]))),
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