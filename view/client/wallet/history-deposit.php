<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Lịch Sử Nạp Tiền";
    check_not_login_redirect();
    load_client_data('wallet/history-deposit');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Sidebar.php');
?>

<div class="ws-col-span-12 md:ws-col-span-9 ws-min-h-[70vh]">
    <div>
        <div class="ws-relative">
            <div class="ws-bg-white ws-relative ws-my-1 ws-py-3 md:ws-py-4 ws-px-4 ws-grid ws-grid-cols-12 ws-gap-x-4 ws-gap-y-2 ws-rounded-none md:ws-rounded">
                <div class="ws-absolute ws-border-skz"></div>
                <div class="ws-col-span-12 md:ws-col-span-12">
                    <label class="ws-block ws-mb-4 ws-text-zinc-700">
                        <i class="fa-solid fa-money-bill-transfer ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>            
                    </label>
                    <div class="form-m1">
                        <form action="<?=BASE_URL('wallet/history/deposit');?>" name="formSearch" method="GET">
                            <div class="row row5 rowmb3">
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="form-theme_item--input">
                                            <select name="paygate" class="el-input__inner">
                                                <option value="" selected="selected">Cổng nạp</option>
                                                <?php foreach ($deposit_banks as $bank): ?>
                                                    <option value="<?=$bank['bankName'] ?>"><?=$bank['textNameBank'] ?></option>
                                                <?php endforeach; ?>
                                                <option value="VIETTEL" <?= @$_GET['paygate'] === 'VIETTEL' ? 'selected' : '' ?>>Thẻ cào Viettel</option>
                                                <option value="VINAPHONE" <?= @$_GET['paygate'] === 'VINAPHONE' ? 'selected' : '' ?>>Thẻ cào Vinaphone</option>
                                                <option value="MOBIFONE" <?= @$_GET['paygate'] === 'MOBIFONE' ? 'selected' : '' ?>>Thẻ cào Mobifone</option>
                                                <option value="GARENA" <?= @$_GET['paygate'] === 'GARENA' ? 'selected' : '' ?>>Thẻ cào Garena</option>
                                                <option value="ZING" <?= @$_GET['paygate'] === 'ZING' ? 'selected' : '' ?>>Thẻ cào Zing</option>
                                                <option value="VCOIN" <?= @$_GET['paygate'] === 'VCOIN' ? 'selected' : '' ?>>Thẻ cào Vcoin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="position-relative">
                                            <input class="el-input__inner" value="<?= anti_xss(anti_xss($_GET['from_date'] ?? '')) ?>" name="from_date" type="date">
                                            <div class="icon-ios"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="position-relative">
                                            <input class="el-input__inner" value="<?= anti_xss(anti_xss($_GET['to_date'] ?? '')) ?>" name="to_date" type="date">
                                            <div class="icon-ios"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md flex-lg-grow-0 d-flex align-items-center px-0">
                                    <button class="btn btn-primary btn-small text-nowrap m-1 my-md-0" type="submit" name="submit" value="filter">
                                        <span class="fal fa-search me-1"></span> Lọc 
                                    </button>
                                    <button class="btn h-100 btn-success btn-small text-nowrap m-1 my-md-0" type="button" onclick="location.reload()">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </button>
                                    <a href="<?=BASE_URL('wallet/history/deposit');?>" class="btn btn-danger btn-small text-nowrap m-1 my-md-0">
                                        <i class="fa fa-trash-alt me-1"></i> Bỏ lọc 
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-module text-nowrap">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Số tiền</th>
                                    <th>Cổng thanh toán</th>
                                    <th>Thông tin thanh toán</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($deposit_invoices) > 0): ?>
                                    <?php foreach ($deposit_invoices as $invoice): ?>
                                        <tr>
                                            <td><?= $invoice['order_code']; ?></td>
                                            <td><?= $invoice['net_amount_formatted']; ?>đ</td>
                                            <td><?= $invoice['paygate_display']; ?></td>
                                            <td><?= $invoice['payment_info']; ?></td>
                                            <td><?= $invoice['created_at_formatted']; ?></td>
                                            <td>
                                                <label class="badge badge-module <?= $invoice['status_class']; ?>">
                                                    <?= $invoice['status_text']; ?>
                                                </label>
                                            </td>
                                            <td><?= $invoice['client_note'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                    <?php
                        if ($total > $limit) {
                            echo '<div class="text-center">' . phan_trang_client(
                                BASE_URL('wallet/history?' . http_build_query(array_merge($_GET, ['page' => null]))),
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
</div>
</div>
</div>
</div>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>
