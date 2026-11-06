<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Việc tự động";
load_admin_data('tools/cron-jobs');
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
                    <a href="javascript: void(0);"><?=$_SERVER['HTTP_HOST'];?></a>
                </li>
                <li class="breadcrumb-item active"><?=$title;?></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>

            <div class="card mb-3">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Bảo mật Cron (mã PIN)</h4>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-2 align-items-end">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        <div class="col-md-6">
                            <label class="form-label">Mã PIN bảo vệ Cron</label>
                            <input type="text" name="pin_cron" class="form-control" value="<?= anti_xss($pin_cron); ?>" placeholder="Để trống nếu không dùng PIN">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="save_cron_pin" class="btn btn-primary w-100">
                                <i class="ti ti-device-floppy"></i> Lưu PIN
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="card-title mb-0">Danh sách Cron Jobs</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="text-muted small">Cron tổng:</span>
                        <code class="small"><?= anti_xss($cronAllUrlDisplay); ?></code>
                        <button type="button" class="btn btn-sm btn-success" onclick="copyCronUrl(this)" data-url="<?= anti_xss($cronAllUrl); ?>">
                            <i class="ti ti-copy"></i> Sao chép
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>Tên tác vụ</th>
                                    <th>URL</th>
                                    <th>Khuyến nghị</th>
                                    <th>Cron gần đây</th>
                                    <th style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cronJobs as $index => $job): ?>
                                    <tr>
                                        <td><?= intval($index) + 1; ?></td>
                                        <td><?= anti_xss($job['name']); ?></td>
                                        <td>
                                            <span class="badge badge-soft-danger text-wrap text-start" style="white-space: normal;">
                                                <?= anti_xss($job['url_display']); ?>
                                            </span>
                                        </td>
                                        <td><span class="badge badge-soft-info"><?= anti_xss($job['recommend']); ?></span></td>
                                        <td><?= anti_xss($job['last_run']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="copyCronUrl(this)" data-url="<?= anti_xss($job['url']); ?>">
                                                <i class="ti ti-copy"></i> Copy
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function copyCronUrl(btn) {
    var url = btn.getAttribute('data-url') || '';
    if (!url) return;
    navigator.clipboard.writeText(url).then(function () {
        var oldHtml = btn.innerHTML;
        btn.innerHTML = '<i class="ti ti-check"></i> Đã copy';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-success');
        setTimeout(function () {
            btn.innerHTML = oldHtml;
            btn.classList.remove('btn-success');
            if (oldHtml.indexOf('Sao chép') === -1) {
                btn.classList.add('btn-outline-success');
            }
        }, 1500);
    }).catch(function () {
        prompt('Copy link cron:', url);
    });
}
</script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
