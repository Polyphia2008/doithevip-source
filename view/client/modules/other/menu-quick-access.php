<?php if ($show_menu_quick_access): ?>
<div class="chakra-container nav-10rjlrk-proudct-mini">
    <div class="border-radius-5px tw-mb-4 tw-text-red-500 tw-font-bold tw-px-2 tw-bg-white md:tw-rounded tw-py-2 tw-relative ws-px-2 ws-mx-auto ws-my-4">
        <div class="ws-grid ws-grid-cols-12 ws-gap-4">
            <?php foreach ($menu_quick_access as $item) : ?>
            <div class="ws-py-2 ws-col-span-4 md:ws-col-span-3 lg:ws-col-span-2 hover:ws-bg-white hover:ws-text-red-500 ws-transition ws-duration-200 ws-rounded-lg">
                <a href="<?=$item['url'];?>" class="ws-flex ws-items-center ws-flex-col ws-justify-center ws-text-center">
                    <img style="border-radius:15px;" class="ws-inline-block ws-h-16 lg:ws-h-18" alt="<?=$item['name'];?>" src="<?=base_url($item['image']);?>">
                    <span class="ws-mt-2 ws-font-semibold ws-text-sm lg:ws-text-base"><?=$item['name'];?></span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
