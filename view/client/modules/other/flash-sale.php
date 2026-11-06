<?php if ($show_flash_sale): ?>
<div id="flashSaleTabs" class="chakra-container nav-10rjlrk-flash-sale">
    <div style="background: url('/assets/client/images/flashsale-back.png') no-repeat; background-size: cover;" class="ws-rounded ws-bg-no-repeat ws-pt-1.5">
        <div class="ws-px-3 ws-border-b ws-border-dashed ws-border-red-300 ws-pb-1">
            <label class="ws-font-extrabold ws-text-white ws-text-2xl">
                <?=$title_flash_sale?>
            </label>
        </div>
        <div class="ws-grid ws-grid-cols-12 ws-gap-0">
            <?php foreach ($flash_sales_list as $flash) : ?>
                <div class="ws-col-span-4 lg:ws-col-span-3 ws-py-2 ws-px-2 ws-text-center">
                    <div class="ws-rounded ws-px-5 ws-py-1 ws-cursor-pointer <?= $flash['tab_bg_class'] ?>" data-target="#tab-<?= $flash['id'] ?>"><label class="ws-text-lg lg:ws-text-2xl ws-font-bold ws-block <?= $flash['tab_time_class'] ?>"><?= $flash['start_label'] ?> - <?= $flash['end_label'] ?></label>
                        <p class="ws-text-xs ws-font-medium <?= $flash['tab_msg_class'] ?>"><?= $flash['flash_status']['msg'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
    <div class="ws-pt-2 ws-bg-white ws-pb-3 ws-px-1 md:ws-px-2 ws-border-r ws-border-l ws-rounded-b-lg">
        <?php foreach ($flash_sales_tabs as $flash) : ?>
            <div id="tab-<?= $flash['id'] ?>" class="tab-content unique-tab-content ws-w-full ws-block <?= $flash['content_active'] ? 'active' : '' ?>">
                <div>
                    <div class="swiper myswiper2">
                        <div class="swiper-wrapper">
                            <?php foreach ($flash_sale_products[$flash['id']] ?? [] as $product) : ?>
                                <div class="swiper-slide ws-cursor-pointer">
                                    <div class="ws-cursor-pointer ws-p-1 ws-rounded ws-relative" style="background-position:center; background-image:url('/assets/client/images/flashsale-back.png');">
                                        <?php if ($product['flashStatus']['status'] == 0) : ?>
                                            <div class="ws-absolute ws-top-0 ws-left-0 ws-right-0 ws-bottom-0 ws-flex ws-items-center ws-justify-center ws-cursor-default end-flash-sale">
                                                <label class="ws-inline-block ws-font-medium ws-text-sm ws-border-2 ws-border-dashed ws-border-red-500 ws-text-red-500 ws-px-2" style="transform: rotate(45deg);">
                                                    Flashsale đã kết thúc
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                        <div class="ws-bg-white ws-rounded hover:ws-shadow-sm">
                                            <a href="<?= $product['link'] ?>" class="ws-bg-white">
                                                <div class="ws-relative ws-w-full ws-inline-block ws-mb-0">
                                                    <div class="ws-overflow-hidden ws-h-28 md:ws-h-[8rem] ws-w-full ws-bg-zinc-50">
                                                        <img src="<?= $product['img'] ?>" class="lazyLoad ws-select-none ws-rounded-lg ws-w-full ws-h-28 md:ws-h-[8rem] ws-object-fill ws-object-center !ws-rounded-none">
                                                    </div>
                                                </div>
                                                <div class="ws-py-1 ws-px-2 ws-text-red-500 ws-text-base">
                                                    <?php if (!empty($product['price'])) : ?>
                                                        <div class="ws-font-semibold ws-text-lg">
                                                            <?= $product['price'] ?><small>đ</small>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="ws-font-semibold ws-text-lg">
                                                            <small><?= $product['title'] ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ws-text-xs ws-text-zinc-800 ws-border-t ws-border-dashed ws-py-1 ws-px-2">
                                                    <?= $product['extraInfo'] ?>
                                                </div>
                                            </a>
                                        </div>
                                        <div onclick="window.location.href='<?= $product['link'] ?>'"
                                                style="cursor:pointer"
                                                class="ws-text-center ws-pt-1.5 ws-pb-0.5 ws-font-bold ws-text-sm ws-text-white">
                                            XEM NGAY
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
