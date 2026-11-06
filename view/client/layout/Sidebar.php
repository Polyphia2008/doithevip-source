<link rel="stylesheet" href="/assets/client/css/profile.css">
<div class="chakra-container nav-15rfyaw" style="">
    <div class="nav-x52q81">
        <div class="nav-1wyxfzy">
            <div class="chakra-stack nav-1s05a5c">
                <div class="nav-zvc0l9">
                    <img alt="Pino Cloud" class="chakra-image nav-bp2dp6" src="<?= base_url('assets/client/images/avt.png'); ?>" />
                </div>
                <div class="chakra-stack nav-kg9n4">
                    <button type="button" class="chakra-button nav-1p1uy1u">
                        ID: <p><?=format_number($data_user['id']);?></p>
                    </button>
                    <div class="chakra-stack nav-2neqna">
                        <div class="chakra-stack nav-1ey9w6j">
                            <p class="chakra-text nav-0"><b
                                    class="chakra-text nav-1bvqdo1">Tên:</b><?= $getUser['username']; ?></p>
                            <p class="chakra-text nav-0"><b class="chakra-text nav-1bvqdo1">Số dư:</b><b
                                    class="chakra-text nav-14egak9"><?=format_number($data_user['money']);?>₫</b>
                            </p>
                        </div>
                        <button type="button" id="menu_sidebar_nav_tapvietcode"
                            class="chakra-button mobile-vudz nav-7gjaca" aria-label="Đóng Menu">
                            <svg id="menu_sidebar_icon" stroke="currentColor" fill="none" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
                                focusable="false" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <polyline points="18 15 12 9 6 15"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <hr aria-orientation="horizontal" class="chakra-divider nav-ecyy33" id="menu_hr" aria-modal="true">
            <div class="chakra-accordion nav-rltemf" id="menu_sidebar" aria-modal="true">
                <div class="chakra-accordion__item nav-1t7rcca">
                    <button type="button" id="accordion-button-:r1c:" aria-expanded="true"
                        aria-controls="accordion-panel-:r1c:" class="chakra-accordion__button nav-uttm9k"
                        data-index="0">
                        <b class="nav-1bwbiic">Tài khoản</b>
                        <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon chakra-accordion__icon nav-j2ph2z"
                            aria-hidden="true">
                            <path fill="currentColor" d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"></path>
                        </svg>
                    </button>
                    <div class="chakra-collapse" style="overflow: hidden; display: block; opacity: 1; height: auto;">
                        <div role="region" id="accordion-panel-:r1c:" aria-labelledby="accordion-button-:r1c:"
                            class="chakra-accordion__panel nav-1rr42hn">
                            <ul role="list" class="nav-155za0w">
                                <li class="nav-0">
                                    <a href="<?= BASE_URL('account/profile'); ?>">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Thông tin tài khoản</p>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-0">
                                    <a href="<?= BASE_URL('account/profile/edit/'.$data_user['id'].''); ?>">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Sửa thông tin</p>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-0">
                                    <a href="<?= BASE_URL('account/change-password'); ?>">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Đổi mật khẩu</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="chakra-accordion__item nav-1t7rcca">
                    <button type="button" id="accordion-button-:r1e:" aria-expanded="true"
                        aria-controls="accordion-panel-:r1e:" class="chakra-accordion__button nav-uttm9k"
                        data-index="1">
                        <b class="nav-1bwbiic">Nạp tiền (Tự động)</b>
                        <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon chakra-accordion__icon nav-j2ph2z"
                            aria-hidden="true">
                            <path fill="currentColor" d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"></path>
                        </svg>
                    </button>
                    <div class="chakra-collapse" style="overflow: hidden; display: block; opacity: 1; height: auto;">
                        <div role="region" id="accordion-panel-:r1e:" aria-labelledby="accordion-button-:r1e:"
                            class="chakra-accordion__panel nav-1rr42hn">
                            <ul role="list" class="nav-155za0w">
                                <li class="nav-0">
                                    <a href="javascript:void(0);" onclick="ModalManager.open('nap_the_cao')">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Nạp thẻ cào</p>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-0">
                                    <a href="javascript:void(0);" onclick="ModalManager.open('nap_ngan_hang')">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Nạp ngân hàng</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="chakra-accordion__item nav-1t7rcca">
                    <button type="button" id="accordion-button-:r1k:" aria-expanded="true"
                        aria-controls="accordion-panel-:r1k:" class="chakra-accordion__button nav-uttm9k"
                        data-index="4">
                        <b class="nav-1bwbiic">Lịch sử</b>
                        <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon chakra-accordion__icon nav-j2ph2z"
                            aria-hidden="true">
                            <path fill="currentColor" d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"></path>
                        </svg>
                    </button>
                    <div class="chakra-collapse" style="overflow: hidden; display: block; opacity: 1; height: auto;">
                        <div role="region" id="accordion-panel-:r1k:" aria-labelledby="accordion-button-:r1k:"
                            class="chakra-accordion__panel nav-1rr42hn">
                            <ul role="list" class="nav-155za0w">
                                <li class="nav-0">
                                    <a href="<?= BASE_URL('wallet/history'); ?>">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Lịch sử giao dịch</p>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-0">
                                    <a href="<?= BASE_URL('wallet/history/deposit'); ?>">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Lịch sử nạp tiền</p>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-0">
                                    <a href="<?= BASE_URL('order/list'); ?>">
                                        <div class="chakra-stack nav-1cmtnvn">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                viewBox="0 0 320 512" focusable="false" class="chakra-icon nav-1mjo316"
                                                role="presentation" height="1em" width="1em"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
                                                </path>
                                            </svg>
                                            <p class="chakra-text nav-0">Lịch sử mua hàng</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>