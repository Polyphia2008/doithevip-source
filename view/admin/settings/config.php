<div class="page-container">
    <div class="page-title-head d-flex align-items-center gap-2">
        <div class="flex-grow-1">
            <h4 class="fs-16 text-uppercase fw-bold mb-0"><?=$title;?></h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0 fs-13">
                <li class="breadcrumb-item"><a href="javascript:void(0);"><?=anti_xss($main_domain);?></a></li>
                <li class="breadcrumb-item active"><?=$title;?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <?php render_msg_admin(); ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item"><a href="#general" data-bs-toggle="tab" class="nav-link active">Thông tin chung</a></li>
                            <li class="nav-item"><a href="#design" data-bs-toggle="tab" class="nav-link">Thiết kế</a></li>
                            <li class="nav-item"><a href="#images" data-bs-toggle="tab" class="nav-link">Hình ảnh</a></li>
                            <li class="nav-item"><a href="#system" data-bs-toggle="tab" class="nav-link">Cấu hình hệ thống</a></li>
                            <li class="nav-item"><a href="#connect" data-bs-toggle="tab" class="nav-link">Kết nối</a></li>
                            <li class="nav-item"><a href="#notice" data-bs-toggle="tab" class="nav-link">Thông báo</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Tab Thông tin chung -->
                            <div class="tab-pane show active" id="general">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" name="title" value="<?=anti_xss($settings['title'] ?? '')?>" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Mô tả</label>
                                        <input type="text" name="description" class="form-control" value="<?=anti_xss($settings['description'] ?? '')?>"></input>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" value="<?=anti_xss($settings['email'] ?? '')?>" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="phone" value="<?=anti_xss($settings['phone'] ?? '')?>" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Từ khóa</label>
                                        <input type="text" name="keywords" value="<?=anti_xss($settings['keywords'] ?? '')?>" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Bản quyền</label>
                                        <input type="text" name="copyright" value="<?=anti_xss($settings['copyright'] ?? '')?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tab Thiết kế -->
                            <div class="tab-pane" id="design">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Sắp xếp danh mục</label>
                                        <select name="location_danhmuc" class="form-select">
                                            <option value="muanick-dichvu" <?=($settings['location_danhmuc']??'')=='muanick-dichvu'?'selected':''?>>Mua Nick - Dịch Vụ</option>
                                            <option value="dichvu-muanick" <?=($settings['location_danhmuc']??'')=='dichvu-muanick'?'selected':''?>>Dịch Vụ - Mua Nick</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Bật/tắt lịch sử mua hàng</label>
                                        <select name="status_noti_sell" class="form-select">
                                            <option value="1" <?=($settings['status_noti_sell']??'')=='1'?'selected':''?>>Bật</option>
                                            <option value="0" <?=($settings['status_noti_sell']??'')=='0'?'selected':''?>>Tắt</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tab Hình ảnh -->
                            <div class="tab-pane" id="images">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Favicon</label>
                                        <input type="file" name="favicon" class="form-control">
                                        <?php if (!empty($settings['favicon'])): ?>
                                            <img src="<?=BASE_URL($settings['favicon'])?>" class="img-thumbnail mt-2" style="max-height:50px">
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Logo Light</label>
                                        <input type="file" name="logo_light" class="form-control">
                                        <?php if (!empty($settings['logo_light'])): ?>
                                            <img src="<?=BASE_URL($settings['logo_light'])?>" class="img-thumbnail mt-2" style="max-height:50px">
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Logo Dark</label>
                                        <input type="file" name="logo_dark" class="form-control">
                                        <?php if (!empty($settings['logo_dark'])): ?>
                                            <img src="<?=BASE_URL($settings['logo_dark'])?>" class="img-thumbnail mt-2" style="max-height:50px">
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Ảnh bìa</label>
                                        <input type="file" name="anhbia" class="form-control">
                                        <?php if (!empty($settings['anhbia'])): ?>
                                            <img src="<?=BASE_URL($settings['anhbia'])?>" class="img-thumbnail mt-2" style="max-height:50px">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Cấu hình hệ thống -->
                            <div class="tab-pane" id="system">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Trạng thái website</label>
                                        <select name="status_website" class="form-select">
                                            <option value="1" <?=($settings['status_website']??'')=='1'?'selected':''?>>Hoạt động</option>
                                            <option value="0" <?=($settings['status_website']??'')=='0'?'selected':''?>>Bảo trì</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Thời gian phiên đăng nhập (giây)</label>
                                        <input type="number" name="session_login" value="<?=anti_xss($settings['session_login'] ?? '')?>" class="form-control">
                                        <small class="text-muted">Tính bằng giây <?=anti_xss($settings['session_login'] ?? 0);?> giây = <?=timeAgo3($settings['session_login'] ?? 0);?></small>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Phí mặc định cho các đơn hàng (%)</label>
                                        <input type="number" name="fees" value="<?=anti_xss($settings['fees'] ?? '')?>" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Hạn mức nạp mỗi ngày</label>
                                        <input type="number" name="deposit_daily_limit" 
                                            value="<?=anti_xss($settings['deposit_daily_limit'] ?? '')?>" 
                                            class="form-control">
                                        <small class="text-muted">Giới hạn tổng số tiền user có thể nạp mỗi ngày (VND)</small>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Số tiền nạp tối thiểu</label>
                                        <input type="number" name="deposit_min_amount" 
                                            value="<?=anti_xss($settings['deposit_min_amount'] ?? '')?>" 
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Số tiền nạp tối đa</label>
                                        <input type="number" name="deposit_max_amount" 
                                            value="<?=anti_xss($settings['deposit_max_amount'] ?? '')?>" 
                                            class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Thời gian hết hạn hóa đơn (giây)</label>
                                        <input type="number" name="invoice_expire_time" value="<?=anti_xss($settings['invoice_expire_time'] ?? '')?>" class="form-control">
                                        <small class="text-muted">Tính bằng giây <?=anti_xss($settings['invoice_expire_time'] ?? 0);?> giây = <?=timeAgo3($settings['invoice_expire_time'] ?? 0);?></small>
                                    </div>
                                    
                                    
                                    
                                </div>
                            </div>

                            <!-- Tab Mạng xã hội -->
                            <div class="tab-pane" id="connect">
                                <div class="row">
                                    <!-- Google -->
                                    <div class="col-md-6">
                                        <div class="card border shadow-sm mb-3">
                                            <div class="card-header bg-success text-white d-flex align-items-center">
                                                <i class="ti ti-brand-google fs-20 me-2"></i>
                                                <h5 class="mb-0 text-white">Google OAuth</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Google Client ID</label>
                                                    <input type="text" name="google_client_id" 
                                                        value="<?=anti_xss($settings['google_client_id'] ?? '')?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Google Secret</label>
                                                    <input type="text" name="google_client_secret" 
                                                        value="<?=anti_xss($settings['google_client_secret'] ?? '')?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label">Callback URL</label>
                                                    <input type="text" readonly 
                                                        value="https://<?=anti_xss($main_domain);?>/account/google-callback" 
                                                        class="form-control bg-light">
                                                    <small class="text-muted">Dán URL này vào Google Console (Authorized redirect URIs)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Facebook -->
                                    <div class="col-md-6">
                                        <div class="card border shadow-sm mb-3">
                                            <div class="card-header bg-primary text-white d-flex align-items-center">
                                                <i class="ti ti-brand-facebook fs-20 me-2"></i>
                                                <h5 class="mb-0 text-white">Facebook OAuth</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Facebook Client ID</label>
                                                    <input type="text" name="facebook_client_id" 
                                                        value="<?=anti_xss($settings['facebook_client_id'] ?? '')?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Facebook Secret</label>
                                                    <input type="text" name="facebook_client_secret" 
                                                        value="<?=anti_xss($settings['facebook_client_secret'] ?? '')?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Graph Version</label>
                                                    <input type="text" name="facebook_graph_version" 
                                                        value="<?=anti_xss($settings['facebook_graph_version'] ?? '')?>" 
                                                        class="form-control">
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label">Callback URL</label>
                                                    <input type="text" readonly 
                                                        value="https://<?=anti_xss($main_domain);?>/account/facebook-callback" 
                                                        class="form-control bg-light">
                                                    <small class="text-muted">Dán URL này vào Facebook Developer (Valid OAuth Redirect URIs)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CloudFlare -->
                                    <div class="col-md-6">
                                        <div class="card border shadow-sm mb-3">
                                            <div class="card-header bg-warning text-white d-flex align-items-center">
                                                <i class="ti ti-brand-cloudflare fs-20 me-2"></i>
                                                <h5 class="mb-0 text-white">CloudFalre</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Bật/tắt Captcha CloudFlare</label>
                                                    <select name="cf_enable" class="form-select">
                                                        <option value="1" <?=($settings['cf_enable']??'')=='1'?'selected':''?>>Bật</option>
                                                        <option value="0" <?=($settings['cf_enable']??'')=='0'?'selected':''?>>Tắt</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Cloudflare Turnstile - Site Key</label>
                                                    <input type="text" name="cf_site_key" value="<?=anti_xss($settings['cf_site_key'] ?? '')?>" class="form-control" placeholder="Nhập Site Key">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Cloudflare Turnstile - Secret Key</label>
                                                    <input type="text" name="cf_secret_key" value="<?=anti_xss($settings['cf_secret_key'] ?? '')?>" class="form-control" placeholder="Nhập Secret Key">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Đổi Thẻ Cào -->
                                    <div class="col-md-6">
                                        <div class="card border shadow-sm mb-3">
                                            <div class="card-header bg-dark text-white d-flex align-items-center">
                                                <i class="ti ti-refresh fs-20 me-2"></i>
                                                <h5 class="mb-0 text-white">Đổi Thẻ Cào</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nhà Cung Cấp</label>
                                                    <input type="text" readonly value="<?= card_url_api(); ?>" class="form-control bg-light">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Partner ID</label>
                                                    <input type="number" name="card_partner_id" value="<?=anti_xss($settings['card_partner_id'] ?? 'Not Found')?>" class="form-control" placeholder="Nhập Partner ID">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Partner Key</label>
                                                    <input type="text" name="card_partner_key" value="<?=anti_xss($settings['card_partner_key'] ?? 'Not Found')?>" class="form-control" placeholder="Nhập Partner Key">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Phí đổi thẻ cào (%)</label>
                                                    <input type="number" name="fees_napthecao" value="<?=anti_xss($settings['fees_napthecao'] ?? 'Not Found')?>" class="form-control" placeholder="Nhập Secret Key">
                                                    <small class="text-muted">Để <?=$settings['fees_napthecao'];?> tức khách nạp 100.000đ sẽ nhận <?=format_number(100000 - 100000 * $settings['fees_napthecao'] / 100);?>đ</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Link Callback</label>
                                                    <input type="text" readonly value="<?=BASE_URL('api/wallet/doithecao');?>" class="form-control bg-light">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Thông báo -->
                            <div class="tab-pane" id="notice">
                                <div class="row">
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Thông báo nổi</label>
                                        <textarea name="notice_popup" id="notice_popup" class="form-control" rows="8">
                                            <?=anti_xss($settings['notice_popup'] ?? '')?>
                                        </textarea>
                                        <small class="text-muted">Thông báo này sẽ hiển thị trên trang chủ website ở dạng popup.</small>
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label class="form-label">Thông báo phần thưởng</label>
                                        <textarea name="phanthuong_popup" id="phanthuong_popup" class="form-control" rows="8">
                                            <?=anti_xss($settings['phanthuong_popup'] ?? '')?>
                                        </textarea>
                                        <small class="text-muted">Thông báo này sẽ hiển thị trên trang chủ khi người dùng nhấn vào nút phần thưởng.</small>
                                    </div>
                                </div>
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
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pairs = [
        ['color_picker_top', 'theme_color_top'],
        ['color_picker_footer', 'theme_color_footer']
    ];

    pairs.forEach(([pickerId, inputId]) => {
        const picker = document.getElementById(pickerId);
        const textbox = document.getElementById(inputId);

        // Khi chọn màu → update textbox
        picker.addEventListener('input', e => {
            textbox.value = e.target.value;
        });

        // Khi sửa textbox → update picker (nếu hợp lệ)
        textbox.addEventListener('input', e => {
            const val = e.target.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(val)) {  
                picker.value = val;
            }
        });
    });
});

</script>