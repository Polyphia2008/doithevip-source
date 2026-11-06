<div class="chakra-container nav-10rjlrk">
    <!--Mua Nick-->
    <?php foreach ($category_accounts as $category) {?>
        <div class="nav-ozw706" data-aos="fade-up"><img alt="<?=$category['name'];?>" class="chakra-image nav-0" src="<?=BASE_URL($category['image']);?>" /></div>
        <div class="nav-lddqu5">
            <?php foreach ($account_groups_by_category[$category['id']] ?? [] as $group) { ?>
            <a href="<?=BASE_URL('service/account/view/'.$group['slug']);?>">
                <section class="nav-1dycfta" data-aos="fade-up">
                    <div class="nav-m6f5b0">
                        <img src="<?=BASE_URL($group['image']);?>" class="chakra-image nav-uwwqev">
                    </div>
                    <div class="nav-1ookdhi">
                        <h1 class="chakra-heading nav-1v0pkbe"><?=$group['name'];?></h1>
                        <div class="nav-ldihrc">
                            <div class="nav-1ahrp53">
                                <span class="chakra-text nav-ybw199">Hiện có:</span>
                                <span class="chakra-text nav-11pi7kn"><?=format_number($group['count_account'] ?? 0)?></span>
                            </div>
                        </div>
                        <hr aria-orientation="horizontal" class="chakra-divider nav-4l7qk7" />
                        <div>
                            <?php if ($group['type'] == 'random' && isset($group['price'])) { ?>
                            <div class="nav-gg4vpm">
                                <div class="chakra-stack nav-isgblm">
                                    <p class="chakra-text nav-1g9zthi">Hiện tại</p>
                                    <b class="chakra-text nav-8byhq"><?=format_number($group['price_with_fee'] ?? $group['price']);?>₫</b>
                                </div>
                                <div class="chakra-stack nav-isgblm">
                                    <p class="chakra-text nav-1g9zthi">Gốc</p>
                                    <p class="chakra-text nav-1xhgo20"><?=format_number($group['price_original'] ?? $group['price_with_fee'] ?? $group['price']);?>₫</p>
                                </div>
                            </div>
                            <?php } elseif($group['type'] == 'tuchon' && isset($group['nhan']) && !empty($group['nhan'])) { ?>
                            <center>
                                <div class="chakra-stack nav-isgblm">
                                    <b class="chakra-text nav-1lads1q"><?=$group['nhan'];?></b>
                                </div>
                            </center>
                            <?php } else { ?>
                            <center>
                                <div class="chakra-stack nav-isgblm">
                                    <b class="chakra-text nav-1lads1q"><?=$group['name'];?></b>
                                </div>
                            </center>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </a>
            <?php } ?>

        </div>
    <?php }?>
</div>
