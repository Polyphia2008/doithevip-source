<style>
.social-login-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #1e1e1e;
    border: 1px solid rgba(255,255,255,.08);
    color: #fff;
    font-weight: 500;
    text-decoration: none;
    transition: all .2s ease;
}

.social-login-btn img {
    width: 20px;
    height: 20px;
}

.social-login-btn:hover {
    background: #2a2a2a;
    transform: translateY(-1px);
}

</style>
<div class="modal fade chakra-portal" id="dang_ky_modal" role="dialog" aria-hidden="true" style="z-index: 2006; ">
    <div class="chakra-modal__overlay nav-hdd9l7 modal-dialog" role="dialog" aria-modal="true" aria-hidden="true" style="opacity: 1;"></div>
    <div class="el-dialog" tabindex="-1">
        <div data-focus-lock-disabled="false">
            <div class="chakra-modal__content-container nav-1u2cvaz" tabindex="-1">
                <section
                    class="chakra-modal__content nav-6gzxwn"
                    role="dialog"
                    id="chakra-modal-:rd:"
                    tabindex="-1"
                    aria-modal="true"
                    style="opacity: 1; transform: none;"
                    aria-labelledby="chakra-modal--header-:rd:"
                    aria-describedby="chakra-modal--body-:rd:"
                >
                    <button type="button" data-dismiss="modal" aria-label="Close" class="chakra-modal__close-btn nav-1ik4h6n close">
                        <svg viewBox="0 0 24 24" focusable="false" class="chakra-icon nav-onkibi" aria-hidden="true">
                            <path
                                fill="currentColor"
                                d="M.439,21.44a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,1,0,2.122-2.121L14.3,12.177a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.44L12.177,9.7a.25.25,0,0,1-.354,0L2.561.44A1.5,1.5,0,0,0,.439,2.561L9.7,11.823a.25.25,0,0,1,0,.354Z"
                            ></path>
                        </svg>
                    </button>
                    <div class="nav-1l4w6pd">
                    <div class="nav-1yfw0to">
                        <form>
                            <h1 class="chakra-heading nav-fe15k6">ĐĂNG KÝ</h1>
                            <div class="nav-11nrrcx">
                                <p class="chakra-text nav-1ivn8l3"></p>
                                <?php
                                $socials = [
                                    'google' => [
                                        'enable' => $db->site('google_client_id') && $db->site('google_client_secret'),
                                        'url'    => BASE_URL('account/google'),
                                        'icon'   => BASE_URL('assets/client/images/gg-logo.png'),
                                        'text'   => 'Đăng nhập bằng Google'
                                    ],
                                    'facebook' => [
                                        'enable' => $db->site('facebook_client_id') && $db->site('facebook_client_secret'),
                                        'url'    => BASE_URL('account/facebook'),
                                        'icon'   => BASE_URL('assets/client/images/fb-logo.png'),
                                        'text'   => 'Đăng nhập bằng Facebook'
                                    ]
                                ];
                                ?>

                                <?php foreach ($socials as $social): ?>
                                    <?php if ($social['enable']): ?>
                                        <a class="social-login-btn" href="<?= $social['url']; ?>">
                                            <img src="<?= $social['icon']; ?>" alt="">
                                            <span><?= $social['text']; ?></span>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="el-divider el-divider--horizontal" role="separator" style="--el-border-style: solid;">
                                    <div class="el-divider__text is-center">
                                        <span class="ws-text-zinc-500">hoặc</span>
                                    </div>
                                </div>
                                <div role="group" class="chakra-form-control nav-1kxonj9">
                                    <div class="chakra-input__group nav-bx0blc" data-group="true">
                                        <input type="text" class="chakra-input input-animation nav-ghxgsz" placeholder="Tên đăng nhập" id="username" />
                                    </div>
                                </div>
                                <div role="group" class="chakra-form-control nav-1kxonj9">
                                    <div class="chakra-input__group nav-bx0blc" data-group="true">
                                        <input type="text" class="chakra-input input-animation nav-ghxgsz" placeholder="Số đt hoặc email (có thể bỏ trống)" id="phoneOrEmail" />
                                    </div>
                                </div>
                                <div role="group" class="chakra-form-control nav-1kxonj9">
                                    <div class="chakra-input__group nav-bx0blc" data-group="true">
                                        <input type="password" class="chakra-input input-animation nav-7e2yxe" placeholder="Mật khẩu" id="password" />
                                        <div class="chakra-input__right-element nav-1ey238l">
                                            <button type="button" class="chakra-button nav-kmzh8b" onclick="togglePassword('password', this)">
                                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($db->site('cf_enable') == 1): ?>
                                <div class="row row15">
                                    <div class="col-lg-8 offset-lg-4">
                                        <div class="cf-turnstile"
                                            data-sitekey="<?= $db->site('cf_site_key') ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <button type="button" id="Register" class="chakra-button nav-n4cpn9">
                                    <p class="chakra-text nav-u3el3g">Đăng ký ngay</p>
                                    <i class="el-icon el-input__icon el-input__password">
                                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" focusable="false" class="chakra-icon nav-1r7jwya" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </i>
                                </button>
                                <div class="nav-1vhdyq4">
                                    <a onclick="ModalManager.open('dang_nhap')"><p class="chakra-text nav-19ji5v2">Bạn đã có tài khoản? Đăng nhập ngay</p></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#Register").on("click", function() {
    $('#Register').html('<i class="fa fa-spinner fa-spin-custom" style="margin-right: 10px;"></i> Đang xử lý').prop('disabled',
        true);
    $.ajax({
        url: "<?=BASE_URL('ajax/register');?>",
        method: "POST",
        dataType:"JSON",
        data: {
            username: $("#username").val(),
            password: $("#password").val(),
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            if (response.status == 'success') {
                Toast(response.status,response.msg);
                setTimeout(function () {
                    window.location = '/';
                }, 1000);
            } else {
                Toast(response.status,response.msg)
            }
            $('#Register').html('<p class="chakra-text nav-u3el3g">Đăng ký</p>')
                .prop('disabled', false);
        }
    });
});
</script>