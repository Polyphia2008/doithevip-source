<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Chi Tiết Đơn Hàng";
    check_not_login_redirect();
    load_client_data('order/view');
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
                        <i class="fa-solid fa-eye ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>
                    </label>
                    <div class="form-m1">
                        <?php foreach ($order_items as $order_item): ?>
                                <?php if ($order_item['service_type'] === 'account'): ?>
                                    <?php include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/service/account/view-order.php'; ?>
                                <?php elseif ($order_item['service_type'] === 'service'): ?>
                                    <?php include realpath($_SERVER["DOCUMENT_ROOT"]) . '/view/client/service/service/view-order.php'; ?>
                                <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
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
