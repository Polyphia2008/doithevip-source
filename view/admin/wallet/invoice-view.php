<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Chi tiết hóa đơn";
load_admin_data('wallet/invoice-view');
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
                <li class="breadcrumb-item"><a href="javascript: void(0);"><?=$_SERVER['HTTP_HOST'];?></a></li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card custom-card">
                <div class="card-header border-bottom border-dashed d-flex align-items-center">
                    <h4 class="card-title mb-0">Danh sách đơn hàng</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        
                        <div class="row g-3">
                            <!-- Left -->
                            <div class="col-md-6">
                                <label class="form-label">Cổng Thanh Toán</label>
                                <select class="form-select" name="paygate">
                                    <option value="balance" <?= ($row['paygate'] === 'balance') ? 'selected' : ''; ?>>
                                        Số dư
                                    </option>
                                    <?php foreach ($deposit_banks as $bank): ?>
                                        <option value="<?= $bank['bankName']; ?>" <?= ($row['paygate'] === $bank['bankName']) ? 'selected' : ''; ?>>
                                            <?= $bank['textNameBank']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mã Đơn</label>
                                <input type="text" class="form-control" value="<?=$row['order_code'];?>" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mô-đun</label>
                                <input type="text" class="form-control" value="<?=$row['module'] ?? '---' ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Chi Tiết Dịch Vụ</label>
                                <textarea type="text" class="form-control" disabled><?=$row['description'];?></textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Số Tiền Thanh Toán</label>
                                <input type="text" class="form-control" name="pay_amount" value="<?=$row['pay_amount']; ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Loại Giao Dịch</label>
                                <input type="text" class="form-control" value="<?=type_action($row['order_type'])?>" disabled>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Trạng Thái Hóa Đơn</label>
                                <select class="form-select" name="status">
                                    <option <?=$row['status']== 'unpaid'?'selected':'';?> value="unpaid">Chưa thanh toán</option>
                                    <option <?=$row['status']== 'paid'?'selected':'';?> value="paid">Hoàn thành</option>
                                    <option <?=$row['status']== 'cancel'?'selected':'';?> value="cancel">Đã huỷ</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Trạng Thái Đơn Hàng</label>
                                <select class="form-select" name="status_service">
                                    <option <?=$row['status_service']== '100'?'selected':'';?> value="100">Chưa thanh toán</option>
                                    <option <?=$row['status_service']== '99'?'selected':'';?> value="99">Chờ xử lý</option>
                                    <option <?=$row['status_service']== '1'?'selected':'';?> value="1">Hoạt động</option>
                                    <option <?=$row['status_service']== '3'?'selected':'';?> value="3">Đã huỷ</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Ghi Chú Đơn Hàng</label>
                                <textarea type="text" class="form-control" disabled><?=$row['admin_note'];?></textarea>
                            </div>

                            <div class="col-md-3 mt-4">
                                <label for="validationServer04" class="form-label"> Thời Gian Tạo Hóa Đơn </label>
                                <ul>
                                    <li> Thời Gian Tạo Hóa Đơn: <?=format_datetime($row['created_at']);?> </li>
                                    <li> Thời Gian Hết Hạn: <?= format_datetime(date("Y-m-d H:i:s", strtotime($row['created_at'] . " +15 minutes"))); ?> </li>
                                </ul>
                                <small> 15 Phút Hóa Đơn Sẽ Tự Bị Hủy. </small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="<?=BASE_URL('admin/wallet/invoices');?>" class="btn btn-danger">
                                <i class="ti ti-arrow-left"></i> Quay Lại
                            </a>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu Lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
