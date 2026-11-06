<header class="nav-i643qf">
    <div class="nav-1j9x4sv"></div>
    <div class="nav-1e0moo" id="bgBlur"></div>
    <div class="chakra-container nav-6n7wkq">
        <div class="nav-1wyeig1">
            <div class="nav-12ckupk swiper-container" id="bannerSlider">
                <div class="swiper-wrapper">
                    <?php foreach ($sliders as $row): ?>
                        <div class="swiper-slide">
                            <img onclick="window.location.href='<?= $row['slider_url']; ?>'" src="<?=BASE_URL($row['slider_image'])?>" data-src="<?=BASE_URL($row['slider_image'])?>" alt="<?=$row['slider_name']?>" class="lazyLoad slider-img ws-select-none chakra-image nav-eq2omy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="nav-x9d2bq" data-aos="fade-up">
                <div class="chakra-tabs nav-167bzet">
                    <div role="tablist" aria-orientation="horizontal" class="chakra-tabs__tablist nav-1uw5bnh">
                        <button type="button" onclick ="Tab('tab_napthe')" role="tab" tabindex="0" aria-selected="true" aria-controls="tabs-:r9:--tabpanel-0" class="chakra-tabs__tab nav-s0rjlw tablinks" data-index="0">
                            
                            <p class="chakra-text nav-0">NẠP THẺ </p>
                        </button>
                        <button type="button" onclick="Tab('tab_phanthuong')" role="tab" tabindex="-1" aria-selected="false" aria-controls="tabs-:r9:--tabpanel-1" class="chakra-tabs__tab nav-o1lnjr tablinks" data-index="1">
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" focusable="false" class="chakra-icon nav-118xvt9" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32 448c0 17.7 14.3 32 32 32h160V320H32v128zm256 32h160c17.7 0 32-14.3 32-32V320H288v160zm192-320h-42.1c6.2-12.1 10.1-25.5 10.1-40 0-48.5-39.5-88-88-88-41.6 0-68.5 21.3-103 68.3-34.5-47-61.4-68.3-103-68.3-48.5 0-88 39.5-88 88 0 14.5 3.8 27.9 10.1 40H32c-17.7 0-32 14.3-32 32v80c0 8.8 7.2 16 16 16h480c8.8 0 16-7.2 16-16v-80c0-17.7-14.3-32-32-32zm-326.1 0c-22.1 0-40-17.9-40-40s17.9-40 40-40c19.9 0 34.6 3.3 86.1 80h-86.1zm206.1 0h-86.1c51.4-76.5 65.7-80 86.1-80 22.1 0 40 17.9 40 40s-17.9 40-40 40z"></path>
                            </svg>
                                TOP T.<?=date('n');?>
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-1gwqola" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <line x1="17" y1="10" x2="3" y2="10"></line>
                                <line x1="21" y1="6" x2="3" y2="6"></line>
                                <line x1="21" y1="14" x2="3" y2="14"></line>
                                <line x1="17" y1="18" x2="3" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="chakra-tabs__tab-panels nav-1sl8dhm">
                        <div tabindex="0" role="tabpanel" aria-labelledby="tabs-:r9:--tab-0" id="tab_napthe" class="chakra-tabs__tab-panel nav-wk6o2d tabcontent">
                            <ul class="nav-cq8o23">
                            <div role="group" class="chakra-form-control nav-1mu7gb0">
                                <div class="swiper operator-slider">
                                    <div class="swiper-wrapper">

                                        <div class="swiper-slide">
                                            <button
                                                class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                                type="button" value="VIETTEL" onclick="selectOperatorIndex(this)">
                                                <img src="/assets/client/images/thecao/VIETTEL.png"
                                                    style="width:4rem;filter:grayscale(100%)">
                                            </button>
                                        </div>

                                        <div class="swiper-slide">
                                            <button
                                                class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                                type="button" value="VINAPHONE" onclick="selectOperatorIndex(this)">
                                                <img src="/assets/client/images/thecao/VINAPHONE.png"
                                                    style="width:4rem;filter:grayscale(100%)">
                                            </button>
                                        </div>

                                        <div class="swiper-slide">
                                            <button
                                                class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                                type="button" value="MOBIFONE" onclick="selectOperatorIndex(this)">
                                                <img src="/assets/client/images/thecao/MOBIFONE.png"
                                                    style="width:4rem;filter:grayscale(100%)">
                                            </button>
                                        </div>

                                        <div class="swiper-slide">
                                            <button
                                                class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                                type="button" value="GARENA" onclick="selectOperatorIndex(this)">
                                                <img src="/assets/client/images/thecao/GARENA.png"
                                                    style="width:4rem;filter:grayscale(100%)">
                                            </button>
                                        </div>

                                        <div class="swiper-slide">
                                            <button
                                                class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                                type="button" value="ZING" onclick="selectOperatorIndex(this)">
                                                <img src="/assets/client/images/thecao/ZING.png"
                                                    style="width:4rem;filter:grayscale(100%)">
                                            </button>
                                        </div>

                                        <div class="swiper-slide">
                                            <button
                                                class="ws-h-12 ws-w-full ws-flex ws-justify-center ws-items-center ws-border-gray-300 ws-border-dashed ws-px-3 ws-rounded ws-font-bold ws-border"
                                                type="button" value="VCOIN" onclick="selectOperatorIndex(this)">
                                                <img src="/assets/client/images/thecao/VCOIN.png"
                                                    style="width:4rem;filter:grayscale(100%)">
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div role="group" class="chakra-form-control nav-1mu7gb0">
                                <div class="chakra-select__wrapper nav-42b2qy">
                                    <select id="menhgia_index" class="chakra-select nav-zkmoxo">
                                        <option value="">-- Chọn đúng mệnh giá. Sai mất thẻ nhé --</option>
                                        <option value="10000">10,000đ</option>
                                        <option value="20000">20,000đ</option>
                                        <option value="30000">30,000đ</option>
                                        <option value="50000">50,000đ</option>
                                        <option value="100000">100,000đ</option>
                                        <option value="200000">200,000đ</option>
                                        <option value="300000">300,000đ</option>
                                        <option value="500000">500,000đ</option>
                                        <option value="1000000">1,000,000đ</option>
                                    </select>
                                    <div class="chakra-select__icon-wrapper nav-18ixemt">
                                        <svg viewBox="0 0 24 24" role="presentation" class="chakra-select__icon" focusable="false" aria-hidden="true" style="width: 1em; height: 1em; color: currentcolor;">
                                            <path fill="currentColor" d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div role="group" class="chakra-form-control nav-1mu7gb0">
                                <input type="number" id="seri_index" placeholder="Seri được in trên thẻ" class="chakra-input nav-11p79ra" />
                            </div>
                            <div role="group" class="chakra-form-control nav-1mu7gb0">
                                <input type="number" id="pin_index" placeholder="Mã số thẻ" class="chakra-input nav-11p79ra" />
                            </div>

                            </ul>
                            <div class="chakra-stack nav-nek2wy">
                                <hr aria-orientation="horizontal" class="chakra-divider nav-1pdcswv" />
                                <a style="width: 100%;"><button type="button" id="napthecao" class="chakra-button changeColor nav-qf38fy">NẠP THẺ NGAY</button></a>
                            </div>
                        </div>
                        <div tabindex="0" role="tabpanel" aria-labelledby="tabs-:r9:--tab-1" hidden="true" id="tab_phanthuong" class="chakra-tabs__tab-panel nav-wk6o2d tabcontent">
                            <div class="nav-cq8o23">
                                <?php $i=1; foreach ($top_deposit_users as $row):?>
                                    <li class="nav-gg4vpm">
                                        <div class="nav-1k8pvkd">
                                            <div class="nav-1vx1dtt">
                                                <?php
                                                    $svgs = [
                                                        1 => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-fmr52q" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                                              <p class="chakra-text nav-1w5ptqk"></p>',
                                                        2 => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-l22ou" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon></svg>
                                                             <p class="chakra-text nav-1w5ptqk">2</p>',
                                                        3 => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-112k95u" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon></svg>
                                                             <p class="chakra-text nav-1w5ptqk">3</p>',
                                                        4 => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-cy4d2h" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon></svg>
                                                             <p class="chakra-text nav-1w5ptqk">4</p>',
                                                        5 => '<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-cy4d2h" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon></svg>
                                                             <p class="chakra-text nav-1w5ptqk">5</p>'
                                                    ];
                                                    
                                                    if (isset($svgs[$i])) {
                                                        // Nếu có, xuất đoạn mã SVG tương ứng
                                                        echo $svgs[$i++];
                                                    } else {
                                                        // Nếu không, xuất một đoạn mã SVG mặc định hoặc thông báo lỗi
                                                        echo '<svg><!-- Mã SVG mặc định hoặc thông báo lỗi --></svg>';
                                                    }
                                                ?>
                                            </div>
                                            <p class="chakra-text nav-8lnsnw">ID <?=$row['user_id'];?></p>
                                        </div>
                                        <div class="nav-19bp7no"><?=format_number($row['total_net']);?>₫</div>
                                    </li>
                                <?php endforeach?>
                                <div class="chakra-stack nav-nek2wy">
                                    <hr aria-orientation="horizontal" class="chakra-divider nav-1pdcswv" />
                                    <a style="width: 100%;">
                                        <button type="button" onclick="ModalManager.open('phan_thuong')" class="chakra-button changeColor nav-qf38fy">Phần Thưởng</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>