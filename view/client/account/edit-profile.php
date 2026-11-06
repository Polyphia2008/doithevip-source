<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
    $title = "Sửa Thông Tin Tài Khoản";
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
                        <i class="fa-solid fa-user-pen ws-relative ws-top-[1px] ws-mr-1"></i> <?=$title;?>
                    </label>
                    <form action="<?=BASE_URL('ajax/profile/edit');?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">Tên đăng nhập</div>
                            <div class="ws-col-span-7">
                                <input type="text" class="el-input__inner" name="username" value="<?= $data_user['username'] ?>" <?= !empty($data_user['username']) ? 'readonly' : '' ?>>
                            </div>
                        </div>
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">Họ và tên</div>
                            <div class="ws-col-span-7">
                                <input type="text" class="el-input__inner" name="name" value="<?= $data_user['name'] ?>">
                            </div>
                        </div>
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">Email</div>
                            <div class="ws-col-span-7">
                                <input type="email" class="el-input__inner" name="email" value="<?= $data_user['email'] ?>" <?= !empty($data_user['email']) ? 'readonly' : '' ?>>
                            </div>
                        </div>
                        <div class="ws-grid ws-grid-cols-12 ws-gap-4 ws-text-sm ws-px-1 ws-mb-4">
                            <div class="ws-col-span-5 ws-text-zinc-700">SĐT</div>
                            <div class="ws-col-span-7">
                                <input type="number" class="el-input__inner" name="email" value="<?= $data_user['phone'] ?>" <?= !empty($data_user['phone']) ? 'readonly' : '' ?>>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-5 pt-4 border-top text-center text-md-right">
                            <a href="javascript:history.back()" class="btn btn-secondary btn-small"> Trở lại </a>
                            <button class="btn btn-success btn-small m-auto d-block">Cập nhật thông tin </button>
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