<body class="chakra-ui-light">
    <div id="root">
        <nav class="nav-hzu7xy">

            <div class="nav-14c0wyt">
                <div class="nav-1rm9hv">
                    <div class="nav-1k9efnl">
                        <a class="chakra-link nav-spn4bz" href="/"><img alt="logo"
                                src="<?= BASE_URL($site['logo_dark'] ?? ''); ?>" class="chakra-image nav-16kb41" /></a>
                    </div>

                    <div class="nav-16n9qts">
                        <ul class="chakra-stack nav-8vayzf">
                            <?php
                            foreach (($menus ?? []) as $item) {

                                // Bỏ qua menu con
                                if ($item['parent_id'] != 0)
                                    continue;

                                // Tìm menu con
                                $children = [];
                                foreach (($menus ?? []) as $m) {
                                    if ($m['parent_id'] == $item['id']) {
                                        $children[] = $m;
                                    }
                                }
                                ?>

                                <li class="nav-0">

                                    <a class="chakra-link nav-1bi585w" href="<?= $item['url'] ?>">
                                        <p class="chakra-text nav-0">
                                            <?= $item['name'] ?>
                                        </p>

                                        <?php if (count($children)): ?>
                                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                                stroke-linecap="round" stroke-linejoin="round" class="chakra-icon nav-15t4wcl"
                                                height="1em" width="1em">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        <?php endif; ?>
                                    </a>

                                    <?php if (count($children)): ?>
                                        <ul class="chakra-stack nav-sub-menu">
                                            <?php foreach ($children as $sub): ?>
                                                <li class="nav-0">
                                                    <a class="chakra-link nav-1bi585w" href="<?= $sub['url'] ?>">
                                                        <p class="chakra-text nav-0"><?= $sub['name'] ?></p>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="chakra-stack nav-1uckx97">
                    <?php if (empty(check_login())) { ?>
                        <a class="chakra-link nav-spn4bz" onclick="ModalManager.open('dang_nhap')" type="button"><button
                                type="button" class="chakra-button nav-1pomecj">Đăng nhập</button></a>
                        <a class="chakra-link nav-spn4bz" onclick="ModalManager.open('dang_ky')"><button type="button"
                                class="chakra-button nav-s9voqi1">Đăng ký ngay</button></a>
                    <?php } else { ?>
                        <button id="profile_btn" type="button" class="chakra-button chakra-menu__menu-button nav-1gvh9jv"
                            onclick="dropprofile()">
                            <span class="chakra-button__icon nav-1wh2kri">
                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                    stroke-linecap="round" stroke-linejoin="round" focusable="false"
                                    class="chakra-icon nav-13otjrl" aria-hidden="true" height="1em" width="1em"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M22.65 14.39L12 22.13 1.35 14.39a.84.84 0 0 1-.3-.94l1.22-3.78 2.44-7.51A.42.42 0 0 1 4.82 2a.43.43 0 0 1 .58 0 .42.42 0 0 1 .11.18l2.44 7.49h8.1l2.44-7.51A.42.42 0 0 1 18.6 2a.43.43 0 0 1 .58 0 .42.42 0 0 1 .11.18l2.44 7.51L23 13.45a.84.84 0 0 1-.35.94z">
                                    </path>
                                </svg>
                            </span>
                            <span class="nav-xl71ch">|
                                <?= format_number(isset($data_user['money']) ? $data_user['money'] : 0); ?>₫</span>
                            <span class="chakra-button__icon nav-1hzyiq5">
                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"
                                    height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </span>
                        </button>
                        <div id="dropdown" class="nav-r6z5ec menu"
                            style="visibility: hidden; min-width: max-content; --popper-transform-origin: top right; position: absolute; inset: 0px 0px auto auto; margin: 0px;">
                            <div class="chakra-menu__menu-list nav-1v9el6a">
                                <div id="menu-list-:rh:-menuitem-:ri:" role="menuitem" tabindex="-1"
                                    class="chakra-menu__menuitem nav-1cbda7y" data-index="0">
                                    <img alt="" class="chakra-image nav-4tzqtu"
                                        src="<?= base_url('assets/client/images/avt.png'); ?>" />
                                    <ul role="list" class="nav-155za0w">
                                        <div class="nav-xpvkkh">
                                            <p class="chakra-text nav-722v25">ID:</p>
                                            <?= format_number($data_user['id']); ?>
                                        </div>
                                        <div class="nav-xpvkkh">
                                            <p class="chakra-text nav-722v25">Tên:</p>
                                            <?= isset($data_user) ? $data_user['name'] : 'Not Found' ?>
                                        </div>
                                        <div class="nav-xpvkkh">
                                            <p class="chakra-text nav-722v25">Số dư:</p>
                                            <?= format_number($data_user['money']) ?>₫
                                        </div>
                                    </ul>
                                </div>
                                <hr aria-orientation="horizontal" class="chakra-menu__divider nav-9p5ngo" />
                                <?php if ($data_user['admin'] == 1): ?>
                                    <div class="chakra-menu__group" role="group">
                                        <p class="chakra-menu__group__title nav-cn0qcj">HỆ THỐNG</p>
                                        <?php if ($data_user['admin'] == 1) { ?>
                                            <a href="/admin">
                                                <button type="button" id="menu-list-:rh:-menuitem-:rj:" role="menuitem"
                                                    tabindex="-1" class="chakra-menu__menuitem nav-1ym84bf" data-index="1">
                                                    <span class="chakra-menu__icon-wrapper nav-7halv9">
                                                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                                            stroke-linecap="round" stroke-linejoin="round" focusable="false"
                                                            aria-hidden="true" class="chakra-menu__icon" height="1em" width="1em"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg>
                                                    </span>
                                                    <span style="pointer-events: none; flex: 1 1 0%;">Admin Dashboard</span>
                                                </button>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php endif; ?>
                                <?php
                                // =====================
                                // DANH SÁCH MENU TĨNH
                                // =====================
                                $menuGroups = [
                                    "TÀI KHOẢN" => [
                                        [
                                            "title" => "Thông tin tài khoản",
                                            "url" => "/account/profile",
                                            "modal" => false
                                        ],
                                        [
                                            "title" => "Đổi mật khẩu",
                                            "url" => "/account/change-password",
                                            "modal" => false
                                        ],
                                    ],

                                    "QUỸ SỐ DƯ" => [
                                        [
                                            "title" => "Nạp thẻ cào",
                                            "onclick" => "ModalManager.open('nap_the_cao')"
                                        ],
                                        [
                                            "title" => "Nạp ngân hàng",
                                            "onclick" => "ModalManager.open('nap_ngan_hang')"
                                        ],
                                    ],

                                    "LỊCH SỬ" => [
                                        [
                                            "title" => "Lịch sử giao dịch",
                                            "url" => "/wallet/history"
                                        ],
                                        [
                                            "title" => "Lịch sử nạp tiền",
                                            "url" => "/wallet/history/deposit"
                                        ],
                                        [
                                            "title" => "Lịch sử mua hàng",
                                            "url" => "/order/list"
                                        ],
                                    ]
                                ];

                                ?>

                                <!-- ============================
                                     RENDER MENU CHAKRA UI
                                =============================== -->
                                <?php foreach ($menuGroups as $groupName => $items): ?>
                                    <div class="chakra-menu__group" role="group">

                                        <p class="chakra-menu__group__title nav-cn0qcj"><?= $groupName ?></p>

                                        <?php foreach ($items as $item): ?>

                                            <?php if (!empty($item['onclick'])): ?>
                                                <a href="javascript:void(0);" onclick="<?= $item['onclick'] ?>">
                                                <?php else: ?>
                                                    <a href="<?= $item['url'] ?>">
                                                    <?php endif; ?>

                                                    <button type="button" class="chakra-menu__menuitem nav-1ym84bf">

                                                        <span class="chakra-menu__icon-wrapper nav-7halv9">
                                                            <svg stroke="currentColor" fill="none" stroke-width="2"
                                                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
                                                                class="chakra-menu__icon" height="1em" width="1em">
                                                                <polyline points="9 18 15 12 9 6"></polyline>
                                                            </svg>
                                                        </span>

                                                        <span style="pointer-events: none; flex: 1 1 0%;">
                                                            <?= $item['title'] ?>
                                                        </span>

                                                    </button>
                                                </a>
                                            <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>

                                <a href="<?= BASE_URL('account/logout'); ?>"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <button type="button"
                                        class="chakra-button chakra-menu__menuitem nav-tbcm8 ws-text-white" role="menuitem"
                                        tabindex="-1" data-index="9">Đăng xuất</button>
                                </a>
                                <form method="POST" action="<?= BASE_URL('ajax/logout'); ?>" accept-charset="UTF-8"
                                    id="logout-form" style="display: none;">
                                    <input name="csrf_token" type="hidden" value="<?= $csrf_token; ?>">
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="chakra-collapse" style="overflow: hidden; display: none; opacity: 0; height: 0px;"></div>
        </nav>
        <button id="call-navigation"
            class="md:tw-hidden tw-fixed tw-text-white tw-h-11 tw-px-3 tw-rounded-r-full tw-font-bold"
            style="z-index: 1001; top: 45%; left: -2px; background: rgba(255, 0, 0, 0.48);">
            <i class="tw-relative fa-solid fa-bars tw-text-xl" style="top: -3px;"></i>
            <div class="tw-absolute tw-text-xs tw-inline-block" style="left: 8px; top: 23px;"><small>Menu</small>
            </div>
        </button>
        <div id="menuProfile" class="tw-fixed tw-hidden tw-top-0 tw-bottom-0 tw-w-72 tw-bg-white tw-border-r-2 tw-pl-4 tw-pt-4" style="z-index:1001; left:-2px; transform: translateX(-100%); opacity: 0; transition: transform .3s ease, opacity .3s ease;">
            <div style="max-height: 95vh; overflow: hidden auto;">
                <div class="tw-w-full md:tw-bg-transparent tw-p-2 md:tw-p-0 tw-sticky tw-top-20" style="z-index: 999;">
                    <?php if (empty(check_login())) { ?>
                        <div class="tw-mb-3">
                            <div class="tw-relative tw-font-semibold tw-text-gray-800">
                                <span
                                    class="tw-h-7 tw-w-7 tw-rounded-full tw-inline-flex tw-justify-center tw-items-center tw-absolute tw-bg-red-500 tw-text-white"
                                    style="top: -2px;">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <a onclick="ModalManager.open('dang_nhap')"
                                    class="tw-ml-10 tw-block hover:tw-text-blue-600 tw-text-red-500"> Đăng nhập </a>
                            </div>
                        </div>
                        <div class="tw-mb-3">
                            <div class="tw-relative tw-font-semibold tw-text-gray-800">
                                <span
                                    class="tw-h-7 tw-w-7 tw-rounded-full tw-inline-flex tw-justify-center tw-items-center tw-absolute tw-bg-black tw-text-white"
                                    style="top: -2px;">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>
                                <a onclick="ModalManager.open('dang_ky')" class="tw-ml-10 tw-block hover:tw-text-blue-600">
                                    Đăng ký </a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="tw-grid tw-grid-cols-12 tw-gap-2 tw-pr-2 tw-pb-2">
                            <div class="tw-col-span-3 tw-flex tw-items-center tw-justify-content">
                                <img class="tw-w-full tw-rounded-full tw-border"
                                    src="<?= base_url('assets/client/images/avt.png'); ?>">
                            </div>
                            <div class="tw-col-span-9">
                                <p class="tw-flex tw-items-center">
                                    <b class="tw-mr-1">ID:</b> <?= isset($data_user) ? $data_user['id'] : 'Not Found' ?>
                                    <span
                                        class="tw-w-4 tw-h-4 tw-flex tw-items-center tw-justify-center tw-ml-2 tw-text-gray-600 tw-cursor-pointer"
                                        onclick="copy('<?= isset($data_user) ? $data_user['id'] : 'Not Found' ?>')">
                                        <i class="fa-solid fa-copy"></i>
                                    </span>
                                </p>
                                <p class="tw-text-sm">
                                    <b>Số dư:</b><span class="tw-text-red-600 tw-font-bold">
                                        <?= isset($data_user) ? format_number($data_user['money']) . 'đ' : 'Not Found' ?></span>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="tw-mb-4 tw-w-3/4 tw-border-b tw-border-gray-200"></div>
                    <?php foreach (($menus ?? []) as $item): ?>
                        <?php if ($item['parent_id'] != 0)
                            continue; ?>

                        <?php
                        $children = [];
                        $isParentActive = false;

                        foreach (($menus ?? []) as $m) {
                            if ($m['parent_id'] == $item['id']) {

                                $isSubActive = ($currentFullUrl === $m['url']);

                                if ($isSubActive) {
                                    $isParentActive = true;
                                }

                                $children[] = $m;
                            }
                        }

                        if ($currentFullUrl === $item['url']) {
                            $isParentActive = true;
                        }
                        ?>

                        <div class="tw-mb-3">

                            <!-- MENU CHA -->
                            <div
                                class="tw-relative tw-font-semibold <?= $isParentActive ? 'tw-text-red-600' : 'tw-text-gray-800' ?>">
                                <span class="tw-h-7 tw-w-7 tw-rounded-full tw-inline-flex tw-justify-center tw-items-center tw-absolute <?= $isParentActive ? 'tw-bg-red-500 tw-text-white' : 'tw-bg-gray-200' ?>" style="top: -2px;">
                                    <i class="<?= $item['icon'] ?: 'bx bx-menu' ?>"></i>
                                </span>

                                <a href="<?= $item['url'] ?>"
                                    class="tw-ml-10 tw-block <?= $isParentActive ? 'tw-text-red-600' : '' ?>">
                                    <?= $item['name'] ?>
                                </a>
                            </div>

                            <!-- MENU CON -->
                            <?php if (!empty($children)): ?>
                                <div class="tw-ml-11 tw-mt-1 tw-text-sm tw-font-semibold tw-text-gray-700">
                                    <ul>
                                        <?php foreach ($children as $sub): ?>
                                            <?php $isSubActive = ($currentFullUrl === $sub['url']); ?>
                                            <li>
                                                <a href="<?= $sub['url'] ?>"
                                                    class="tw-block tw-py-1 <?= $isSubActive ? 'tw-text-red-600' : '' ?>">
                                                    <?= $sub['name'] ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
            <button id="menuHide"
                class="md:tw-hidden tw-absolute tw-text-white tw-h-10 tw-px-3 tw-w-10 tw-rounded-full tw-bg-red-600"
                style="z-index: 1; top: 45%; right: -20px;">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </div>