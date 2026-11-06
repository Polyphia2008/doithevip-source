<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
    check_not_login_redirect();
?>
<div id="main" class="main">
    <div class="section-breadcrumb">
        <div class="container">
            <ol class="breadcrumb bg-transparent rounded-0 mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('');?>">Trang chủ</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?=BASE_URL('wallet/list');?>">Danh sách quỹ</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="section-gap">
        <div class="container">
            <div class="description mb-3">
                <div class="title"> Quỹ số dư </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-module">
                    <thead>
                        <tr>
                            <th class="text-center">Loại</th>
                            <th class="text-center">Số dư quỹ</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">VND</td>
                            <td class="text-center">
                                <span class="text-success">
                                    <b><?=format_number($data_user['money']);?>đ</b>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success badge-module">Hoạt động</span>
                            </td>
                            <td class="text-center">
                                <a href="<?=BASE_URL('wallet/history');?>">
                                    <span class="btn btn-sm btn-info" style="margin: 2px">
                                        <i class="fa fa-history"></i> Lịch sử </span>
                                </a>
                                <a href="<?=BASE_URL('wallet/deposit');?>">
                                    <span class="btn btn-sm btn-success" style="margin: 2px">
                                        <i class="fa fa-plus-circle"></i> Nạp quỹ </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>