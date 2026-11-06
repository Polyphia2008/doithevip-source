<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
$title = "Tất Cả Tin Tức";
load_client_data('news/all');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
?>
<div class="chakra-container nav-tintuc-cont">
    <!--Mua Nick-->
    <div class="nav-ozw706" data-aos="fade-up">
        <h2 class="ws-block ws-font-bold ws-text-lg"><?= $title; ?></h2>
    </div>
    <div class="nav-lddqu5">
        <?php if ($news): ?>
            <?php foreach ($news as $row): ?>
                <a href="<?= BASE_URL('news/view/' . $row['news_slug']); ?>">
                    <section class="nav-tintuc" data-aos="fade-up">
                        <div class="nav-1ookdhi-tintuc">
                            <img src="<?= BASE_URL($row['image']); ?>" class="chakra-image nav-uwwqev">
                        </div>
                        <div class="nav-1ookdhi-tintuc2">
                            <h1 class="chakra-heading nav-text-tintuc"><?= $row['title']; ?></h1>
                        </div>
                    </section>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <h2 class="ws-block ws-font-bold ws-text-lg">Chưa có bài viết nào</h2>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>