<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Nhật ký sử dụng mã giảm giá";
load_admin_data('modules/coupon/coupon-log');
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
                    <a href="javascript:void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
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
                    <h4 class="card-title mb-0">Nhật Ký Sử Dụng Mã <b class="text-danger"><?=$row['code'];?></b> </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã giảm giá</th>
                                    <th>Người dùng</th>
                                    <th>Đơn hàng</th>
                                    <th>Thời gian</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $isd = 0;
                                foreach ($coupon_logs as $log) {
                                    $isd++;
                                ?>
                                <tr>
                                    <td><?= $isd; ?></td>
                                    <td>
                                        <a class="text-danger" href="<?= BASE_URL('admin/users/' . $log['user_id'] . '/edit') ?>">
                                            <?= $log['coupon_code'] ?> [ID <?= $log['id'] ?>]
                                        </a>
                                    </td>
                                    <td>
                                        <a class="text-primary" href="<?= BASE_URL('admin/users/' . $log['user_id'] . '/edit') ?>">
                                            <?= $log['username'] ?> [ID <?= $log['user_id'] ?>]
                                        </a>
                                    </td>
                                    <td><?= $log['trans_id']; ?></td>
                                    <td><?= format_datetime($log['created_at']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-delete-single"
                                                data-id="<?= $log['id'] ?>" data-table="coupons" data-column-remove="id">
                                            <i class="ti ti-trash"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
