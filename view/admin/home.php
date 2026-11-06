<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Bảng quản trị - Quản trị hệ thống";
load_admin_data('home');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0">Bảng quản trị</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
                </li>
                <li class="breadcrumb-item active">Bảng quản trị</li>
            </ol>
        </div>
    </div>
    <div class="row row-cols-xxl-5 row-cols-md-2 row-cols-2">
        <?php foreach ($stats as $item): ?>
        <div class="col">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="text-muted fs-13 text-uppercase"><?= $item['label'] ?></h5>
                    <div class="d-flex align-items-center gap-2 my-2 py-1">
                        <div class="user-img fs-42 flex-shrink-0">
                            <span class="avatar-title <?= $item['bg'] ?> rounded-circle fs-22">
                                <iconify-icon icon="<?= $item['icon'] ?>"></iconify-icon>
                            </span>
                        </div>
                        <h3 class="mb-0 fw-bold"><?= format_number($item['value']) ?></h3>
                        <iconify-icon icon="<?= $item['icon'] ?>" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-tabs d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-0">Thống kê</h4>
                    </div>
                    <ul class="nav nav-tabs nav-justified card-header-tabs nav-bordered" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#ngay-ct" data-bs-toggle="tab" class="nav-link active" role="tab">
                                <i class="ti ti-calendar-clock d-md-none d-block"></i>
                                <span class="d-none d-md-block">Ngày</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tuan-ct" data-bs-toggle="tab" class="nav-link" role="tab">
                                <i class="ti ti-calendar-week d-md-none d-block"></i>
                                <span class="d-none d-md-block">Tuần</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#thang-ct" data-bs-toggle="tab" class="nav-link" role="tab">
                                <i class="ti ti-calendar-month d-md-none d-block"></i>
                                <span class="d-none d-md-block">Tháng</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#nam-ct" data-bs-toggle="tab" class="nav-link" role="tab">
                                <i class="ti ti-calendar-stats d-md-none d-block"></i>
                                <span class="d-none d-md-block">Năm</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tong-ct" data-bs-toggle="tab" class="nav-link" role="tab">
                                <i class="ti ti-chart-donut d-md-none d-block"></i>
                                <span class="d-none d-md-block">Tổng</span>
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="card-body" style="background: var(--section-bg);">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="ngay-ct" role="tabpanel">
                            <div class="row">
                                <?php renderServiceStats($todayStart, $todayEnd, 'Hôm nay'); ?>
                            </div>
                        </div>

                        <div class="tab-pane" id="tuan-ct" role="tabpanel">
                            <div class="row">
                                <?php renderServiceStats($monday, $sunday, 'Tuần này'); ?>
                            </div>
                        </div>

                        <div class="tab-pane" id="thang-ct" role="tabpanel">
                            <div class="row">
                                <?php renderServiceStats($monthStart, $monthEnd, 'Tháng này'); ?>
                            </div>
                        </div>

                        <div class="tab-pane" id="nam-ct" role="tabpanel">
                            <div class="row">
                                <?php renderServiceStats($yearStart, $yearEnd, 'Năm nay'); ?>
                            </div>
                        </div>

                        <div class="tab-pane" id="tong-ct" role="tabpanel">
                            <div class="row">
                                <?php renderServiceStats($allTime['start'], $allTime['end'], 'Tổng'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>