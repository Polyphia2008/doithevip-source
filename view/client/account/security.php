<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Bảo Mật Tài Khoản";
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
                    <a href="<?=BASE_URL('account/security');?>">Bảo mật tài khoản</a>
                </li>
            </ol>
        </div>
    </div>

    <div class="section-gap">
        <div class="container">

            <?php render_msg_client(); ?>

            <div class="section-gap">
                <div class="description mb-3">
                    <div class="title">
                        Bảo mật tài khoản
                    </div>
                </div>
                <div class="alert-custom alert alert-warning">
                    <?=get_informations('security');?>
                </div>
                <div class="mt-4">
                    <table class="table table-condensed table-module">
                        <tbody>
                            <tr class="">
                                <td style="width: 10%;padding-right: 0px!important;">
                                    <img src="<?=BASE_URL('assets/client/images/Email.png');?>">
                                </td>
                                <td style="padding-left: 10px!important;width: 75%"><span
                                        style="font-size: 16px"><strong>Bảo mật bằng Email </strong></span><br>
                                    <span class="small">
                                        Chúng tôi sẽ gửi mã xác thực vào email mỗi khi bạn thực hiện giao dịch (Mức an toàn cao)
                                    </span>
                                </td>
                                <?php if ($data_user['twofactor'] === 'Email'): ?>
                                    <td>
                                        <span class="badge badge-success badge-module">
                                            Đang bật
                                        </span>
                                    </td>
                                    <td style="width: 15%" class="text-center">
                                        <a class="btn btn-outline-danger btn-sm_table" href="<?= BASE_URL('disable/security/Email'); ?>">Hủy bỏ</a>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <span class="badge badge-secondary badge-module">
                                            Đang tắt
                                        </span>
                                    </td>
                                    <td style="width: 15%" class="text-center">
                                        <a class="btn btn-outline-success btn-sm_table" href="<?= BASE_URL('active/security/Email'); ?>">
                                            Kích hoạt
                                        </a>
                                    </td>
                                <?php endif; ?>

                            </tr>
                            <tr class="">
                                <td style="width: 10%;padding-right: 0px!important;">
                                    <img src="<?=BASE_URL('assets/client/images/GoogleAuth.png');?>">
                                </td>
                                <td style="padding-left: 10px!important;width: 75%"><span
                                        style="font-size: 16px"><strong>Bảo mật bằng GoogleAuth </strong></span><br>
                                    <span class="small">
                                        Mã xác thực sẽ hiện trên App điện thoại của bạn (Mức an toàn cao)
                                    </span>
                                </td>
                                <?php if ($data_user['twofactor'] === 'GoogleAuth'): ?>
                                    <td>
                                        <span class="badge badge-success badge-module">
                                            Đang bật
                                        </span>
                                    </td>
                                    <td style="width: 15%" class="text-center">
                                        <a class="btn btn-outline-danger btn-sm_table" href="<?= BASE_URL('disable/security/GoogleAuth'); ?>">Hủy bỏ</a>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <span class="badge badge-secondary badge-module">
                                            Đang tắt
                                        </span>
                                    </td>
                                    <td style="width: 15%" class="text-center">
                                        <a class="btn btn-outline-success btn-sm_table" href="<?= BASE_URL('active/security/GoogleAuth'); ?>">
                                            Kích hoạt
                                        </a>
                                    </td>
                                <?php endif; ?>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>