<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Cấu hình Mail";
load_admin_data('settings/mail');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Navbar.php');
?>
<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?=$title;?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
                </li>
                <li class="breadcrumb-item active"><?=$title;?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                        <div class="mb-3">
                            <label class="form-label">Email gửi đi (From Email)</label>
                            <input type="email" name="from_email" value="<?=anti_xss($setting['from_email'] ?? '')?>" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tên hiển thị (From Name)</label>
                            <input type="text" name="from_name" value="<?=anti_xss($setting['from_name'] ?? '')?>" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Driver</label>
                                <select name="driver" class="form-select">
                                    <option value="Smtp" <?=($setting['driver'] ?? '') == 'Smtp' ? 'selected' : ''?>>SMTP</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">SMTP Host</label>
                                <input type="text" name="host" value="<?=anti_xss($configs['host'] ?? '')?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">SMTP Port</label>
                                <input type="number" name="port" value="<?=anti_xss($configs['port'] ?? '')?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">SMTP Username</label>
                                <input type="text" name="username" value="<?=anti_xss($configs['username'] ?? '')?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">SMTP Password</label>
                                <input type="password" name="password" value="<?=anti_xss($configs['password'] ?? '')?>" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Encryption</label>
                                <select name="encryption" class="form-select">
                                    <option value="">-- None --</option>
                                    <option value="ssl" <?=($configs['encryption'] ?? '')=='ssl'?'selected':''?>>SSL</option>
                                    <option value="tls" <?=($configs['encryption'] ?? '')=='tls'?'selected':''?>>TLS</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Lưu cấu hình
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php'); 
?>
