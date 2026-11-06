<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý khách hàng";
load_admin_data('account/users');
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
                    <h4 class="card-title mb-0">Danh Sách Khách Hàng</h4>
                </div>
                
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL('admin/users'); ?>" class="mb-3 row g-2 align-items-center">
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
                            <input type="number" name="user_id" class="form-control" placeholder="ID khách hàng" value="<?= anti_xss($_GET['user_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" value="<?= anti_xss($_GET['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="name" class="form-control" placeholder="Tên" value="<?= anti_xss($_GET['name'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= anti_xss($_GET['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="phone" class="form-control" placeholder="Số điện thoại" value="<?= anti_xss($_GET['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="banned" class="form-select">
                                <option value="">-- Trạng thái --</option>
                                <option value="0" <?= (($_GET['banned'] ?? '') === '0') ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="1" <?= (($_GET['banned'] ?? '') === '1') ? 'selected' : '' ?>>Bị khóa</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from_date" class="form-control"
                                value="<?= anti_xss($_GET['from_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" class="form-control"
                                value="<?= anti_xss($_GET['to_date'] ?? '') ?>">
                        </div>

                        
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <a href="<?=BASE_URL('admin/users');?>" class="btn btn-danger w-100">
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
                                    <th>Họ tên</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Email</th>
                                    <th>SDT</th>
                                    <th>Số dư</th>
                                    <th>Nhóm</th>
                                    <th>2FA</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $row): ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="users" data-column="sort_order" data-column-remove="id">
                                            <td style="display: none;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input checkbox_product" data-id="<?= $row['id'] ?>" name="checkbox_product" value="<?= $row['id'] ?>">
                                                </div>
                                            </td>
                                            <td><?= anti_xss($row['id'] ?? 'Không rõ') ?></td>
                                            <td><?= anti_xss($row['name'] ?? 'Không rõ') ?></td>
                                            <td><span class="text-dark" style="display:block"><strong><i class="fa-solid fa-user"></i> <?= anti_xss($row['username'] ?? 'Không rõ') ?></strong></span></td>
                                            <td>
                                                <?php if (!empty($row['email'])): ?>
                                                    <span class="text-dark" style="display:block"><strong><i class="fa-solid fa-envelope"></i> <?= anti_xss($row['email']); ?></strong></span>
                                                <?php else: ?>
                                                    
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($row['phone'])): ?>
                                                    <i class="fa-solid fa-phone"></i> <strong class="text-info"><?= anti_xss($row['phone']); ?></strong>
                                                <?php else: ?>
                                                    
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="<?= ($row['money'] < 1) ? 'text-dark' : 'text-success' ?>" style="display:block">
                                                    <strong><?= format_number($row['money']) ?> VND</strong>
                                                </span>
                                            </td>

                                            <td><?= check_capbac($row['id'])['label'] ?? 'Không rõ' ?></td>
                                            <td>
                                                <?php if (!empty($row['twofactor'])): ?>
                                                    <span class="text-dark" style="display:block"><strong> <?= anti_xss($row['twofactor']); ?></strong></span>
                                                <?php else: ?>
                                                    
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <label class="<?= check_banned($row['id'])['class']?>"><?= check_banned($row['id'])['text']?></label>
                                            </td>
                                            <td><?= format_datetime($row['create_date']) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="<?= BASE_URL('admin/users/'.$row['id'].'/edit'); ?>" role="button">
                                                    <i class="ti ti-edit"></i> Sửa
                                                </a>
                                                <button class="btn btn-sm btn-danger btn-delete-single" 
                                                    data-id="<?= $row['id'] ?>" 
                                                    data-table="users" 
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
                                BASE_URL('admin/users?' . http_build_query(array_merge($_GET, ['page' => null]))),
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