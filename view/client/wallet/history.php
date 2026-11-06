<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Lịch Sử Giao Dịch";
    check_not_login_redirect();
    load_client_data('wallet/history');
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
                        <i class="fa-solid fa-clock ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>            
                    </label>
                    <div class="form-m1">
                        <form action="<?=BASE_URL('wallet/history');?>" name="formSearch" method="GET">
                            <div class="row row5 rowmb3">
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="form-theme_item--input">
                                            <input class="el-input__inner" value="<?= anti_xss($_GET['order_code'] ?? '') ?>" name="order_code" placeholder="Mã đơn">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="form-theme_item--input">
                                            <input class="el-input__inner" value="<?= anti_xss($_GET['message'] ?? '') ?>" name="message" placeholder="Nội dung">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="form-theme_item--input">
                                            <select name="order_type" class="el-input__inner">
                                                <option value="" selected="selected">Chọn loại</option>
                                                <option value="Withdraw" <?= @$_GET['order_type'] === 'Withdraw' ? 'selected' : '' ?>>Tiền ra</option>
                                                <option value="Deposit" <?= @$_GET['order_type'] === 'Deposit' ? 'selected' : '' ?>>Tiền vào</option>
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
                                    <a href="<?=BASE_URL('wallet/history');?>" class="btn btn-danger btn-small text-nowrap m-1 my-md-0">
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
                                    <th>Trước GD</th>
                                    <th>Số tiền</th>
                                    <th>Sau GD</th>
                                    <th>Tiền tệ</th>
                                    <th>Ngày tạo</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($result) > 0): ?>
                                    <?php foreach ($result as $row): ?>
                                        <tr>
                                            <td><?= htmlentities($row['trans_id']) ?></td>
                                            <td><?= number_format($row['money_before']) ?>đ</td>
                                            <td>
                                                <?php if ($row['type'] === '-'): ?>
                                                    <span class="text-danger"><b>-<?= number_format($row['money_change']) ?>đ</b></span>
                                                <?php else: ?>
                                                    <span class="text-success"><b><?= number_format($row['money_change']) ?>đ</b></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= number_format($row['money_after']) ?>đ</td>
                                            <td>VND</td>
                                            <td><?= $row['time'] ?></td>
                                            <td><?= htmlentities($row['content']) ?></td>
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
