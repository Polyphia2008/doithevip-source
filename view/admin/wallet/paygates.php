<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = "Quản lý cổng thanh toán";
load_admin_data('wallet/paygates');
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
                <div class="card-header border-bottom border-dashed">
                    <h4 class="card-title mb-0">Nhà cung cấp &amp; hướng dẫn nạp bank tự động</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($bankApiProviders as $providerIndex => $providerRow): ?>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="border rounded p-3 h-100 bg-white d-flex flex-column provider-card position-relative overflow-hidden">
                                <?php if (strtoupper($providerRow['code']) === 'RPAY'): ?>
                                    <?= wallet_provider_corner_ribbon('Nên dùng'); ?>
                                <?php endif; ?>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-dark"><?= anti_xss($providerRow['code']); ?></span>
                                    <i class="ti ti-book"></i>
                                </div>
                                <div class="fw-bold mb-1"><?= anti_xss($providerRow['name']); ?></div>
                                <small class="text-muted d-block mb-3" style="word-break: break-all;"><?= anti_xss($providerRow['url']); ?></small>
                                <button type="button" class="btn btn-sm btn-outline-dark align-self-start" data-bs-toggle="modal" data-bs-target="#guideModal<?= $providerIndex; ?>">
                                    <i class="ti ti-book me-1"></i> Hướng dẫn
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh Sách Cổng Thanh Toán</h4>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bankingModal" onclick="openAddBanking()">
                        <i class="ti ti-plus"></i> Thêm Ngân Hàng
                    </a>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="col"> # </th>
                                    <th scope="col"> NCC </th>
                                    <th scope="col"> Thông tin ví </th>
                                    <th scope="col"> Dữ liệu ví </th>
                                    <th scope="col"> Phí </th>
                                    <th scope="col"> Hành động </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                $isd = 0;
                                foreach($bankings as $row){
                                    $isd+=1;
                                ?>
                                
                                <tr id="row-order-<?=$row['id'];?>">
                                    <th scope="row"> <?=$isd;?> </th>
                                    <th scope="row"> <?=$row['typeApi'];?> </th>
                                    <th scope="row">
                                        Mã Ví: <?=$row['bankName'];?><br>
                                        Tên Ví: <?=$row['textNameBank'];?>
                                    </th>
                                    <th scope="row">
                                        Địa Chỉ Ví: <?=$row['bankAccount'];?><br>
                                        <a class="text-danger">Chủ Ví: <?=$row['bankOwner'];?></a>
                                    </th>
                                    <th scope="row"> 
                                        Phí Cố Định: <?=format_number($row['fixed_fee']);?><sup>đ</sup><br> 
                                        Phí Phần Trăm: <?=format_number($row['fees_percent']);?>%
                                    </th>
                                    <th scope="row">
                                        <a href="#" class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" data-bs-target="#bankingModal"
                                            onclick='openEditBanking(<?=json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT)?>)'>
                                            <i class="ti ti-edit me-1"></i> Chỉnh Sửa
                                        </a>

                                        <button class="btn btn-sm btn-danger btn-delete-single" 
                                            data-id="<?= $row['id'] ?>" 
                                            data-table="bankings" 
                                            data-column-remove="id">
                                            <i class="ti ti-trash"></i> Xóa
                                        </button>
                                    </th>
                                </tr>
                                
                                <?php } ?>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($bankApiProviders as $providerIndex => $providerRow): ?>
<div class="modal fade" id="guideModal<?= $providerIndex; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <span class="badge bg-light text-dark me-2"><?= anti_xss($providerRow['code']); ?></span>
                    Hướng dẫn tích hợp — <?= anti_xss($providerRow['name']); ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ol class="mb-0 ps-3">
                    <?php foreach ($providerRow['steps'] as $guideStep): ?>
                    <li class="mb-2"><?= $guideStep; ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
            <div class="modal-footer justify-content-between">
                <a href="<?= anti_xss($providerRow['url']); ?>" target="_blank" rel="noopener" class="btn btn-outline-dark">Mở website</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="walletCodesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content wallet-codes-modal">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-wallet"></i>
                    <span>Danh sách Mã Ví</span>
                    <span class="badge bg-primary-subtle text-primary"><?= count($walletCodeList); ?></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="wallet-codes-toolbar px-3 py-3 border-bottom">
                    <input type="search" class="form-control" id="walletSearch" placeholder="Tìm mã ví hoặc tên ngân hàng..." autocomplete="off">
                    <div class="wallet-codes-filters mt-2">
                        <button type="button" class="wallet-filter-btn active" data-filter="all">Tất cả</button>
                        <button type="button" class="wallet-filter-btn" data-filter="rpay"><?= wallet_provider_ribbon('rpay', true, 'sm'); ?></button>
                        <button type="button" class="wallet-filter-btn" data-filter="stc"><?= wallet_provider_ribbon('stc', true, 'sm'); ?></button>
                        <button type="button" class="wallet-filter-btn" data-filter="pino"><?= wallet_provider_ribbon('pino', true, 'sm'); ?></button>
                    </div>
                </div>
                <div class="wallet-code-scroll" id="walletCodeListWrap">
                    <?php if ($walletCodeList): ?>
                        <?php foreach ($walletCodeList as $walletRow): ?>
                        <?php
                            $walletSupport = json_decode($walletRow['support'] ?? '', true);
                            if (!is_array($walletSupport)) {
                                $walletSupport = [];
                            }
                            $supportPino = ($walletSupport['pino'] ?? '0') === '1' ? 1 : 0;
                            $supportStc = ($walletSupport['stc'] ?? '0') === '1' ? 1 : 0;
                            $supportRpay = ($walletSupport['rpay'] ?? '0') === '1' ? 1 : 0;
                        ?>
                        <button type="button"
                            class="wallet-code-item"
                            data-code="<?= anti_xss($walletRow['wallet_code']); ?>"
                            data-name="<?= anti_xss($walletRow['wallet_name']); ?>"
                            data-pino="<?= $supportPino; ?>"
                            data-stc="<?= $supportStc; ?>"
                            data-rpay="<?= $supportRpay; ?>">
                            <span class="wallet-code-badge"><?= anti_xss($walletRow['wallet_code']); ?></span>
                            <span class="wallet-code-name"><?= anti_xss($walletRow['wallet_name']); ?></span>
                            <span class="wallet-code-support">
                                <?= wallet_provider_ribbon('rpay', $supportRpay === 1, 'sm'); ?>
                                <?= wallet_provider_ribbon('stc', $supportStc === 1, 'sm'); ?>
                                <?= wallet_provider_ribbon('pino', $supportPino === 1, 'sm'); ?>
                            </span>
                            <i class="ti ti-chevron-right wallet-code-arrow"></i>
                        </button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="wallet-codes-empty">Chưa có mã ví. Truy cập <code>/install</code> để cài đặt dữ liệu.</div>
                    <?php endif; ?>
                    <div class="wallet-codes-empty d-none" id="walletEmptyFilter">Không tìm thấy mã ví phù hợp.</div>
                </div>
            </div>
            <div class="modal-footer border-top py-2 justify-content-between">
                <small class="text-muted" id="walletVisibleCount"></small>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bankingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="bankingModalLabel">Xác nhận xóa đơn hàng</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="banking_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Loại API</label>
                            <select class="form-select" name="typeApi" id="typeApi" required>
                                <option value="RPAY">RPAY</option>
                                <option value="PINO_API">PINO_API</option>
                                <option value="STC">STC</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại Ví</label>
                            <select class="form-select" name="wallet_type" id="wallet_type" required onchange="toggleWalletCookie()">
                                <option value="bank_wallet">Bank Wallet</option>
                                <option value="card_wallet">Card Wallet</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã Ví / BankName</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bankName" id="bankName" required>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#walletCodesModal">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                Xem danh sách mã ví tại
                                <a href="<?= BASE_URL('admin/wallet/inter-wallet-code'); ?>" target="_blank">Quản lý Mã Ví</a>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên Ví / textNameBank</label>
                            <input type="text" class="form-control" name="textNameBank" id="textNameBank" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">API Token</label>
                            <input type="text" class="form-control" name="apiToken" id="apiToken">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số Tài Khoản</label>
                            <input type="text" class="form-control" name="bankAccount" id="bankAccount">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="bankUsername" id="bankUsername">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="bankPassword" id="bankPassword">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chủ Ví</label>
                            <input type="text" class="form-control" name="bankOwner" id="bankOwner">
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-12" id="walletCookieGroup">
                            <label class="form-label">Wallet Cookie</label>
                            <textarea class="form-control" name="walletCookie" id="walletCookie"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phí Cố Định</label>
                            <input type="number" class="form-control" name="fixed_fee" id="fixed_fee" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phí %</label>
                            <input type="number" class="form-control" name="fees_percent" id="fees_percent" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Thanh toán hóa đơn?</label>
                            <select class="form-select" name="invoice_pay" id="invoice_pay">
                                <option value="1">Có</option>
                                <option value="0">Không</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="saveBanking" class="btn btn-danger">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function openAddBanking(){
    $('#bankingModalLabel').text('Thêm Ngân Hàng');
    $('#banking_id').val('');
    $('#bankingModal form')[0].reset();

    // mặc định chọn bank_wallet
    $('#wallet_type').val('bank_wallet');
    toggleWalletCookie();
}

function openEditBanking(data){
    $('#bankingModalLabel').text('Chỉnh Sửa Ngân Hàng');
    for(const key in data){
        if($('#'+key).length){
            $('#'+key).val(data[key]);
        }
    }

    // kiểm tra bankName xem có phải domain/website không
    if(isDomain(data.bankName)){
        $('#wallet_type').val('card_wallet');
    } else {
        $('#wallet_type').val('bank_wallet');
    }

    toggleWalletCookie();
}

// helper function: check có phải domain/URL không
function isDomain(str){
    return /^(https?:\/\/|www\.)/i.test(str) || str.includes('.');
}

function toggleWalletCookie(){
    if($('#wallet_type').val() === 'card_wallet'){
        $('#walletCookieGroup').show();
    } else {
        $('#walletCookieGroup').hide();
    }
}

function filterWalletCodes() {
    const keyword = ($('#walletSearch').val() || '').toLowerCase().trim();
    const filter = $('.wallet-filter-btn.active').data('filter') || 'all';
    let visible = 0;
    const total = $('#walletCodeListWrap .wallet-code-item').length;

    $('#walletCodeListWrap .wallet-code-item').each(function () {
        const $item = $(this);
        const code = String($item.data('code') || '').toLowerCase();
        const name = String($item.data('name') || '').toLowerCase();
        const matchKeyword = !keyword || code.indexOf(keyword) !== -1 || name.indexOf(keyword) !== -1;
        const matchProvider = filter === 'all' || String($item.data(filter)) === '1';
        const show = matchKeyword && matchProvider;
        $item.toggle(show);
        if (show) visible++;
    });

    $('#walletVisibleCount').text('Hiển thị ' + visible + ' / ' + total + ' mã ví');
    $('#walletEmptyFilter').toggleClass('d-none', visible > 0 || total === 0);
}

function selectWalletCode(code, name) {
    $('#bankName').val(code);
    $('#textNameBank').val(name);
    const walletModal = bootstrap.Modal.getInstance(document.getElementById('walletCodesModal'));
    if (walletModal) walletModal.hide();
    setTimeout(function () {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('bankingModal')).show();
    }, 200);
}

$('#walletSearch').on('input', filterWalletCodes);
$('.wallet-filter-btn').on('click', function () {
    $('.wallet-filter-btn').removeClass('active');
    $(this).addClass('active');
    filterWalletCodes();
});
$('#walletCodeListWrap').on('click', '.wallet-code-item', function () {
    selectWalletCode($(this).data('code'), $(this).data('name'));
});
$('#walletCodesModal').on('shown.bs.modal', function () {
    $('#walletSearch').val('');
    $('.wallet-filter-btn').removeClass('active');
    $('.wallet-filter-btn[data-filter="all"]').addClass('active');
    filterWalletCodes();
    setTimeout(function () { $('#walletSearch').trigger('focus'); }, 150);
});
</script>
<style>
.wallet-codes-modal .modal-header,
.wallet-codes-modal .modal-footer,
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
    max-height: min(58vh, 520px);
    overflow-y: auto;
    overflow-x: hidden;
}

.wallet-code-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 0.7rem 1rem;
    border: 0;
    border-bottom: 1px solid var(--bs-border-color);
    background: #fff;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.wallet-code-item:hover {
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
    margin: 0 0.75rem;
    flex-shrink: 0;
}

.wallet-code-arrow {
    color: #adb5bd;
    font-size: 0.95rem;
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

.provider-card {
    overflow: hidden;
}

.wallet-corner-ribbon {
    position: absolute;
    top: 14px;
    right: -34px;
    z-index: 2;
    display: block;
    width: 130px;
    padding: 4px 0;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    line-height: 1.2;
    color: #fff;
    text-align: center;
    text-transform: uppercase;
    background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    transform: rotate(45deg);
    pointer-events: none;
}
</style>

<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/admin/layout/Footer.php');
?>