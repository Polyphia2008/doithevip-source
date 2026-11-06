<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
$title = "Thông Tin Tài Khoản";
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
                        <i class="fa-solid fa-user ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>
                    </label>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">ID</div>
                        <div class="ws-col-span-7"><?= isset($data_user) ? $data_user['id'] : 'Not Found' ?></div>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">Tên đăng nhập</div>
                        <div class="ws-col-span-7 ws-text-red-500">
                            <?= isset($data_user) ? $data_user['username'] : 'Not Found' ?></div>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">Họ và tên</div>
                        <div class="ws-col-span-7 ws-text-red-500">
                            <?= isset($data_user) ? $data_user['name'] : 'Not Found' ?></div>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">Email</div>
                        <div class="ws-col-span-7"><?= isset($data_user) ? $data_user['email'] : 'Not Found' ?></div>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">Nhóm</div>
                        <div class="ws-col-span-7 ws-text-red-500 ws-font-semibold">
                            <?= isset($data_user) ? $capbac['label'] : 'Not Found' ?></div>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">Số dư</div>
                        <div class="ws-col-span-7 ws-text-red-500 ws-font-semibold">
                            <?= isset($data_user) ? format_number($data_user['money']) : 'Not Found' ?>đ</div>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                        <div class="ws-col-span-5 ws-text-zinc-700">Ngày đăng ký</div>
                        <div class="ws-col-span-7"><?= isset($data_user) ? $data_user['create_date'] : 'Not Found' ?>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-top text-center text-md-left">
                        <a href="<?= BASE_URL('account/profile/edit/' . $data_user['id']); ?>" class="btn btn-success btn-small updateInformation"> Sửa thông tin </a>
                        <a href="<?= BASE_URL('account/change-password'); ?>" class="btn btn-secondary btn-small updateInformation"> Đổi mật khẩu </a>
                    </div>
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