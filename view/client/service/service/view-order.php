<style>


</style>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-module text-nowrap">
        <thead>
            <tr>
                <th>ID Gói, Mã Đơn</th>
                <th>Thông Tin Đơn Hàng</th> <th>Hình Ảnh</th>
                <th>Số Tiền</th>
                <th>Ngày Tạo</th>
                <th>Trạng Thái</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="dong">
                    ID Gói: <b class="text-primary">#<?= $order_item['service_id'] ?? '?' ?></b><br>
                    <b class="text-danger"><?= $order_item['order_code'] ?></b>
                </td>
                <td>
                    <?php foreach ($order_item['dynamic_fields'] as $field): ?>
                        <?= $field['label'] ?>:
                        <a class="text-primary">
                            <?= $field['value']; ?>
                            <button class="copy_data" data-clipboard-text="<?= $field['value']; ?>">
                                <i class="fa fa-copy"></i>
                            </button>
                        </a><br>
                    <?php endforeach; ?>
                </td>
                <td class="td-image">
                    <?php if (!empty($order_item['images'])): ?>
                        <div class="swiper acc-slider">
                            <div class="swiper-wrapper">
                                <?php foreach ($order_item['images'] as $img): ?>
                                    <div class="swiper-slide text-center">
                                        <a data-fancybox="gallery-<?= $order_item['service_id'] ?? 0 ?>"
                                        href="<?= BASE_URL($img); ?>"
                                        data-caption="<strong>ID Gói <?= anti_xss($order_item['service_id'] ?? 0) ?></strong>">
                                            <img src="<?= BASE_URL($img); ?>"
                                                class="img-fluid"
                                                style="width:80px;border-radius:4px;">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    <?php else: ?>
                        <span>Không có ảnh</span>
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
        var clipboard = new ClipboardJS('.copy_data');
        clipboard.on('success', function(e) {
            var button = e.trigger;
            var icon = button.querySelector('i');
            if (icon) {
                var originalClass = icon.className;
                icon.className = 'fa fa-check text-success';
                setTimeout(function() {
                    icon.className = 'fa fa-copy';
                }, 1000);
            }
            e.clearSelection();
        });
        clipboard.on('error', function(e) {
            alert('Lỗi: Không thể sao chép, vui lòng thử lại thủ công.');
        });
    })();
</script>
