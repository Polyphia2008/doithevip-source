<?php if ($show_noti_sell): ?>
<div class="chakra-container nav-10rjlrk-noti-buy">
    <div class="border-radius-5px tw-mb-4 tw-text-red-500 tw-font-bold tw-px-2 tw-bg-white md:tw-rounded tw-py-2 tw-relative ws-px-2 ws-mx-auto ws-my-4">
        <span class="border-radius-5px tw-absolute tw-flex tw-items-center tw-justify-center tw-text-xl tw-h-10 tw-w-10 tw-bg-white md:tw-rounded" style="top: 0px; left: 0px; z-index: 5; color:#dc2626">
            <i class="fa-solid fa-bell"></i>
        </span>
        <div class="tw-flex tw-w-full tw-items-center tw-notification-home">
            <marquee scrollamount="20">
                <?php foreach ($noti_orders as $order) : ?>
                <a style="text-align:center;">
                    <span class="">
                    <span style="background-color: #FBC02D;color: black; padding: 5px; border-radius: 4px">ID <?= $order['user_id'] ?></span>
                        đã mua <?= $order['service_type_text'] ?> <span style="color: #279EFF">#<?= $order['service_id'] ?></span> giá <span style="color: #4D2DB7"><?= $order['amount_formatted'] ?><sup class="text-muted">đ</sup></span> -
                        <span class="text-muted"> cách đây <span style="color: red"><?= $order['time_ago'] ?></span></span>
                    </span> <b style="">|</b>
                </a>
                <?php endforeach; ?>
            </marquee>
        </div>
    </div>
</div>
<?php endif; ?>
