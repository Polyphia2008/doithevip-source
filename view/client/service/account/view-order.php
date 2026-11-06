<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-module text-nowrap">
        <thead>
            <tr>
                <th>ID Nick, Mã Đơn</th>
                <th>Tài Khoản, Mật Khẩu</th>
                <th>Hình Ảnh</th>
                <th>Số Tiền</th>
                <th>Ngày Tạo</th>
                <th>Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="dong">ID Nick: <b class="text-primary">#<?= $order_item['service_id'] ?></b><br><b class="text-danger"><?= $order_item['order_code'] ?></b>
                </td>
                <td>
                    Tài khoản: <a class="text-primary">
                        <?= $order_item['taikhoan']; ?>
                        <button class="copy_taikhoan" data-clipboard-text="<?= $order_item['taikhoan']; ?>">
                            <i class="fa fa-copy"></i>
                        </button>
                    </a><br>
                    Mật khẩu:
                    <a class="text-danger">
                        <?= $order_item['matkhau']; ?>
                        <button class="copy_matkhau" data-clipboard-text="<?= $order_item['matkhau']; ?>">
                            <i class="fa fa-copy"></i>
                        </button>
                    </a>
                </td>
                <td class="td-image">
                    <?php if (!empty($order_item['images'])): ?>
                    <div class="swiper acc-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($order_item['images'] as $img): ?>
                                <div class="swiper-slide text-center">
                                    <a data-fancybox="gallery-<?= $order_item['service_id'] ?>"
                                    href="<?= BASE_URL($img); ?>"
                                    data-caption="<strong>ID Nick <?= anti_xss($order_item['service_id']) ?></strong>">
                                        <img src="<?= BASE_URL($img); ?>"
                                            class="img-fluid"
                                            style="width:80px;border-radius:4px;">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <?php endif; ?>
                </td>
                <td><?= $order_item['amount_formatted'] ?>đ</td>
                <td><?= $order_item['created_at'] ?></td>
                <td><span class="<?= $order_item['status_class'] ?>"><?= $order_item['status_text'] ?></span></td>
            </tr>

        </tbody>
    </table>
</div>
<script type="text/javascript" src="/assets/client/js/clipboard.min.js"></script>
<script>
    (function(){
        const c1 = new ClipboardJS('.copy_taikhoan');
        const c2 = new ClipboardJS('.copy_matkhau');
    })();
</script>
