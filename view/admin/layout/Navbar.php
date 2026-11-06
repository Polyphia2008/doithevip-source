<body>
    <div class="wrapper">
        <div class="sidenav-menu">
            <a href="<?= BASE_URL('admin'); ?>" class="logo">
                <span class="logo-light">
                    <span class="logo-lg">
                        <img src="<?= BASE_URL($site['logo_light'] ?? '') ?>" alt="logo">
                    </span>
                    <span class="logo-sm">
                        <img src="<?= BASE_URL($site['logo_light'] ?? '') ?>" alt="small logo">
                    </span>
                </span>
                <span class="logo-dark">
                    <span class="logo-lg">
                        <img src="<?= BASE_URL($site['logo_dark'] ?? '') ?>" alt="dark logo">
                    </span>
                    <span class="logo-sm">
                        <img src="<?= BASE_URL($site['logo_dark'] ?? '') ?>" alt="small logo">
                    </span>
                </span>
            </a>
            <!-- Sidebar Hover Menu Toggle Button -->
            <button class="button-sm-hover">
                <i class="ti ti-circle align-middle"></i>
            </button>
            <!-- Full Sidebar Menu Close Button -->
            <button class="button-close-fullsidebar">
                <i class="ti ti-x align-middle"></i>
            </button>
            <div data-simplebar>
                <!--- Sidenav Menu -->
                <ul class="side-nav">
                <li class="side-nav-title">Main</li>
                <li class="side-nav-item">
                    <a href="<?= BASE_URL('admin'); ?>" class="side-nav-link">
                        <span class="menu-icon"><i class="fal fa-home-alt"></i></span>
                        <span class="menu-text">Bảng quản trị</span>
                    </a>
                </li>
                <!-- Stores removed: single-site settings -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarHistory" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-history"></i></span>
                        <span class="menu-text">Lịch sử</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarHistory">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/history/logs'); ?>" class="side-nav-link"><span class="menu-text">Lịch sử hoạt động</span></a></li>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/history/transaction'); ?>" class="side-nav-link"><span class="menu-text">Lịch sử giao dịch</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Danh mục -->
                <li class="side-nav-title">Danh mục</li>
                <!-- Tài khoản -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarAccount" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-cloud-bolt"></i></span>
                        <span class="menu-text">Tài Khoản</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarAccount">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/account/category-list'); ?>" class="side-nav-link"><span class="menu-text">Danh mục</span></a></li>
                            <?php
                            if ($currentPath === '/admin/account/category-add') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/account/category-add').'" class="side-nav-link">
                                                <span class="menu-text">Thêm danh mục</span>
                                            </a>
                                        </li>';
                                }
                            if (preg_match('#^/admin/account/category-edit/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/account/category-edit/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chỉnh sửa</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/account/product-list'); ?>" class="side-nav-link"><span class="menu-text">Sản phẩm</span></a></li>
                            <?php
                            if ($currentPath === '/admin/account/product-add') {
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/account/product-add').'" class="side-nav-link">
                                            <span class="menu-text">Thêm sản phẩm</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/account/product-edit/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/account/product-edit/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chỉnh sửa</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/account/nick-list/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/account/nick-list/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Danh sách nick</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/account/nick-add/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/account/nick-add/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Thêm nick</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/account/nick-edit/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/account/nick-edit/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chỉnh sửa</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/account/orders'); ?>" class="side-nav-link"><span class="menu-text">Đơn hàng</span></a></li>
                        </ul>
                    </div>
                </li>
                <!-- Dịch vụ -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarService" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-meteor"></i></span>
                        <span class="menu-text">Dịch vụ</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarService">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/service/category-list'); ?>" class="side-nav-link"><span class="menu-text">Danh mục</span></a></li>
                            <?php
                            if ($currentPath === '/admin/service/category-add') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/service/category-add').'" class="side-nav-link">
                                                <span class="menu-text">Thêm danh mục</span>
                                            </a>
                                        </li>';
                                }
                            if (preg_match('#^/admin/service/category-edit/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/category-edit/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chỉnh sửa</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/service/product-list'); ?>" class="side-nav-link"><span class="menu-text">Sản phẩm</span></a></li>
                            <?php
                            if ($currentPath === '/admin/service/product-add') {
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/product-add').'" class="side-nav-link">
                                            <span class="menu-text">Thêm sản phẩm</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/service/product-edit/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/product-edit/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chỉnh sửa</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/service/pack-list/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/pack-list/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Danh sách gói</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/service/pack-add/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/pack-add/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Thêm gói</span>
                                        </a>
                                    </li>';
                            }
                            if (preg_match('#^/admin/service/pack-edit/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/pack-edit/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chỉnh sửa</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/service/orders'); ?>" class="side-nav-link"><span class="menu-text">Đơn hàng</span></a></li>
                            <?php
                            if (preg_match('#^/admin/service/order-view/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/service/order-view/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Xem chi tiết</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                        </ul>
                    </div>
                </li>
                <!-- Nội dung -->
                <li class="side-nav-title">Nội dung</li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarNews" class="side-nav-link">
                        <span class="menu-icon"><i class="fal fa-newspaper"></i></span>
                        <span class="menu-text">Tin tức</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarNews">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/news/new-cats'); ?>" class="side-nav-link"><span class="menu-text">Danh mục</span></a></li>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/news/new-list'); ?>" class="side-nav-link"><span class="menu-text">Tin tức</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Tài khoản -->
                <li class="side-nav-title">Tài khoản & Hệ thống</li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarUsers" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-users"></i></span>
                        <span class="menu-text">Tài khoản</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarUsers">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/users'); ?>" class="side-nav-link"><span class="menu-text">Người dùng</span></a></li>
                            <?php
                                if ($currentPath === '/admin/wallet/inter-wallet-code') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/wallet/inter-wallet-code').'" class="side-nav-link">
                                                <span class="menu-text">Quản lý mã ví</span>
                                            </a>
                                        </li>';
                                }

                                if (preg_match('#^/admin/users/(\d+)/edit$#', $path, $m)) {
                                    $id = $m[1];
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/users/'.$id.'/edit').'" class="side-nav-link">
                                                <span class="menu-text">Chỉnh sửa</span>
                                            </a>
                                        </li>';
                                }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/admins'); ?>" class="side-nav-link"><span class="menu-text">Quản trị viên</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Ví điện tử -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarWallet" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-wallet"></i></span>
                        <span class="menu-text">Ví điện tử</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarWallet">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/wallet/paygates'); ?>" class="side-nav-link"><span class="menu-text">Cổng thanh toán</span></a></li>
                            <?php
                            if (preg_match('#^/admin/wallet/invoice-view/([A-Za-z0-9_-]+)$#', $path, $m)) {
                                $id = $m[1];
                                echo '<li class="side-nav-item">
                                        <a href="'.BASE_URL('admin/wallet/invoice-view/'.$id).'" class="side-nav-link">
                                            <span class="menu-text">Chi tiết</span>
                                        </a>
                                    </li>';
                            }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/wallet/invoices'); ?>" class="side-nav-link"><span class="menu-text">Danh sách hóa đơn</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Công cụ -->
                <li class="side-nav-title">Tiện ích & Cấu hình</li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarTools" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-tools"></i></span>
                        <span class="menu-text">Công cụ</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTools">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/tools/seo-onpage'); ?>" class="side-nav-link"><span class="menu-text">Seo Onpage</span></a></li>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/tools/informations'); ?>" class="side-nav-link"><span class="menu-text">Thông tin</span></a></li>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/tools/cron-jobs'); ?>" class="side-nav-link"><span class="menu-text">Việc tự động</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Mô-đun -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarModules" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-box-alt"></i></span>
                        <span class="menu-text">Các mô-đun</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarModules">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/modules/menu-list'); ?>" class="side-nav-link"><span class="menu-text">Menu</span></a></li>
                            <?php
                                if ($currentPath === '/admin/modules/menu-add') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/menu-add').'" class="side-nav-link">
                                                <span class="menu-text">Thêm menu</span>
                                            </a>
                                        </li>';
                                }

                                if (preg_match('#^/admin/modules/menu/(\d+)/edit$#', $path, $m)) {
                                    $id = $m[1];
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/menu/'.$id.'/edit').'" class="side-nav-link">
                                                <span class="menu-text">Chỉnh sửa</span>
                                            </a>
                                        </li>';
                                }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/modules/menu-quick-access-list'); ?>" class="side-nav-link"><span class="menu-text">Menu Nhanh</span></a></li>
                            <?php
                                if ($currentPath === '/admin/modules/menu-quick-access-add') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/menu-quick-access-add').'" class="side-nav-link">
                                                <span class="menu-text">Thêm menu</span>
                                            </a>
                                        </li>';
                                }

                                if (preg_match('#^/admin/modules/menu-quick-access/(\d+)/edit$#', $path, $m)) {
                                    $id = $m[1];
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/menu-quick-access/'.$id.'/edit').'" class="side-nav-link">
                                                <span class="menu-text">Chỉnh sửa</span>
                                            </a>
                                        </li>';
                                }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/modules/slider-list'); ?>" class="side-nav-link"><span class="menu-text">Trình diễn ảnh</span></a></li>
                            <?php
                                if ($currentPath === '/admin/modules/slider-add') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/slider-add').'" class="side-nav-link">
                                                <span class="menu-text">Thêm slider</span>
                                            </a>
                                        </li>';
                                }

                                if (preg_match('#^/admin/modules/slider/(\d+)/edit$#', $path, $m)) {
                                    $id = $m[1];
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/slider/'.$id.'/edit').'" class="side-nav-link">
                                                <span class="menu-text">Chỉnh sửa</span>
                                            </a>
                                        </li>';
                                }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/modules/coupon-list'); ?>" class="side-nav-link"><span class="menu-text">Mã giảm giá</span></a></li>
                            <?php
                                if ($currentPath === '/admin/modules/coupon-add') {
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/coupon-add').'" class="side-nav-link">
                                                <span class="menu-text">Thêm mã giảm giá</span>
                                            </a>
                                        </li>';
                                }

                                if (preg_match('#^/admin/modules/coupon/(\d+)/edit$#', $path, $m)) {
                                    $id = $m[1];
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/coupon/'.$id.'/edit').'" class="side-nav-link">
                                                <span class="menu-text">Chỉnh sửa</span>
                                            </a>
                                        </li>';
                                }
                            ?>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/modules/flash-sale-list'); ?>" class="side-nav-link"><span class="menu-text">Flash Sale</span></a></li>
                            <?php
                                if (preg_match('#^/admin/modules/flash-sale-add/(\d+)/product$#', $path, $m)) {
                                    $id = $m[1];
                                    echo '<li class="side-nav-item">
                                            <a href="'.BASE_URL('admin/modules/flash-sale-add/'.$id.'/product').'" class="side-nav-link">
                                                <span class="menu-text">Sản phẩm</span>
                                            </a>
                                        </li>';
                                }
                            ?>
                        </ul>
                    </div>
                </li>

                <!-- Cấu hình -->
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarSettings" class="side-nav-link">
                        <span class="menu-icon"><i class="fa-regular fa-cog"></i></span>
                        <span class="menu-text">Cấu hình</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSettings">
                        <ul class="sub-menu">
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/setting/setting'); ?>" class="side-nav-link"><span class="menu-text">Cài đặt</span></a></li>
                            <li class="side-nav-item"><a href="<?= BASE_URL('admin/setting/mail'); ?>" class="side-nav-link"><span class="menu-text">Cấu hình Mail</span></a></li>
                        </ul>
                    </div>
                </li>
            </ul>

            </div>
        </div>
        
        <header class="app-topbar">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">
                    <!-- Brand Logo -->
                    <a href="index.html" class="logo">
                        <span class="logo-light">
                            <span class="logo-lg">
                                <img src="<?= BASE_URL($site['logo_light'] ?? '') ?>" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="<?= BASE_URL($site['logo_light'] ?? '') ?>" alt="small logo">
                            </span>
                        </span>
                        <span class="logo-dark">
                            <span class="logo-lg">
                                <img src="<?= BASE_URL($site['logo_dark'] ?? '') ?>" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="<?= BASE_URL($site['logo_dark'] ?? '') ?>" alt="small logo">
                            </span>
                        </span>
                    </a>
                    <!-- Sidebar Menu Toggle Button -->
                    <button class="sidenav-toggle-button px-2">
                        <i class="ti ti-menu-2 fs-24"></i>
                    </button>
                    <!-- Horizontal Menu Toggle Button -->
                    <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="ti ti-menu-deep fs-22"></i>
                    </button>
                    
                </div>
                <div class="d-flex align-items-center gap-2">
                    
                    
                    <div class="topbar-item">
                        <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" type="button">
                            <i class="fa-regular fa-gear fs-22"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-content">