<div class="chakra-container nav-10rjlrk">
    <!--Mua Nick-->
    <?php foreach ($category_services as $category) {?>
        <div class="nav-ozw706" data-aos="fade-up"><img alt="<?=$category['name'];?>" class="chakra-image nav-0" src="<?=BASE_URL($category['image']);?>" /></div>
        <div class="nav-lddqu5">
            <?php foreach ($service_groups_by_category[$category['id']] ?? [] as $group) { ?>
            <a href="<?=BASE_URL('service/service/view/'.$group['slug']);?>">
                <section class="nav-1dycfta" data-aos="fade-up">
                    <div class="nav-m6f5b0">
                        <img src="<?=BASE_URL($group['image']);?>" class="chakra-image nav-uwwqev">
                    </div>
                    <div class="nav-1ookdhi">
                        <h1 class="chakra-heading nav-1v0pkbe"><?=$group['name'];?></h1>
                        <div class="nav-ldihrc">
                            <div class="nav-1ahrp53">
                                <span class="chakra-text nav-ybw199">Đã bán:</span>
                                <span class="chakra-text nav-11pi7kn"><?=format_number($group['count_sell'] ?? 0)?></span>
                            </div>
                        </div>
                        <hr aria-orientation="horizontal" class="chakra-divider nav-4l7qk7" />
                        <div>
                            <?php if (isset($group['nhan']) && !empty($group['nhan'])) { ?>
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
