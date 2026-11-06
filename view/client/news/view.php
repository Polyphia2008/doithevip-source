<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
load_client_data('news/view');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
?>
<style>
.nav-spacing {
    margin-top: 5rem;
}
.v-content-post {
    line-height: 1.8;
    font-size: 16px;
    color: #1f2937;
}
.v-content-post img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 16px 0;
}
.news-meta span {
    font-size: 14px;
    color: #6b7280;
}
</style>
<div class="nav-15rfyaw nav-spacing ws-my-6 ws-px-2 md:ws-px-0 max-w-7xl mx-auto">

    <div class="ws-breadcrumb ws-text-sm ws-text-zinc-700 ws-py-2">
        <a href="/">Trang chủ</a>
        <i class="ws-relative ws-top-[1px] fa-solid fa-caret-right"></i>
        <a href="<?= BASE_URL('news/all'); ?>">
            <span>Tin tức</span>
        </a>
        <i class="ws-relative ws-top-[1px] fa-solid fa-caret-right"></i> <?= $news['title']; ?>
    </div>
    <div class="ws-grid ws-grid-cols-12 ws-gap-12">

        <!-- CONTENT -->
        <div class="ws-col-span-12 md:ws-col-span-12 ws-bg-white ws-rounded ws-px-2 ws-py-2 ws-mb-4" style="padding-inline: 1rem;">
            <!-- Content -->
            <div class="v-content-post">
                <?= $news['description']; ?>
            </div>
        </div>
    </div>
</div>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>
