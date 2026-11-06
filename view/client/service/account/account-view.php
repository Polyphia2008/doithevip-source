<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/libs/init.php';
$title = "Xem Chi Tiết Tài Khoản";
load_client_data('service/account-view');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Header.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Navbar.php');
?>
<style>
/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: var(--el-overlay-color-lighter);
}
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}
.modal-content,
#caption {
    animation-name: zoom;
    animation-duration: 0.6s;
}
@keyframes zoom {
    from {
        transform: scale(0)
    }
    to {
        transform: scale(1)
    }
}
.close_modal {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}
.close_modal:hover,
.close_modal:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
.flash-sale {
    align-items: center;
    background-image: url(/assets/images/background-flash-sale.jpg), linear-gradient(-90deg, #f0451e 9%, #f32424 96%);
    color: #fff;
    display: flex;
    height: 2.25rem;
    margin-top: .625rem;
    border-radius: 0.25rem;
}
.flash-sale {
    align-items: center;
    color: #fff;
    display: flex;
    height: 2.25rem;
    margin-top: .625rem;
    border-radius: 0.25rem;
}
.flash-sale .icon-flash {
    height: 1.3125rem;
    object-fit: contain;
    object-position: center;
    width: 7.625rem;
}
.flash-sale .icon-time {
    margin-left: auto;
    margin-right: .375rem;
}
.flash-sale .text-time {
    font-size: 1rem;
    margin-right: .625rem;
    text-transform: uppercase;
}
.flash-sale .timer {
    background-color: black;
    color: white;
    font-weight: bold;
    padding: 1px 3px;
    margin: 0px 0px 4px;
    border-radius: 5px;
    margin-right: 5px;
}
@media(max-width: 768px) {
    .title-time {
        display: none;
    }
}
.mySwiperView .swiper-slide {
    width: 90px !important;
}
.nav-spacing {
    margin-top: 5rem;
}
</style>
<div class="nav-15rfyaw nav-spacing ws-my-4 ws-px-2">
    <div class="ws-breadcrumb ws-text-sm ws-text-zinc-700 ws-py-2">
        <a href="/">Trang chủ</a>
        <i class="ws-relative ws-top-[1px] fa-solid fa-caret-right"></i>
        <a href="/service/account/view/<?=$check['slug'];?>">
            <span>Tài khoản</span>
        </a>
        <i class="ws-relative ws-top-[1px] fa-solid fa-caret-right"></i> Nick: <?=$row['id']?>
    </div>
    <div class="ws-mb-4 ws--mx-2 md:ws--mx-0 ws-bg-white md:ws-rounded">
        <div class="ws-grid ws-grid-cols-12 ws-gap-x-4">
            <div class="ws-col-span-12 md:ws-col-span-5 ws-py-3 ws-px-3 md:ws-px-5 md:ws-py-5 md:ws-pr-0">
                <div class="ws-col-span-12 md:ws-col-span-6">
                    <div class="ws-relative">
                        <img id="mainImage" src="<?= BASE_URL($old_images[0]) ?>" class="ws-rounded ws-w-full">
                        <label class="ws-absolute ws-right-[5px] ws-bottom-[5px] ws-text-xs ws-bg-red-600 ws-px-2 ws-rounded ws-text-white ws-font-medium ws-py-1">
                            1 / <?= count($old_images) ?>
                        </label>
                        <button type="button"
                            class="el-button-zoom el-button el-button--primary el-button--small is-has-bg ws-absolute ws-left-[5px] ws-bottom-[5px]">
                            <span>Phóng to</span>
                        </button>
                    </div>
                    <?php if ($row['type'] == 'tuchon') : ?>
                    <div>
                        <?php if (count($old_images) > 1): ?>
                        <div class="ws-mt-2">
                            <div class="swiper mySwiperView">
                                <div class="swiper-wrapper">
                                    <?php foreach ($old_images as $key => $img): ?>
                                        <div class="swiper-slide ws-cursor-pointer" data-index="<?= $key ?>">
                                            <div class="ws-w-full ws-relative">
                                                <span class="ws-absolute ws-font-medium ws-text-xs ws-px-2 ws-rounded ws-text-white ws-bg-zinc-900">
                                                    <?= $key + 1 ?>
                                                </span>
                                                <img
                                                    src="<?= BASE_URL($img) ?>"
                                                    class="ws-rounded ws-border ws-h-[55px]"
                                                    alt="thumb"
                                                >
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ws-col-span-12 md:ws-col-span-7 ws-py-3 md:ws-pl-1 ws-px-3 md:ws-px-5 md:ws-py-5">
                <div>
                    <h2 class="ws-text-xl"></h2>
                    <div class="ws-flex ws-my-2 md:ws-block ws-justify-between ws-items-center">
                        <div class="ws-w-[80%] md:ws-w-full">
                            <label class="ws-text-sm ws-font-bold">Mã số: <?=$row['id']?></label>
                        </div>
                    </div>
                </div>
                <p>
                    <span class="ws-text-sm">Danh Mục: <span class="ws-text-red-500"><?= $check['name'] ?></span></span>
                </p>
                <?php if ($is_flash_sale) : ?>
                <div class="flash-sale">
                    <img alt="icon flash sale" class="icon-flash" src="/assets/client/images/svg/flash.svg"><img
                        class="icon-time" alt="icon clock" src="/assets/client/images/svg/time.svg" height="17" width="17">
                    <div class="text-time"><span class="title-time">Kết thúc trong</span> <span class="timer"
                            id="hours"></span><span class="timer" id="minutes"></span><span class="timer"
                            id="seconds"></span></div>
                </div>
                <?php endif; ?>
                <p><span class="ws-text-sm"></span></p>
                <div class="ws-my-4 ws-grid ws-grid-cols-12 ws-gap-0">
                    <div class="ws-col-span-12">
                        <div style="background: linear-gradient(100deg, rgb(255, 66, 78), rgb(253, 130, 10));"
                            class="ws-rounded">
                            <div class="ws-flex ws-items-center ws-justify-between ws-px-3 ws-py-1">
                                <?php if ($is_flash_sale) : ?>
                                <div><span class="ws-text-white ws-text-sm ws-text-xs ws-font-semibold">GIÁ GỐC
                                        <i class="fa-solid fa-fire ws-relative ws-top-[0.8px]"></i></span>
                                    <h2 class="ws-text-white ws-stroke-red-500 ws-font-extrabold ws-text-2xl">
                                        <?= $price_after_sale_formatted ?>
                                        <small class="ws-relative ws-top-[-10px] ws-text-[10px]">đ</small></h2>

                                </div>
                                <div class="el-divider el-divider--vertical" style="--el-border-style:solid;"
                                    role="separator"></div>
                                <div><span class="ws-text-white ws-text-xs ws-font-semibold">GIẢM
                                        <?= $discount_amount_formatted ?>đ</span>
                                    <h2 class="ws-text-white ws-font-extrabold ws-text-2xl">
                                        <?= $final_price_formatted ?>
                                        <small class="ws-relative ws-top-[-10px] ws-text-[10px]">đ</small></h2>

                                </div>
                                <?php else : ?>
                                <div><span class="ws-text-white ws-text-sm ws-text-xs ws-font-semibold">GIÁ GỐC
                                        <i class="fa-solid fa-fire ws-relative ws-top-[0.8px]"></i></span>
                                    <h2 class="ws-text-white ws-stroke-red-500 ws-font-extrabold ws-text-2xl">
                                        <?= $price_original_formatted ?> <small
                                            class="ws-relative ws-top-[-10px] ws-text-[10px]">đ</small></h2>

                                </div>
                                <div>
                                    <span class="ws-text-white ws-text-xs ws-font-semibold">GIẢM
                                        <?= $discount_label ?></span>
                                    <h2 class="ws-text-white ws-font-extrabold ws-text-2xl">
                                        <?= $final_price_formatted ?>
                                        <small class="ws-relative ws-top-[-10px] ws-text-[10px]">đ</small></h2>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ws-bg-white ws-mb-4">
                    <label class="ws-text-sm ws-font-medium ws-uppercase ws-mb-2 ws-block">Thông Tin Chi Tiết</label>
                    <div>
                        <?php foreach ($detail_rows as $detail_row): ?>
                            <div class="ws-grid ws-grid-cols-12 ws-gap-1 ws-text-sm">
                                <div class="ws-mb-0.5 ws-col-span-4 md:ws-col-span-3 ws-py-2 ws-font-medium ws-px-2 ws-text-zinc-700 ws-bg-zinc-100">
                                    <?= $detail_row['label'] ?>
                                </div>
                                <div class="<?= $detail_row['row_class'] ?> ws-col-span-8 md:ws-col-span-9 ws-py-2 ws-px-2 ws-text-zinc-700">
                                    <span class="ws-inline-block ws-min-w-[5rem]">
                                        <?= $detail_row['value'] ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
                <div class="ws-mt-4">
                    <div class="ws-mt-4 ws-grid ws-grid-cols-12 ws-gap-2">
                        <div class="ws-col-span-12 md:ws-col-span-7">
                            <button onclick="openBuyingModal()" class="ws-bg-gradient-to-r ws-from-red-500 ws-to-orange-500 hover:ws-bg-gradient-to-l ws-transition ws-duration-300 focus:ws-outline-none ws-bg-orange-500 ws-text-white ws-w-full ws-text-lg ws-rounded ws-h-[50px]">
                                <b>
                                    <i class="fa-solid fa-basket-shopping ws-relative ws-top-[1px] ws-mr-1"></i> Mua Ngay
                                </b>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<div id="buyingModal" class="el-overlay ws-buying-modal" style="z-index: 2009;display: none;">
    <div role="dialog" aria-modal="true" aria-labelledby="el-id-1024-8" aria-describedby="el-id-1024-9" class="modal-dialog el-overlay-dialog" style="">
        <div class="el-dialog" tabindex="-1">
            <header class="el-dialog__header">
                <div class="ws-flex ws-items-center ws-justify-between">
                    <div class="ws-text-white ws-font-semibold">
                        <small>
                            <span>XÁC NHẬN </span>MUA TÀI KHOẢN </small>
                        <p class="ws-text-2xl">#<?=$row['id']?></p>
                    </div>
                    <button onclick="closeBuyingModal()" class="focus:ws-outline-none ws-w-4 ws-h-4 ws-flex ws-items-center ws-justify-center ws-rounded ws-text-xl ws-text-white">
                        <i class="fa-solid fa-x"></i>
                    </button>
                </div>
            </header>
            <div id="el-id-1024-9" class="el-dialog__body">
                <div class="ws-p-3">
                    <div class="ws-mb-2">
                        <label class="ws-text-sm ws-font-medium ws-uppercase ws-text-zinc-800 ws-mb-2 ws-block">Thông Tin Chi Tiết</label>
                        <?php foreach ($detail_rows as $detail_row): ?>
                            <div class="ws-grid ws-grid-cols-12 ws-gap-1 ws-text-sm">
                                <div class="ws-mb-0.5 ws-col-span-4 md:ws-col-span-3 ws-py-2 ws-font-medium ws-px-2 ws-text-zinc-700 ws-bg-zinc-100">
                                    <?= $detail_row['label'] ?>
                                </div>
                                <div class="<?= $detail_row['modal_row_class'] ?>">
                                    <span class="ws-inline-block ws-min-w-[5rem]">
                                        <?= $detail_row['value'] ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="ws-mb-4">
                        <div class="ws-flex ws-justify-between ws-items-center">
                            <div class="ws-relative ws-font-medium ws-text-zinc-700 ws-uppercase">
                                <span>Số dư của bạn</span>
                            </div>
                            <div class="ws-text-right ws-font-bold ws-text-base ws-text-red-600"><?= format_number(!empty($data_user['money']) ? $data_user['money'] : 0); ?>đ
                                <button class="ws-px-1">
                                    <i data-toggle="modal" data-target="#naptienModal" class="ws-text-red-500 ws-relative ws-text-xl fa-solid fa-square-plus" style="top: 3px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="ws-border-b ws-border-gray-100 ws-my-2"></div>
                    <div class="ws-flex ws-items-center">
                        <div class="el-input el-input--large el-input--prefix el-input--suffix ws-w-full ws-mr-2">
                            <div class="el-input__wrapper" tabindex="-1">
                                <span class="el-input__prefix">
                                    <span class="el-input__prefix-inner">
                                        <i class="el-icon el-input__icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                                                <path fill="currentColor" d="M640 832H64V640a128 128 0 1 0 0-256V192h576v160h64V192h256v192a128 128 0 1 0 0 256v192H704V672h-64zm0-416v192h64V416z"></path>
                                            </svg>
                                        </i>
                                    </span>
                                </span>
                                <input class="el-input__inner2" type="text" autocomplete="off" tabindex="0" placeholder="Mã giảm giá" id="coupon">
                            </div>
                        </div>
                        <div>
                            <button type="button" class="el-button el-button--primary el-button--large btn-apply-coupon is-disabled" disabled>
                                <span>Áp dụng</span>
                            </button>

                        </div>
                    </div>
                    <div class="ws-border-b ws-border-gray-100 ws-my-2"></div>
                    <div class="ws-my-4 ws-flex ws-justify-between">
                        <label class="ws-font-semibold ws-text-red-600 ws-text-base">GIÁ TÀI KHOẢN</label>
                        <span class="ws-font-bold ws-text-red-600 ws-text-xl final-price"><?= format_number($final_price) ?>đ</span>
                    </div>
                    <div class="ws-grid ws-grid-cols-12 ws-gap-x-2 ws-py-2">
                        <div class="ws-col-span-8">
                            <button onclick="confirmPurchase(<?=$row['id']?>)" aria-disabled="false" type="button" class="el-button el-button--primary el-button--large ws-w-full" style="font-size: 1rem;">
                                <span class="">Xác nhận mua</span>
                            </button>
                        </div>
                        <div class="ws-col-span-4">
                            <button onclick="closeBuyingModal()" aria-disabled="false" type="button" class="el-button el-button--large ws-w-full">
                                <span class="">Đóng</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal">
    <span class="close_modal">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>
<script>
function openBuyingModal() {
    document.getElementById('buyingModal').style.display = 'block';
}
function closeBuyingModal() {
    document.getElementById('buyingModal').style.display = 'none';
}
function confirmPurchase(id) {
    var coupon = document.getElementById('coupon').value;
    if (coupon !== '') {
        $.ajax({
            url: "<?=BASE_URL('ajax/action/check-coupon');?>",
            method: "POST",
            dataType: 'json',
            data: {
                action: 'coupon-acc',
                id: id,
                coupon: coupon,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(data) {
                if (data.status == 'error') {
                    Toast(data.status, data.msg);
                    return;
                }
                $.ajax({
                    url: "<?=BASE_URL('ajax/invoice');?>",
                    method: "POST",
                    dataType: 'json',
                    data: {
                        type: "mua-tai-khoan",
                        id: id,
                        coupon: coupon,
                        csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Toast(response.status, response.msg);
                            setTimeout(function(){ window.location='/History'; }, 1000);
                        } else {
                            Toast(response.status, response.msg);
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi thực hiện giao dịch. Vui lòng thử lại sau.');
                    }
                });
            }
        });
    } else {
        $.ajax({
            url: "<?=BASE_URL('ajax/invoice');?>",
            method: "POST",
            dataType: 'json',
            data: {
                type: "mua-tai-khoan",
                id: id,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(response) {
                if (response.status == 'success') {
                    Toast(response.status, response.msg);
                    setTimeout(function(){ window.location='/History'; }, 1000);
                } else {
                    Toast(response.status, response.msg);
                }
            }
        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var couponInput = document.getElementById('coupon');
    var applyBtn = document.querySelector('#buyingModal .btn-apply-coupon');
    var priceLabel = document.querySelector('#buyingModal .final-price');
    var id = <?=$row['id']?>; // ID sản phẩm
    // Enable/disable nút Áp dụng
    couponInput.addEventListener('input', function() {
        if (couponInput.value.trim() !== "") {
            applyBtn.removeAttribute('disabled');
            applyBtn.classList.remove('is-disabled');
        } else {
            applyBtn.setAttribute('disabled', 'true');
            applyBtn.classList.add('is-disabled');
        }
    });
    // Xử lý click "Áp dụng"
    applyBtn.addEventListener('click', function() {
        var coupon = couponInput.value.trim();
        if (coupon === "") return;
        $.ajax({
            url: "<?=BASE_URL('ajax/action/check-coupon');?>",
            method: "POST",
            dataType: "json",
            data: {
                action: "coupon-acc",
                id: id,
                coupon: coupon,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(res) {
                if (res.status === 'success') {
                    Toast('success', res.msg);
                    if (priceLabel) {
                        priceLabel.textContent = res.price.toLocaleString() + 'đ';
                    }
                } else {
                    Toast('error', res.msg);
                }
            },
            error: function() {
                Toast('error', 'Có lỗi xảy ra, vui lòng thử lại sau!');
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    var totalImages = document.querySelectorAll('.swiper-slide').length;
    console.log(totalImages);
    var slidesPerView = totalImages < 5 ? totalImages : 3;
    var mySwiper = new Swiper('.mySwiperView', {
        loop: false,
        loopedSlides: totalImages,
        autoplay: {
            delay: 3000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function(index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
        navigation: {
            nextEl: '.myNext',
            prevEl: '.myPrev',
        },
        slidesPerView: slidesPerView,
        spaceBetween: 5,
        on: {
            slideChange: function() {
                var activeIndex = this.activeIndex;
                var bullets = document.querySelectorAll('.swiper-pagination-bullet');
                bullets.forEach(function(bullet, index) {
                    if (index === activeIndex) {
                        bullet.classList.add('swiper-pagination-bullet-active');
                    } else {
                        bullet.classList.remove('swiper-pagination-bullet-active');
                    }
                });
            },
        },
    });
    document.querySelectorAll('.swiper-slide').forEach(function(slide) {
        slide.addEventListener('click', function() {
            var slideIndex = slide.getAttribute('data-index');
            document.querySelectorAll('.swiper-slide img').forEach(function(img) {
                img.classList.remove('ws-border-red-600');
            });
            document.querySelectorAll('.swiper-slide span').forEach(function(span) {
                span.classList.remove('ws-bg-red-700');
                span.classList.add('ws-bg-zinc-900');
            });
            var img = slide.querySelector('img');
            var span = slide.querySelector('span');
            if (img) img.classList.add('ws-border-red-600');
            if (span) {
                span.classList.remove('ws-bg-zinc-900');
                span.classList.add('ws-bg-red-700');
            }
            var mainImage = document.getElementById('mainImage');
            mainImage.src = img.src;
            var label = document.querySelector('.ws-relative label');
            label.textContent = (parseInt(slideIndex) + 1) + ' / ' + totalImages;
        });
    });
    // Get the modal
    var modal = document.getElementById('myModal');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    var mainImage = document.getElementById('mainImage');
    var zoomBtn = document.querySelector('.el-button-zoom');
    zoomBtn.onclick = function() {
        modal.style.display = "block";
        modalImg.src = mainImage.src;
        captionText.innerHTML = mainImage.alt;
    }
    var span = document.getElementsByClassName("close_modal")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }
});
</script>
<?php if ($discountPrice['flashSaleStatus'] == "ongoing") : ?>
    <script>
        // Thời gian bắt đầu và kết thúc của flash sale
        const flashSaleStartTime = new Date('<?= date('Y-m-d\TH:i:s', strtotime($discountPrice['start_time'])) ?>')
        .getTime();
        const flashSaleEndTime = new Date('<?= date('Y-m-d\TH:i:s', strtotime($discountPrice['end_time'])) ?>').getTime();
        // Hàm cập nhật bộ đếm ngược
        function updateCountdown() {
            const now = new Date().getTime();
            if (now < flashSaleStartTime) {
                document.getElementById('countdown-timer').innerHTML = "Chưa bắt đầu";
                return;
            }
            const timeLeft = flashSaleEndTime - now;
            if (timeLeft <= 0) {
                showMessage('Chương trình Flash Sale đã kết thúc', 'error');
                clearInterval(countdownInterval); // Dừng bộ đếm ngược
                return;
            }
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            document.getElementById('hours').textContent = hours < 10 ? '0' + hours : hours;
            document.getElementById('minutes').textContent = minutes < 10 ? '0' + minutes : minutes;
            document.getElementById('seconds').textContent = seconds < 10 ? '0' + seconds : seconds;
        }
        // Cập nhật bộ đếm ngược mỗi giây
        const countdownInterval = setInterval(updateCountdown, 1000);
        // Gọi hàm để cập nhật ngay lập tức khi trang được tải
        updateCountdown();
    </script>
<?php endif; ?>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/view/client/layout/Footer.php');
?>
