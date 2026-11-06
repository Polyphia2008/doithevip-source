<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Đổi Mật Khẩu";
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Sidebar.php');
    check_not_login_redirect();
?>

<div class="ws-col-span-12 md:ws-col-span-9 ws-min-h-[70vh]">
    <div>
        <div class="ws-relative">
            <div class="ws-bg-white ws-relative ws-my-1 ws-py-3 md:ws-py-4 ws-px-4 ws-grid ws-grid-cols-12 ws-gap-x-4 ws-gap-y-2 ws-rounded-none md:ws-rounded">
                <div class="ws-absolute ws-border-skz"></div>
                <div class="ws-col-span-12 md:ws-col-span-12">
                    <label class="ws-block ws-mb-4 ws-text-zinc-700">
                        <i class="fa-solid fa-key ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>
                    </label>
                    <form action="<?=BASE_URL('ajax/change-password');?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">Mật khẩu hiện tại</div>
                            <div class="ws-col-span-7">
                                <input type="password" class="el-input__inner" name="old-pwd" required="" placeholder="Mật khẩu hiện tại">
                            </div>
                        </div>
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">Mật khẩu mới</div>
                            <div class="ws-col-span-7">
                                <input type="password" class="el-input__inner" name="new-pwd" required="" placeholder="Mật khẩu mới">
                            </div>
                        </div>
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">Xác nhận mật khẩu</div>
                            <div class="ws-col-span-7">
                                <input type="password" class="el-input__inner" name="new-pwd-confirmation" required="" placeholder="Xác nhận mật khẩu">
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-5 pt-4 border-top text-center text-md-right">
                            <a href="javascript:history.back()" class="btn btn-secondary btn-small"> Trở lại </a>
                            <button class="btn btn-success btn-small m-auto d-block">Xác nhận </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<?php
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>