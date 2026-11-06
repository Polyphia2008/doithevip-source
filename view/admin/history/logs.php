<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Lịch sử hoạt động";
load_admin_data('history/logs');
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
                    <h4 class="card-title mb-0">Lịch Sử Hoạt Động</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= BASE_URL('admin/history/logs'); ?>" class="mb-3 row g-2 align-items-center">
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
                            <input type="text" name="username" class="form-control" placeholder="Tên khách hàng" value="<?= anti_xss($_GET['username'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="action" class="form-control" placeholder="Hành động" value="<?= anti_xss($_GET['action'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="ip" class="form-control" placeholder="Địa chỉ IP" value="<?= anti_xss($_GET['ip'] ?? '') ?>" data-toggle="input-mask" data-mask-format="999.999.999.999" data-reverse="true" inputmode="text">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="device" class="form-control" placeholder="Thiết bị" value="<?= anti_xss($_GET['device'] ?? '') ?>">
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
                            <a href="<?=BASE_URL('admin/history/transaction');?>" class="btn btn-danger w-100">
                                <i class="ti ti-trash"></i> Xóa lọc
                            </a>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Hành động</th>
                                    <th>Thời gian</th>
                                    <th>Địa chỉ IP</th>
                                    <th>Thiết bị</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                <?php if (empty($logs)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $row): ?>
                                        <tr class="service-row" data-id="<?= $row['id'] ?>" data-table="logs" data-column="sort_order" data-column-remove="id">
                                            <td><?= anti_xss($row['id'] ?? 'Không rõ') ?></td>
                                            <td>
                                                <a class="text-primary" href="<?= BASE_URL('admin/users/'.$row['user_id'].'/edit'); ?>">
                                                    <?= $row['username'] ?> [ID <?=$row['user_id'];?>]
                                                </a>
                                            </td>
                                            <td><?= anti_xss($row['action'] ?? 'Không rõ') ?></td>
                                            <td><span class="badge badge-soft-danger"><?= format_datetime($row['create_date'] ?? 'Không rõ') ?></span></td>
                                            <td><span class="badge badge-soft-info"><?= anti_xss($row['ip'] ?? 'Không rõ') ?></span></td>
                                            <td><?= anti_xss($row['device'] ?? 'Không rõ') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        if ($total > $limit) {
                            echo '<div class="text-center">' . phan_trang_admin(
                                BASE_URL('admin/history/logs?' . http_build_query(array_merge($_GET, ['page' => null]))),
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