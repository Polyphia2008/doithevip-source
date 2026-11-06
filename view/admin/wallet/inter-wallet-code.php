<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý mã ví";
load_admin_data('wallet/inter-wallet-code');
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
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh Sách Mã Ví</h4>
                    <span class="badge bg-primary-subtle text-primary"><?= count($wallet_rows); ?> mã ví</span>
                </div>
                <div class="card-body p-0">
                    <div class="wallet-codes-toolbar px-3 py-3 border-bottom">
                        <input type="search" class="form-control" id="walletPageSearch" placeholder="Tìm mã ví hoặc tên ngân hàng..." autocomplete="off">
                        <div class="wallet-codes-filters mt-2">
                            <button type="button" class="wallet-filter-btn active" data-filter="all">Tất cả</button>
                            <button type="button" class="wallet-filter-btn" data-filter="rpay"><?= wallet_provider_ribbon('rpay', true, 'sm'); ?></button>
                            <button type="button" class="wallet-filter-btn" data-filter="stc"><?= wallet_provider_ribbon('stc', true, 'sm'); ?></button>
                            <button type="button" class="wallet-filter-btn" data-filter="pino"><?= wallet_provider_ribbon('pino', true, 'sm'); ?></button>
                        </div>
                    </div>

                    <div class="wallet-code-scroll wallet-code-scroll--page" id="walletPageList">
                        <?php if ($wallet_rows): ?>
                            <?php foreach ($wallet_rows as $row): ?>
                            <div class="wallet-code-item wallet-code-item--static"
                                data-code="<?= anti_xss($row['wallet_code']); ?>"
                                data-name="<?= anti_xss($row['wallet_name']); ?>"
                                data-pino="<?= $row['support_pino']; ?>"
                                data-stc="<?= $row['support_stc']; ?>"
                                data-rpay="<?= $row['support_rpay']; ?>">
                                <span class="wallet-code-badge"><?= anti_xss($row['wallet_code']); ?></span>
                                <span class="wallet-code-name"><?= anti_xss($row['wallet_name']); ?></span>
                                <span class="wallet-code-support"><?= $row['support_html']; ?></span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="wallet-codes-empty">Chưa có mã ví. Truy cập <code>/install</code> để cài đặt dữ liệu.</div>
                        <?php endif; ?>
                        <div class="wallet-codes-empty d-none" id="walletPageEmpty">Không tìm thấy mã ví phù hợp.</div>
                    </div>
                </div>
                <div class="card-footer border-top py-2">
                    <small class="text-muted" id="walletPageVisibleCount"></small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wallet-codes-toolbar,
.wallet-code-item,
.wallet-codes-empty { text-align: left; }

.wallet-codes-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
}

.wallet-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border: 1px solid var(--bs-border-color);
    border-radius: 999px;
    background: #fff;
    color: inherit;
    font-size: 0.8125rem;
    line-height: 1.2;
    transition: all 0.15s ease;
}

.wallet-filter-btn.active {
    border-color: #405189;
    background: #f3f6fb;
    box-shadow: inset 0 0 0 1px rgba(64, 81, 137, 0.08);
}

.wallet-code-scroll {
    max-height: min(70vh, 680px);
    overflow-y: auto;
    overflow-x: hidden;
}

.wallet-code-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.7rem 1rem;
    border-bottom: 1px solid var(--bs-border-color);
    background: #fff;
    text-align: left;
}

.wallet-code-item--static:hover {
    background: #f8f9fb;
}

.wallet-code-badge {
    display: inline-block;
    min-width: 5.5rem;
    padding: 0.2rem 0.55rem;
    margin-right: 0.75rem;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    color: #fff;
    background: #405189;
    border-radius: 0.35rem;
    text-align: left;
    flex-shrink: 0;
}

.wallet-code-name {
    flex: 1;
    min-width: 0;
    font-size: 0.875rem;
    color: #212529;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: left;
}

.wallet-code-support {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin-left: 0.75rem;
    flex-shrink: 0;
}

.wallet-codes-empty {
    padding: 1.25rem 1rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.wallet-provider-ribbon {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0.18rem 0.55rem 0.18rem 0.65rem;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    line-height: 1.2;
    color: #fff;
    border-radius: 0 0.25rem 0.25rem 0;
    clip-path: polygon(0 0, calc(100% - 6px) 0, 100% 50%, calc(100% - 6px) 100%, 0 100%, 6px 50%);
}

.wallet-provider-ribbon--sm {
    font-size: 0.62rem;
    padding: 0.12rem 0.45rem 0.12rem 0.55rem;
}

.wallet-provider-ribbon--rpay.is-on {
    background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
}

.wallet-provider-ribbon--stc.is-on {
    background: linear-gradient(135deg, #2a9d8f 0%, #1d7870 100%);
}

.wallet-provider-ribbon--pino.is-on {
    background: linear-gradient(135deg, #457b9d 0%, #1d3557 100%);
}

.wallet-provider-ribbon.is-off {
    color: #6c757d;
    background: #eef1f4;
    box-shadow: none;
}

.wallet-provider-ribbon--rpay.is-off {
    background: #fdecee;
    color: #b42318;
}

.wallet-provider-ribbon--stc.is-off {
    background: #ecfdf5;
    color: #047857;
}

.wallet-provider-ribbon--pino.is-off {
    background: #eff6ff;
    color: #1d4ed8;
}
</style>

<script>
function filterWalletPageList() {
    const keyword = ($('#walletPageSearch').val() || '').toLowerCase().trim();
    const filter = $('#walletPageList').closest('.card').find('.wallet-filter-btn.active').data('filter') || 'all';
    let visible = 0;
    const total = $('#walletPageList .wallet-code-item').length;

    $('#walletPageList .wallet-code-item').each(function () {
        const $item = $(this);
        const code = String($item.data('code') || '').toLowerCase();
        const name = String($item.data('name') || '').toLowerCase();
        const matchKeyword = !keyword || code.indexOf(keyword) !== -1 || name.indexOf(keyword) !== -1;
        const matchProvider = filter === 'all' || String($item.data(filter)) === '1';
        const show = matchKeyword && matchProvider;
        $item.toggle(show);
        if (show) visible++;
    });

    $('#walletPageVisibleCount').text('Hiển thị ' + visible + ' / ' + total + ' mã ví');
    $('#walletPageEmpty').toggleClass('d-none', visible > 0 || total === 0);
}

$('#walletPageSearch').on('input', filterWalletPageList);
$('.wallet-filter-btn').on('click', function () {
    $(this).closest('.wallet-codes-filters').find('.wallet-filter-btn').removeClass('active');
    $(this).addClass('active');
    filterWalletPageList();
});
filterWalletPageList();
</script>

<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>
