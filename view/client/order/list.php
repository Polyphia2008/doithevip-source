<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Đơn Hàng";
    check_not_login_redirect();
    load_client_data('order/list');
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
                        <i class="fa-solid fa-receipt ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>            
                    </label>
                    <div class="form-m1">
                        <form action="<?=BASE_URL('order/list');?>" name="formSearch" method="GET">
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
                                            <select name="service_type" class="el-input__inner">
                                                <option value="" selected="selected">Chọn loại</option>
                                                <option value="account" <?= @$_GET['service_type'] === 'account' ? 'selected' : '' ?>>Tài khoản</option>
                                                <option value="service" <?= @$_GET['service_type'] === 'service' ? 'selected' : '' ?>>Dịch vụ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="position-relative">
                                            <input class="el-input__inner" value="<?= anti_xss($_GET['from_date'] ?? '') ?>" name="from_date" type="date">
                                            <div class="icon-ios"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-3 col-6">
                                    <div class="form-theme_item">
                                        <div class="position-relative">
                                            <input class="el-input__inner" value="<?= anti_xss($_GET['to_date'] ?? '') ?>" name="to_date" type="date">
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
                                    <a href="<?=BASE_URL('order/list');?>" class="btn btn-danger btn-small text-nowrap m-1 my-md-0">
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
                                    <th>Dịch vụ</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Số tiền</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($result)): ?>
                                    <?php foreach ($grouped_display as $order_code => $items): ?>
                                        <?php $rowspan = count($items); ?>
                                        <?php foreach ($items as $index => $item): ?>
                                            <?php $row = $item['row']; ?>
                                            <tr>
                                                <?php if ($index === 0): ?>
                                                    <td class="text-primary" rowspan="<?= $rowspan ?>">
                                                        <b><a href="<?= BASE_URL('order/view/'.$order_code) ?>"><?= anti_xss($order_code) ?></a></b>
                                                    </td>
                                                <?php endif; ?>
                                                <td><?= $item['serviceType2']['text'] ?></td>
                                                <td><?= anti_xss($item['productName']) ?></td>
                                                <td><span class="<?= $item['statusInfo']['class'] ?>"><?= $item['statusInfo']['text'] ?></span></td>
                                                <td><?= format_number($row['amount'] ?? 0) ?>đ</td>
                                                <td><?= anti_xss($row['created_at'] ?? $row['time']) ?></td>
                                                <?php if ($index === 0): ?>
                                                    <td class="text-center dong-button" rowspan="<?= $rowspan ?>">
                                                        <a href="<?=BASE_URL('order/view/'.$row['order_code']);?>">
                                                            <button type="button" class="btn btn-sm_table btn-outline-primary">
                                                                Xem <i class="fal fa-external-link-alt ms-1"></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                    <?php
                        if ($total > $limit) {
                            echo '<div class="text-center">' . phan_trang_client(
                                BASE_URL('order/list?' . http_build_query(array_merge($_GET, ['page' => null]))),
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
